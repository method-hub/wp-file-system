const { defineConfig } = require('@playwright/test');
const { devices } = require('@playwright/test');

module.exports = defineConfig({
    testDir: './tests/E2E',
    timeout: 30 * 1000,
    expect: {
        timeout: 5000
    },
    fullyParallel: true,
    reporter: 'html',
    use: {
        baseURL: 'http://localhost:8888',
        trace: 'on-first-retry',
    },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],
});
