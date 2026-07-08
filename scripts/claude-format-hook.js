// PostToolUse formatter for Claude Code edits.
//
// Claude Code passes the edited file's path on stdin as JSON
// (`tool_input.file_path`); there is no CLAUDE_FILE_PATH env var. We read that
// path and run the same auto-fixers lint-staged uses, so a file Claude touches
// is formatted exactly as a commit would format it. Auto-formatting must never
// block a tool call, so every branch exits 0.

import { spawnSync } from 'node:child_process';
import { existsSync, readFileSync } from 'node:fs';
import { extname } from 'node:path';

let input;
try {
  input = JSON.parse(readFileSync(0, 'utf8') || '{}');
} catch {
  process.exit(0);
}

const filePath = input?.tool_input?.file_path ?? '';
if (filePath === '' || !existsSync(filePath)) process.exit(0);

const projectDir = process.env.CLAUDE_PROJECT_DIR || input.cwd || process.cwd();
const isWin = process.platform === 'win32';

// spawnSync({ shell: true }) runs via cmd.exe on Windows, /bin/sh elsewhere.
// Quote the path for that shell: cmd treats backslashes literally inside double
// quotes (paths can't contain `"`); POSIX shells need single-quote escaping.
const quote = (p) => (isWin ? `"${p}"` : `'${p.replace(/'/g, `'\\''`)}'`);
const run = (cmdline) => spawnSync(cmdline, { cwd: projectDir, shell: true, stdio: 'ignore' });

const file = quote(filePath);

switch (extname(filePath).toLowerCase()) {
  case '.css':
    run(`npx stylelint --fix ${file}`);
    run(`npx prettier --write ${file}`);
    break;
  case '.js':
    run(`npx eslint --fix ${file}`);
    run(`npx prettier --write ${file}`);
    break;
  case '.php':
    // phpcbf-staged.js maps phpcbf's "fixed" exit 1 to success; remaining
    // violations surface at commit via the husky phpcs step.
    run(`node scripts/phpcbf-staged.js ${file}`);
    break;
  case '.md':
    // markdownlint rules + prettier formatting, matching lint-staged.
    run(`npx markdownlint-cli2 --fix ${file}`);
    run(`npx prettier --write ${file}`);
    break;
  case '.json':
  case '.yml':
  case '.yaml':
  case '.html':
    run(`npx prettier --write ${file}`);
    break;
  default:
    break;
}

process.exit(0);
