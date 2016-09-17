<?php

function recursive_remove_directory($directory, $empty = FALSE)
{
    if (substr($directory, - 1) == '/') {
        $directory = substr($directory, 0, - 1);
    }
    if (! file_exists($directory) || ! is_dir($directory)) {
        return FALSE;
    } elseif (is_readable($directory)) {
        $handle = opendir($directory);
        while (FALSE !== ($item = readdir($handle))) {
            if ( $item == '.gitignore') {
                continue;
            }
            if ($item != '.' && $item != '..') {
                $path = $directory . '/' . $item;
                if (is_dir($path)) {
                    recursive_remove_directory($path);
                } else {

                    unlink($path);
                }
            }
        }
        closedir($handle);
        if ($empty == FALSE) {
            if (! rmdir($directory)) {
                return FALSE;
            }
        }
    }
    echo '<div style="text-align:center;">

            TEMP je smazan.


   </div>';
}

try {
    $directory = dirname(__DIR__) . '/temp/';

    recursive_remove_directory($directory, true);
} catch (Exception $e) {}