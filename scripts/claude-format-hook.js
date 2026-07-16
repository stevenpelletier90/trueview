// PostToolUse formatter for Claude Code edits.
//
// Files Claude edits land on disk without passing through an editor, so
// format-on-save never sees them and they drift out of style. This hook closes
// that gap: it auto-fixes whatever Claude just touched, using the same fixers
// `npm run validate` checks with.
//
// Claude Code passes the edited file's path on stdin as JSON
// (`tool_input.file_path`) — there is no CLAUDE_FILE_PATH env var; an earlier
// inline version relied on one and silently no-op'd.
//
// Formatting must never block a tool call, so every branch exits 0 and all
// fixer output is discarded. Real violations still surface at `npm run
// validate`, which is the actual gate.

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

// --no-install is load-bearing: bare `npx <tool>` silently DOWNLOADS a missing
// tool from the registry rather than failing, which would put a network fetch
// in the edit path. --no-install keeps this to the project's own pinned bins.
const npx = (tool, args) => run(`npx --no-install ${tool} ${args}`);

const file = quote(filePath);

switch (extname(filePath).toLowerCase()) {
  case '.css':
    npx('stylelint', `--fix ${file}`);
    npx('prettier', `--write ${file}`);
    break;
  case '.js':
    npx('eslint', `--fix ${file}`);
    npx('prettier', `--write ${file}`);
    break;
  case '.php':
    // phpcbf exits 1 when it successfully fixes something; exit codes are
    // ignored here, so it is called directly rather than through a wrapper.
    run(`php -d memory_limit=-1 vendor/bin/phpcbf ${file}`);
    break;
  case '.md':
    npx('markdownlint-cli2', `--fix ${file}`);
    npx('prettier', `--write ${file}`);
    break;
  case '.json':
  case '.yml':
  case '.yaml':
  case '.html':
    npx('prettier', `--write ${file}`);
    break;
  default:
    break;
}

process.exit(0);
