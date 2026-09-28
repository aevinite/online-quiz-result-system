<?php
/**
 * Vercel entry point. Every *.php request is rewritten here (see vercel.json),
 * then the matching page file is loaded so the project runs unchanged.
 */
require_once __DIR__ . '/../config/session.php';

$path = isset($_GET['__path']) ? $_GET['__path'] : '';
unset($_GET['__path'], $_REQUEST['__path']);

$path = trim(str_replace('\\', '/', $path), '/');
if ($path === '') {
    $path = 'index.php';
}

$root = realpath(__DIR__ . '/..');
$file = realpath($root . '/' . $path);

// Only real .php pages inside the project; never config/, includes/, api/ or setup.php.
$blocked = preg_match('#^(config|includes|api)/#', $path) || $path === 'setup.php';
if ($file === false || strpos($file, $root . DIRECTORY_SEPARATOR) !== 0
    || pathinfo($file, PATHINFO_EXTENSION) !== 'php' || $blocked) {
    http_response_code(404);
    echo 'Page not found';
    exit;
}

$_SERVER['SCRIPT_NAME'] = '/' . $path;
$_SERVER['PHP_SELF']    = '/' . $path;
chdir(dirname($file));
require $file;
