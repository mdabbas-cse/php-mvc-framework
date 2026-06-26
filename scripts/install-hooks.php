<?php

/**
 * Install git hooks for the project.
 * Run once after cloning: php scripts/install-hooks.php
 */

// Skip in CI environments (GitHub Actions, etc.)
$ciEnvVars = ['CI', 'GITHUB_ACTIONS', 'GITLAB_CI', 'CIRCLECI', 'TRAVIS', 'BITBUCKET_BUILD_NUMBER'];
foreach ($ciEnvVars as $var) {
    if (getenv($var) !== false) {
        echo "[hooks] CI environment detected — skipping hook installation.\n";
        exit(0);
    }
}

$rootDir  = dirname(__DIR__);
$gitDir   = $rootDir . DIRECTORY_SEPARATOR . '.git';
$hooksDir = $gitDir . DIRECTORY_SEPARATOR . 'hooks';
$source   = $rootDir . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'pre-push';
$target   = $hooksDir . DIRECTORY_SEPARATOR . 'pre-push';

// Must be a git repo
if (!is_dir($gitDir)) {
    echo "[hooks] No .git directory found — skipping hook installation.\n";
    exit(0);
}

// Source hook must exist
if (!file_exists($source)) {
    echo "[hooks] Source hook not found: scripts/pre-push\n";
    exit(1);
}

// Ensure hooks directory exists
if (!is_dir($hooksDir) && !mkdir($hooksDir, 0755, true)) {
    echo "[hooks] Failed to create hooks directory: {$hooksDir}\n";
    exit(1);
}

// Warn if a non-managed hook already exists
if (file_exists($target)) {
    $existing = file_get_contents($target);
    if (strpos($existing, 'LaraCore') === false) {
        echo "[hooks] WARNING: existing pre-push hook does not appear to be managed by this project.\n";
        echo "[hooks] Overwriting {$target}\n";
    }
}

// Copy and make executable
if (!copy($source, $target)) {
    echo "[hooks] Failed to copy hook to {$target}\n";
    exit(1);
}

if (!chmod($target, 0755)) {
    echo "[hooks] Failed to make hook executable: {$target}\n";
    exit(1);
}

echo "[hooks] pre-push hook installed successfully → {$target}\n";
