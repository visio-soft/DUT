<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ChunkedUploadController extends Controller
{
    private string $uploadPath = 'temp/chunked-uploads';

    /**
     * Handle chunked file uploads
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $action = $request->input('action');

            switch ($action) {
                case 'init':
                    return $this->initializeUpload($request);
                case 'chunk':
                    return $this->uploadChunk($request);
                case 'finalize':
                    return $this->finalizeUpload($request);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action'
                    ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Chunked upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initialize upload session
     */
    private function initializeUpload(Request $request): JsonResponse
    {
        $uploadId = $request->input('upload_id');
        $filename = $request->input('filename');
        $filesize = $request->input('filesize');
        $filetype = $request->input('filetype');
        $totalChunks = $request->input('total_chunks');

        // Validate file size (still check against our app limit)
        $maxSize = config('upload.max_file_size_bytes', 52428800); // 50MB
        if ($filesize > $maxSize) {
            return response()->json([
                'success' => false,
                'message' => 'File size exceeds maximum allowed size of ' . round($maxSize / 1024 / 1024, 2) . ' MB'
            ], 413);
        }

        // Validate file type
        $allowedTypes = array_merge(
            config('upload.allowed_image_types', []),
            config('upload.allowed_document_types', [])
        );

        if (!in_array($filetype, $allowedTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'File type not allowed: ' . $filetype
            ], 415);
        }

        // Create upload session directory
        $sessionPath = $this->uploadPath . '/' . $uploadId;
        Storage::makeDirectory($sessionPath);

        // Store session metadata
        $metadata = [
            'upload_id' => $uploadId,
            'filename' => $filename,
            'filesize' => $filesize,
            'filetype' => $filetype,
            'total_chunks' => $totalChunks,
            'chunks_received' => [],
            'created_at' => now()->toISOString(),
            'model_type' => $request->input('model_type'),
            'collection' => $request->input('collection'),
            'field_name' => $request->input('field_name'),
        ];

        Storage::put($sessionPath . '/metadata.json', json_encode($metadata));

        Log::info('Chunked upload initialized', $metadata);

        return response()->json([
            'success' => true,
            'upload_id' => $uploadId,
            'message' => 'Upload session initialized'
        ]);
    }

    /**
     * Upload individual chunk
     */
    private function uploadChunk(Request $request): JsonResponse
    {
        $uploadId = $request->input('upload_id');
        $chunkIndex = (int) $request->input('chunk_index');
        $totalChunks = (int) $request->input('total_chunks');
        $chunk = $request->file('chunk');

        if (!$chunk) {
            return response()->json([
                'success' => false,
                'message' => 'No chunk file provided'
            ], 400);
        }

        $sessionPath = $this->uploadPath . '/' . $uploadId;

        // Check if session exists
        if (!Storage::exists($sessionPath . '/metadata.json')) {
            return response()->json([
                'success' => false,
                'message' => 'Upload session not found'
            ], 404);
        }

        // Store chunk
        $chunkPath = $sessionPath . '/chunk_' . str_pad($chunkIndex, 4, '0', STR_PAD_LEFT);
        $chunk->storeAs('', $chunkPath, 'local');

        // Update metadata
        $metadata = json_decode(Storage::get($sessionPath . '/metadata.json'), true);
        $metadata['chunks_received'][] = $chunkIndex;
        $metadata['last_chunk_at'] = now()->toISOString();

        Storage::put($sessionPath . '/metadata.json', json_encode($metadata));

        Log::info("Chunk uploaded: {$uploadId} chunk {$chunkIndex}/{$totalChunks}");

        return response()->json([
            'success' => true,
            'chunk_index' => $chunkIndex,
            'total_chunks' => $totalChunks,
            'progress' => round(((count($metadata['chunks_received']) / $totalChunks) * 100), 2)
        ]);
    }

    /**
     * Finalize upload by combining chunks
     */
    private function finalizeUpload(Request $request): JsonResponse
    {
        $uploadId = $request->input('upload_id');
        $sessionPath = $this->uploadPath . '/' . $uploadId;

        // Get metadata
        if (!Storage::exists($sessionPath . '/metadata.json')) {
            return response()->json([
                'success' => false,
                'message' => 'Upload session not found'
            ], 404);
        }

        $metadata = json_decode(Storage::get($sessionPath . '/metadata.json'), true);
        $totalChunks = $metadata['total_chunks'];
        $filename = $metadata['filename'];

        // Check if all chunks are present
        if (count($metadata['chunks_received']) !== $totalChunks) {
            return response()->json([
                'success' => false,
                'message' => 'Missing chunks: expected ' . $totalChunks . ', received ' . count($metadata['chunks_received'])
            ], 400);
        }

        // Combine chunks into final file
        $finalPath = 'uploads/' . date('Y/m/d') . '/' . uniqid() . '_' . $filename;
        $this->combineChunks($sessionPath, $finalPath, $totalChunks);

        // Verify file size
        $finalSize = Storage::size($finalPath);
        if ($finalSize != $metadata['filesize']) {
            // Clean up
            Storage::delete($finalPath);
            $this->cleanupSession($sessionPath);

            return response()->json([
                'success' => false,
                'message' => 'File size mismatch after combining chunks'
            ], 500);
        }

        // Clean up temporary files
        $this->cleanupSession($sessionPath);

        Log::info('Chunked upload completed', [
            'upload_id' => $uploadId,
            'filename' => $filename,
            'final_path' => $finalPath,
            'size' => $finalSize
        ]);

        return response()->json([
            'success' => true,
            'file_id' => $uploadId,
            'file_path' => $finalPath,
            'file_url' => Storage::url($finalPath),
            'file_size' => $finalSize,
            'filename' => $filename,
            'message' => 'File upload completed successfully'
        ]);
    }

    /**
     * Combine chunks into final file
     */
    private function combineChunks(string $sessionPath, string $finalPath, int $totalChunks): void
    {
        $finalFile = Storage::path($finalPath);
        $finalDir = dirname($finalFile);

        // Ensure directory exists
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0755, true);
        }

        $outputHandle = fopen($finalFile, 'wb');

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $sessionPath . '/chunk_' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $chunkFile = Storage::path($chunkPath);

            if (file_exists($chunkFile)) {
                $chunkHandle = fopen($chunkFile, 'rb');
                stream_copy_to_stream($chunkHandle, $outputHandle);
                fclose($chunkHandle);
            }
        }

        fclose($outputHandle);
    }

    /**
     * Clean up temporary session files
     */
    private function cleanupSession(string $sessionPath): void
    {
        try {
            Storage::deleteDirectory($sessionPath);
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup session: ' . $sessionPath, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Clean up old upload sessions (should be run via cron)
     */
    public function cleanupOldSessions(): JsonResponse
    {
        $cutoff = now()->subHours(24); // Remove sessions older than 24 hours
        $cleaned = 0;

        $directories = Storage::directories($this->uploadPath);

        foreach ($directories as $dir) {
            $metadataPath = $dir . '/metadata.json';

            if (Storage::exists($metadataPath)) {
                $metadata = json_decode(Storage::get($metadataPath), true);
                $createdAt = \Carbon\Carbon::parse($metadata['created_at']);

                if ($createdAt->lt($cutoff)) {
                    Storage::deleteDirectory($dir);
                    $cleaned++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'cleaned_sessions' => $cleaned,
            'message' => "Cleaned up {$cleaned} old upload sessions"
        ]);
    }
}
