<?php
/* 
@copyright (C) Normit, Michiel Keijts 2022
 */

define('TMP',__DIR__ . '/../../tmp/');

if ($_SERVER['HTTP_X_SHARK_TOKEN'] !== 'N9TT-9G0A-B7FQ-RANC') {
    exit();
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$file_name = TMP.$name;
if (!file_exists($file_name)) {
    exit();
}


// open the file in a binary mode
$fp = fopen($file_name, 'r');
header("Content-Type: text/csv");
header("Content-Length: " . filesize($file_name));
fpassthru($fp);
exit();