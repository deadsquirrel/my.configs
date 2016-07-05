<?php
define('SOURCE_VERSION', 'build3.3');

define('SOURCE_DIR', realpath(__DIR__ . '/../' . SOURCE_VERSION));
if (!is_dir(SOURCE_DIR)) {
    die('Source dir is incorrect');
}

function symlink_log($source, $dst)
{
    echo 'Symlink ' . $source . ' to ' . $dst . ': ';flush();
    $result = symlink($source, $dst);
    echo $result ? 'OK<br>' : 'FAILED<br>';flush();
}

function unlink_log($source)
{
    echo 'Symlink ' . $source . ' removing: ';flush();
	if (is_link($source) || is_file($source)) {
		$result = unlink($source);
	} else {
		$result = rmdir_full($source);
	}
    echo $result ? 'OK<br>' : 'FAILED<br>';flush();
}

function rmdir_full($src)
{
    if (is_dir($src)) {
        $dir = opendir($src);

        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . '/' . $file)) {
                    rmdir_full($src . '/' . $file);
                } else {
                    unlink($src . '/' . $file);
                }
            }
        }
        closedir($dir);

        return rmdir($src);
    } elseif (is_link($src)) {
        return unlink($src);
    }
    return false;
}

function mkdir_log($path)
{
    echo 'Directory ' . $path . ' creating: ';flush();
    $result = mkdir($path);
    echo $result ? 'OK<br>' : 'FAILED<br>';flush();
}

function move_log($source, $dst)
{
    echo 'Moving ' . $source . ' to ' . $dst . ': ';flush();
    $result = rename($source, $dst);
    echo $result ? 'OK<br>' : 'FAILED<br>';flush();
}

function create_empty($path)
{
    echo 'Creating ' . $path . ': ';flush();
    if (!file_exists($path)) {
        $result = file_put_contents($path, '', LOCK_EX);
    } else {
        $result = false;
    }
    echo $result !== false ? 'OK<br>' : 'FAILED<br>';flush();
}

//if (is_link(__DIR__ . '/config/scss')) {
//    unlink_log(__DIR__ . '/config/scss');
//}
// Файлы и директории для удаления
$rm = [
    'config/scss',
    '._favicon.ico',
    'frontend/modules/logs_storage',
    'edit/temp',
    'sitemap.xml',
    'img_creator.php'
];

// Файлы и директории для перемещения
$mv = [
    'assets/original/*.css' => 'assets/original/css',
    'assets/original/*.js' => 'assets/original/js',
    'favicon.ico' => 'assets/favicon.ico',
    'frontend/modules/api/logs_storage/requests/*.php' => 'logs/api/requests',
    'frontend/modules/api/logs_storage/responses/*.php' => 'logs/api/responses',
    'frontend/modules/api/logs_storage/*.php' => 'logs/api',
];
// Список ссылаемых параметров
$symLinks = [
    'edit',
    'frontend',
    'out',
    'config/styles/scss',
    'core'
];

// Список необходимых файлов для обновления
$files = [
    'index.php',
    'check_permissions.php',
    '.htaccess',
    'robots.txt',
    'sitemap.php',
    //'img_creator.php',
    'staticmap.php',
    'config/main.php',
    'config/env.php',
    'logs/np/index.php',
    'logs/api/.htaccess',
];

// Список новых директорий
$dirs = [
    'config/styles',
    'config/styles/custom',
    'assets',
    'assets/original',
    'assets/cache',
    'project_override',
    'assets/original/css',
    'assets/original/js',
    'content/export',
    'logs/np',
    'logs/api',
    'logs/api/requests',
    'logs/api/responses',
    'content/temp'
];

$new_files = [
    'project_override/autoload.php'
];


foreach ($symLinks as $link) {
    unlink_log(__DIR__ . '/' . $link);
}

foreach ($dirs as $dir) {
    if (!is_dir(__DIR__ . '/' . $dir)) {
        mkdir_log(__DIR__ . '/' . $dir);
    }
}

foreach ($symLinks as $link) {
    symlink_log(SOURCE_DIR . '/' . $link, __DIR__ . '/' . $link);
}

foreach ($files as $file) {
    unlink_log(__DIR__ . '/' . $file);
    copy(SOURCE_DIR . '/' . $file, __DIR__ . '/' . $file);
}

foreach ($new_files as $file) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        create_empty(__DIR__ . '/' . $file);
    }
}

foreach ($mv as $from => $to) {
    $list = glob(__DIR__ . '/' . $from);

    foreach ($list as $source) {
        $filename = array_pop(explode(DIRECTORY_SEPARATOR, $source));
        $dst = __DIR__ . '/' . $to . '/' . $filename;
        move_log($source, $dst);
    }
}

foreach ($rm as $path) {
    $list = glob(__DIR__ . '/' . $path);

    if (is_link(__DIR__ . '/' . $path) || file_exists(__DIR__ . '/' . $path)) {
        unlink_log(__DIR__ . '/' . $path);
    }
}
require_once (__DIR__ . '/config.php');
installParam('product_set');

require_once(__DIR__ . '/db_updater_to_3.3.php');
require_once(__DIR__ . '/update_json.php');