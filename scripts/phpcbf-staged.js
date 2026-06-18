// lint-staged wrapper for phpcbf. phpcbf exits 1 when it successfully fixes
// violations, which lint-staged would treat as a failed task and abort the
// commit. Map 0 (clean) and 1 (fixed) to success; 2 (unfixable violations
// remain) and 3 (processing error) stay failures, which the phpcs step that
// runs next reports in detail.

import { spawnSync } from 'child_process';

const files = process.argv.slice(2);
if (files.length === 0) process.exit(0);

const result = spawnSync('php', ['-d', 'memory_limit=-1', 'vendor/bin/phpcbf', ...files], { stdio: 'inherit' });
const code = result.status ?? 3;
process.exit(code <= 1 ? 0 : code);
