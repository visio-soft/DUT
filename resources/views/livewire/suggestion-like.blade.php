<div class="d-flex align-items-center">
    <button wire:click.prevent="toggleLike" class="btn btn-sm {{ $liked ? 'btn-primary' : 'btn-outline-primary' }} me-2">
        {{ $liked ? 'Beğenildi' : 'Beğen' }}
    </button>
    <span class="text-muted small">{{ $likesCount }}</span>
</div>
