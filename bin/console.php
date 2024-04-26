<?php

use sbolch\WordCounter\Counter;

require dirname(__DIR__).'/vendor/autoload.php';

foreach (glob(dirname(__DIR__).'/tests/files/*') as $file) {
    try {
        $counter = new Counter($file);
        echo pathinfo($file, PATHINFO_EXTENSION)."\twords: {$counter->words()}\tchars: {$counter->characters()}\n";
    } catch (Exception) {
        echo pathinfo($file, PATHINFO_EXTENSION)."\tERROR\n";
    }
}
