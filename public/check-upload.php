<?php
/**
 * PHP Upload Limitleri Kontrol Dosyası
 *
 * Bu dosyayı canlı sunucunuza yükleyip tarayıcıdan açarak
 * PHP upload ayarlarınızı kontrol edebilirsiniz
 *
 * Kullanım: public/check-upload.php olarak kaydedin ve
 * https://yoursite.com/check-upload.php adresini açın
 */
$requiredSettings = [
    'upload_max_filesize' => '25M',
    'post_max_size' => '30M',
    'memory_limit' => '256M',
    'max_execution_time' => '300',
];

function parseSize($size)
{
    if ($size === '-1') {
        return -1;
    }
    $unit = strtoupper(substr($size, -1));
    $value = (int) substr($size, 0, -1);

    switch ($unit) {
        case 'G': $value *= 1024;
        case 'M': $value *= 1024;
        case 'K': $value *= 1024;
    }

    return $value;
}

function formatSize($bytes)
{
    if ($bytes === -1) {
        return 'Unlimited';
    }
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }

    return round($bytes, 2).' '.$units[$i];
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Upload Ayarları Kontrol</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .check { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .success { background: #dcfce7; border-color: #16a34a; }
        .warning { background: #fef3c7; border-color: #f59e0b; }
        .error { background: #fee2e2; border-color: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 600; }
        .status { padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status.ok { background: #dcfce7; color: #16a34a; }
        .status.warning { background: #fef3c7; color: #f59e0b; }
        .status.error { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔍 PHP Upload Ayarları Kontrol</h1>
        <p>20MB dosya upload için gerekli PHP ayarlarını kontrol edin</p>
    </div>

    <?php
    $allOk = true;
$warnings = [];
$errors = [];

echo '<div class="check">';
echo '<h2>📋 Mevcut PHP Ayarları</h2>';
echo '<table>';
echo '<thead><tr><th>Ayar</th><th>Mevcut Değer</th><th>Gerekli Değer</th><th>Durum</th></tr></thead>';
echo '<tbody>';

foreach ($requiredSettings as $setting => $required) {
    $current = ini_get($setting);
    $currentBytes = parseSize($current);
    $requiredBytes = parseSize($required);

    $status = 'ok';
    $statusText = 'OK';

    if ($currentBytes !== -1 && $requiredBytes !== -1 && $currentBytes < $requiredBytes) {
        $status = 'error';
        $statusText = 'DÜŞÜK';
        $allOk = false;
        $errors[] = "$setting değeri $current, olması gereken $required";
    } elseif ($currentBytes !== -1 && $requiredBytes !== -1 && $currentBytes < ($requiredBytes * 1.5)) {
        $status = 'warning';
        $statusText = 'SINIRDA';
        $warnings[] = "$setting değeri $current, daha yüksek olabilir";
    }

    echo '<tr>';
    echo "<td><code>$setting</code></td>";
    echo '<td>'.formatSize($currentBytes)." <small>($current)</small></td>";
    echo '<td>'.formatSize($requiredBytes)." <small>($required)</small></td>";
    echo "<td><span class='status $status'>$statusText</span></td>";
    echo '</tr>';
}

echo '</tbody></table>';
echo '</div>';

// Özet
if ($allOk && empty($warnings)) {
    echo '<div class="check success">';
    echo '<h2>✅ Tebrikler!</h2>';
    echo '<p><strong>Tüm PHP ayarları 20MB dosya upload için uygun.</strong></p>';
    echo '<p>Artık Laravel uygulamanızda 20MB\'a kadar dosya yükleyebilirsiniz.</p>';
    echo '</div>';
} elseif (! empty($warnings) && empty($errors)) {
    echo '<div class="check warning">';
    echo '<h2>⚠️ Uyarı</h2>';
    echo '<p>Ayarlar çalışır durumda ama iyileştirilebilir:</p>';
    echo '<ul>';
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo '</ul>';
    echo '</div>';
} else {
    echo '<div class="check error">';
    echo '<h2>❌ Sorun Var</h2>';
    echo '<p><strong>Aşağıdaki ayarları düzeltmeniz gerekiyor:</strong></p>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul>';
    echo '</div>';
}
?>

    <div class="check">
        <h2>🛠️ Nasıl Düzeltilir?</h2>

        <h3>1. cPanel/Hosting Panel</h3>
        <p>PHP Ayarları > PHP Options kısmından yukarıdaki değerleri girin.</p>

        <h3>2. .htaccess Dosyası</h3>
        <p>Web sitenizin ana dizinindeki .htaccess dosyasına şu satırları ekleyin:</p>
        <pre style="background: #f1f5f9; padding: 15px; border-radius: 4px; overflow-x: auto;">
php_value upload_max_filesize 25M
php_value post_max_size 30M
php_value memory_limit 256M
php_value max_execution_time 300</pre>

        <h3>3. Hosting Desteği</h3>
        <p>Yukarıdaki yöntemler işe yaramazsa hosting firmanızdan bu PHP ayarlarını artırmasını isteyin.</p>
    </div>

    <div class="check">
        <h2>📊 Sistem Bilgileri</h2>
        <table>
            <tr><td><strong>PHP Version</strong></td><td><?php echo phpversion(); ?></td></tr>
            <tr><td><strong>Server Software</strong></td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor'; ?></td></tr>
            <tr><td><strong>Document Root</strong></td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Bilinmiyor'; ?></td></tr>
            <tr><td><strong>Max File Uploads</strong></td><td><?php echo ini_get('max_file_uploads'); ?></td></tr>
            <tr><td><strong>File Uploads</strong></td><td><?php echo ini_get('file_uploads') ? 'Enabled' : 'Disabled'; ?></td></tr>
        </table>
    </div>

    <p style="text-align: center; color: #64748b; margin-top: 40px;">
        <small>Bu kontrol sayfasını test ettikten sonra güvenlik için silebilirsiniz.</small>
    </p>

</body>
</html>
