import js from '@eslint/js';
import prettier from 'eslint-config-prettier';
import globals from 'globals';

const sharedRules = {
  ...js.configs.recommended.rules,
  'no-unused-vars': ['warn', { args: 'none' }],
};

export default [
  {
    ignores: ['vendor/**', 'node_modules/**', '**/*.min.js'],
  },
  {
    // Theme front-end JS (none yet, but ready for it).
    files: ['trueview-watchtower/**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'script',
      globals: {
        ...globals.browser,
        jQuery: 'readonly',
        $: 'readonly',
        wp: 'readonly',
      },
    },
    rules: sharedRules,
  },
  {
    // Node tooling scripts.
    files: ['scripts/**/*.js', '*.config.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.nodeBuiltin,
      },
    },
    rules: sharedRules,
  },
  prettier,
];
