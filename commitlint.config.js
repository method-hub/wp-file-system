module.exports = {
    extends: ['@commitlint/config-conventional'],
    rules: {
        'type-enum': [2, 'always', ['fix', 'feat', 'docs', 'style', 'refactor', 'test', 'chore']],
        'scope-enum': [2, 'always', ['main', 'deps', 'markdown', 'ci', 'format', 'git', 'tests', 'other']],
    },
};
