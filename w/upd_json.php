<?php
require_once __DIR__ . '/config.php';

function hs_pattern_replace($old_value)
{
    if ($old_value === true) {
        return 'repeat';
    } elseif ($old_value === false) {
        return 'no-repeat';
    } else {
        return $old_value;
    }
}

function hs_position_replace($old_value)
{
    if ($old_value === 'center') {
        return '50% 0';
    } elseif ($old_value === 'left') {
        return '0 0';
    } elseif ($old_value === 'right') {
        return '100% 0';
    } else {
        return $old_value;
    }
}

defined('SOURCE_VERSION') or define('SOURCE_VERSION', 'build3.3');
//defined('SOURCE_DIR') or define('SOURCE_DIR', realpath(__DIR__));
defined('SOURCE_DIR') or define('SOURCE_DIR', realpath(__DIR__ . '/../' . SOURCE_VERSION));

$old_json_path = __DIR__ . '/config/application.json';
$bak_json_path = __DIR__ . '/config/application_3.2.json';
$new_json_path = SOURCE_DIR . '/config/application.json';

$old_json = new HS(file_get_contents($old_json_path, LOCK_EX), false);

file_put_contents($bak_json_path, $old_json->toString(), LOCK_EX);

$new_json = new HS(file_get_contents($new_json_path, LOCK_EX), false);
$updated_json = new HS($new_json, false);
$old_json_updates = [
    'nomination' => [
        'homepage.blocks',
        'homepage.blocks.columns.components',
        'catalog.sorting',
        'catalog.siblings',
    ],
    'removing' => [
        'homepage.blocks.columns.components.socials.widgets'
    ],
    'moving' => [],
    'replace' => [
        'common.style.picture.pattern'         => 'hs_pattern_replace',
        'common.style.picture.position'        => 'hs_position_replace',
        'header.style.picture.pattern'         => 'hs_pattern_replace',
        'header.style.picture.position'        => 'hs_position_replace',
        'header.banner.style.picture.pattern'  => 'hs_pattern_replace',
        'header.banner.style.picture.position' => 'hs_position_replace',
    ]
];
$old_json->updateNodes($old_json_updates);

$updated_json->merge($old_json);

foreach ($old_json_updates['nomination'] as $node_path) {
    $updated_json->sortNode($node_path, $old_json);
}

if (isset($old_json_updates['replace']) && is_array($old_json_updates['replace'])) {
    $updated_json->replaceNodes($old_json_updates['replace']);
}
$updated_json->version = $new_json->version;
file_put_contents($old_json_path, $updated_json->toString(), LOCK_EX);
?>
<div style="width: 500px; float: left"><pre><?=print_r($old_json->toString(), true)?></pre></div>
<div style="width: 500px; float: left"><pre><?=print_r($new_json->toString(), true)?></pre></div>
<div style="width: 500px; float: left"><pre><?=print_r($updated_json->toString(), true)?></pre></div>