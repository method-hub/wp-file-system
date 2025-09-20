<?php

use Metapraxis\WPFileSystem\Facade\WPFS;

/**
 * This function hooks into comment submission to run a comprehensive E2E test
 * of the WPFS facade. It performs a series of file and directory manipulations
 * and writes a JSON report file with the results.
 *
 * @param int $comment_post_ID The ID of the post the comment was submitted to.
 */
function wpfs_e2e_run_colossal_test(int $comment_post_ID)
{
    // 1. SETUP PATHS AND INITIAL DATA
    $authorName = isset($_POST['author']) ? sanitize_text_field($_POST['author']) : 'Unknown';
    $baseDir = WP_CONTENT_DIR . '/wpfs-e2e-test';
    $subDir = $baseDir . '/subdir';

    $mainLogFile = $baseDir . '/main.log';
    $copiedLogFile = $baseDir . '/main.copy.log';
    $movedLogFile = $subDir . '/main.moved.log';
    $jsonDataFile = $baseDir . '/data.json';
    $reportFile = WP_CONTENT_DIR . '/wpfs-e2e-report.json';

    $report = [
        'directory_created' => false,
        'subdirectory_created' => false,
        'initial_content_correct' => false,
        'file_copied' => false,
        'original_file_after_copy_exists' => false,
        'file_moved' => false,
        'original_file_after_move_exists' => false,
        'moved_file_content_correct' => false,
        'appended_content_correct' => false,
        'json_written' => false,
        'json_read_and_verified' => false,
        'cleanup_successful' => false,
    ];

    try {
        // DIRECTORY OPERATIONS
        WPFS::createDirectory($baseDir);
        $report['directory_created'] = WPFS::isDirectory($baseDir);

        WPFS::createDirectory($subDir);
        $report['subdirectory_created'] = WPFS::isDirectory($subDir);

        // 3. FILE WRITING
        $initialContent = "Test run initiated by: " . $authorName;
        WPFS::putContents($mainLogFile, $initialContent);
        $report['initial_content_correct'] = (WPFS::getContents($mainLogFile) === $initialContent);

        // 4. COPY OPERATION
        WPFS::copyFile($mainLogFile, $copiedLogFile);
        $report['file_copied'] = WPFS::isFile($copiedLogFile) &&
            (WPFS::getContents($copiedLogFile) === $initialContent);
        $report['original_file_after_copy_exists'] = WPFS::isFile($mainLogFile);

        // 5. MOVE OPERATION
        WPFS::moveFile($copiedLogFile, $movedLogFile);
        $report['file_moved'] = WPFS::isFile($movedLogFile);
        $report['original_file_after_move_exists'] = !WPFS::exists($copiedLogFile);
        $report['moved_file_content_correct'] = (WPFS::getContents($movedLogFile) === $initialContent);

        // 6. FILE APPEND
        $appendedContent = "\nLog finished at: " . date('Y-m-d H:i:s');
        WPFS::append($mainLogFile, $appendedContent);
        $report['appended_content_correct'] = (WPFS::getContents($mainLogFile) === $initialContent . $appendedContent);

        // 7. JSON OPERATIONS
        $jsonData = ['status' => 'success', 'author' => $authorName, 'timestamp' => time()];
        WPFS::writeJson($jsonDataFile, $jsonData);
        $report['json_written'] = WPFS::isFile($jsonDataFile);

        $readJsonData = WPFS::readJson($jsonDataFile);
        $report['json_read_and_verified'] =
            ($readJsonData['status'] === 'success' && $readJsonData['author'] === $authorName);
    } catch (Exception $e) {
        $report['error'] = $e->getMessage();
    } finally {
        // 8. CLEANUP
        WPFS::delete($baseDir);
    }

    // 9. WRITE FINAL REPORT
    WPFS::writeJson($reportFile, $report, JSON_PRETTY_PRINT);
}

add_action('comment_post', 'wpfs_e2e_run_colossal_test');
