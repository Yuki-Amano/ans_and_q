<?php

spl_autoload_register ('autoload');

function autoload ($className) {
    $fileName = $className . '.php';
    if (!file_exists($fileName)) {
        foreach (['controllers', 'models', 'templates'] as $folder) {
            $fileName = $folder. '/'. $className . '.php';
            if (file_exists($fileName)) {
                include $fileName;
                return;
            }
        }
    } else {
        include $fileName;
    }
}