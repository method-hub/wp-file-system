<?php

// 1. WP-core loading
$wp_load_path = __DIR__;
while (! file_exists($wp_load_path . '/wp-load.php')) {
    $wp_load_path = dirname($wp_load_path);
    if (empty($wp_load_path) || $wp_load_path === '/') {
        echo "Error: Could not find wp-load.php\n";
        exit(1);
    }
}

require_once $wp_load_path . '/wp-load.php';

// 2. Clear comments before test
$all_comments = get_comments(['fields' => 'ids', 'status' => 'all']);
if (! empty($all_comments)) {
    foreach ($all_comments as $comment_id) {
        wp_delete_comment($comment_id, true);
    }
    echo "Deleted " . count($all_comments) . " comment(s).\n";
} else {
    echo "No comments to delete.\n";
}

/**
 * 3. Clear files before tests
 * @param string $dir
 */
function deleteDirectory(string $dir)
{
    if (!is_dir($dir)) {
        return;
    }
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}

$reportFile = WP_CONTENT_DIR . '/wpfs-e2e-report.json';
$tempDirPattern = WP_CONTENT_DIR . '/wpfs-e2e-test';

if (file_exists($reportFile)) {
    unlink($reportFile);
    echo "Deleted report file: " . $reportFile . "\n";
}

$tempDirs = glob($tempDirPattern);
if (!empty($tempDirs)) {
    foreach ($tempDirs as $dir) {
        if (is_dir($dir)) {
            deleteDirectory($dir);
            echo "Deleted temporary directory: " . $dir . "\n";
        }
    }
}

echo "E2E cleanup complete.\n";
