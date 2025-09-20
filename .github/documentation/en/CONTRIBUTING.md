# Project Participation Guide

Thank you so much for your interest in our project! Any contribution is welcome, whether it's bug fixes, adding
new features, or improving documentation.

This document contains a set of recommendations that will help you make your changes easily and correctly.

## Getting started

1.  **Fork** the repository on GitHub.
2.  **Clone** your fork to the local machine:
    ```bash
    git clone https://github.com/method-hub/wp-file-system.git
    ```
3.  **Setup** all dependencies using Composer:
    ```bash
    composer install
    ```

After that, set up a working environment for testing.

## Configuring the test environment

The project uses a script `./bin/install-wp-tests.sh`, which installs the `wordpress-test-lib` for unit and integration
tests. You will need a running MySQL service on your computer. Install the `wordpress-test-lib` test environment
using this command:

```shell
./bin/install-wp-tests.sh wordpress_test root [password] 127.0.0.1:[port]
```

For E2E tests, the project uses Playwright and `@wordpress/env`. To run end-to-end tests, there are the following commands:

`composer.json/package.json`
```json
{
    "test:e2e": "npx playwright test",
    "test:ui": "npx playwright test --ui",
    "test:headed": "npx playwright test --headed"
}
```

- `test:e2e`: Running E2E testing;
- `test:ui`: Running E2E testing with the browser window open;
- `test:headed`: Launching E2E testing with the opportunity to observe interactive interaction with the site.

Commands for managing containers:

`package.json`
```json
{
    "wp-env": "wp-env",
    "env:start": "wp-env start",
    "env:stop": "wp-env stop"
}
```

- `wp-env`: CLI utility for working with the test environment;
- `env:start`: Launching the test environment, database, and website;
- `env:stop`: Stopping the test environment.

You can use the test database that `@wordpress/env` brings up during testing to install
the unit and integrations database of tests. To do this, first run the `env:start` command, and then run
the `wordpress-test-lib` installation script:

```shell
./bin/install-wp-tests.sh wordpress_test root password ['host']:['port']
```

Using this approach, you no longer need to deploy the database for unit tests locally.

For more information, read the official documentation:
[Playwright](https://playwright.dev),
[wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme).

## The process of making changes (Workflow)

1.  Create a new branch for your task from the current `develop` branch. Please use meaningful names,
    for example
    `feature/add-new-button` or `fix/user-login-bug`.
    ```bash
    git checkout -b feature/ваша-новая-фича
    ```
2.  Make your own changes to the code.
3.  **Be sure to check the code style.** Our project uses
    PHP_CodeSniffer to maintain a single standard.
    *   To check the code for style errors, run:
        ```bash
        composer lint
        ```
    *   To automatically fix most problems with
        before formatting, perform:
        ```bash
        composer lint-fix
        ```
    **Important:** Pull Requests with code style errors will not be accepted.
4.  Commit your changes. Write clear and informative messages to the commits.
5.  Send (push) your branch to your fork on GitHub.
6.  Create a Pull Request to the main repository of the project. In the description, describe in detail what changes you 
have made and what problem they solve.

## Coding standards

The project uses the PSR12 standard.

### The main configuration file `.phpcs.xml.dist`

All the formatting and code style rules for this project are defined in the `.phpcs.xml.dist` file. This file is
a "contract" for the entire team and ensures that all code entering the repository conforms to a single standard.
It is used by our CI/CD systems to automatically verify each Pull Request.

### Local redefinition using `.phpcs.xml`

Sometimes you may need to temporarily change the rules or use stricter checks just for yourself,
without affecting the entire team. That's what the override mechanism is for.

You can create a file in the root of the `project.phpcs.xml` (it has already been added to `.gitignore` and will not be included in the repository).
PHP_CodeSniffer will automatically detect it and use **instead of** `.phpcs.xml.dist`.

## License

By contributing to this project, you agree that your changes will be distributed under a license.
**GNU General Public License v2.0 (or later)**, as well as WordPress itself.
