import { test, expect } from '@playwright/test';
import { execSync } from 'child_process';

test.describe('WPFS Facade E2E Test via Comment Submission', () => {
    const reportFilePath = '/var/www/html/wp-content/wpfs-e2e-report.json';
    const cleanupScriptPath = '/var/www/html/wp-content/plugins/metapraxis-wp-file-system/tests/E2E/cleanup.php';

    test.beforeEach(() => {
        console.log('Running universal cleanup script...');
        const cleanupCommand = `npm run wp-env run cli -- php ${cleanupScriptPath}`;
        execSync(cleanupCommand, { stdio: 'inherit' });
        console.log('Cleanup finished.');
    });

    test('should trigger a series of WPFS operations and verify the results', async ({ page }) => {
        await page.goto('/wp-login.php');
        await page.locator('#user_login').fill('admin');
        await page.locator('#user_pass').fill('password');
        await page.locator('#wp-submit').click();

        await expect(page.locator('#wpadminbar')).toBeVisible();

        // 1. Navigate to a post-page.
        await page.goto('/?p=1');

        // 2. Fill out the comment form.
        await page.locator('#comment').fill(`This is an auto-approved comment from an admin from ${Date.now()}.`);

        // 3. Submit the form.
        await page.locator('#submit').click();

        // 4. Wait for the comment to appear on the page.
        await expect(page.locator('.wp-block-comment-author-name:has-text("admin")'))
            .toBeVisible({ timeout: 10000 });

        // 5. Read the final report file from within the container.
        const command = `npm run wp-env run cli -- bash -c "cat ${reportFilePath}"`;
        const rawOutput = execSync(command).toString('utf-8');
        const jsonStartIndex = rawOutput.indexOf('{');
        const jsonString = jsonStartIndex !== -1 ? rawOutput.substring(jsonStartIndex) : '';
        const report = JSON.parse(jsonString);

        // 6. Assert that all operations reported success.
        expect(report.error, 'The PHP script should not have thrown an exception.').toBeUndefined();
        expect(report.directory_created, 'The main test directory should have been created.').toBe(true);
        expect(report.subdirectory_created, 'The sub-directory should have been created.').toBe(true);
        expect(report.initial_content_correct, 'The initial content of the log file should be correct.').toBe(true);
        expect(report.file_copied, 'The file should have been copied successfully.').toBe(true);
        expect(report.original_file_after_copy_exists, 'The original file should still exist after copying.').toBe(true);
        expect(report.file_moved, 'The file should have been moved successfully.').toBe(true);
        expect(report.original_file_after_move_exists, 'The original file should not exist after being moved.').toBe(true);
        expect(report.moved_file_content_correct, 'The content of the moved file should be correct.').toBe(true);
        expect(report.appended_content_correct, 'Content should have been appended to the main log file.').toBe(true);
        expect(report.json_written, 'The JSON data file should have been written.').toBe(true);
        expect(report.json_read_and_verified, 'The JSON data should have been read back and verified successfully.').toBe(true);
    });
});
