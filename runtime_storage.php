<?php
declare(strict_types=1);

/**
 * 公開ディレクトリ外に置く、サイト共通の書き込み先を返す。
 *
 * 本番では YAFUSO_RUNTIME_STORAGE_DIR または git 管理外の
 * runtime.config.php で絶対パスを必ず指定する。
 */
/**
 * Validate a configured writable directory without creating it.
 *
 * The nearest existing parent is resolved so a not-yet-created path cannot
 * bypass the public document-root check.
 */
function yafuso_validate_storage_dir($configured): string
{
    $configured = trim((string) $configured);

    // 相対パスやdocument root配下への誤配置は受け付けない。
    if ($configured === '' || $configured[0] !== '/') {
        return '';
    }

    $documentRoot = trim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $documentRoot = $documentRoot !== '' ? realpath($documentRoot) : false;
    if ($documentRoot === false) {
        $documentRoot = realpath(__DIR__);
    }

    // 既存パスはディレクトリかつ書き込み可能であることを確認する。
    if ((file_exists($configured) && !is_dir($configured)) || (is_link($configured) && !is_dir($configured))) {
        return '';
    }

    // 既存パス、または最初に見つかる既存の親を解決する。
    $probe = $configured;
    while ($probe !== '' && !is_dir($probe)) {
        $parent = dirname($probe);
        if ($parent === $probe) {
            break;
        }
        $probe = $parent;
    }
    $resolvedParent = realpath($probe);
    if ($resolvedParent === false || $documentRoot === false) {
        return '';
    }

    if (!is_writable($resolvedParent)) {
        return '';
    }

    if (
        $resolvedParent === $documentRoot
        || strpos($resolvedParent, rtrim($documentRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR) === 0
    ) {
        return '';
    }

    return rtrim($configured, '/');
}

function yafuso_runtime_storage_dir(): string
{
    static $storageDir = null;
    if ($storageDir !== null) {
        return $storageDir;
    }

    $configured = trim((string) getenv('YAFUSO_RUNTIME_STORAGE_DIR'));
    $localFile = __DIR__ . '/runtime.config.php';
    if (is_file($localFile)) {
        $local = require $localFile;
        if (is_array($local)) {
            $configured = trim((string) ($local['storage_dir'] ?? $configured));
        }
    }

    $storageDir = yafuso_validate_storage_dir($configured);
    return $storageDir;
}
