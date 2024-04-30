<?php

use sbolch\WordCounter\Counter;

require __DIR__.'/vendor/autoload.php';

foreach (glob(__DIR__.'/tests/files/*') as $file) {
    try {
        $counter = Counter::get($file);
        echo pathinfo($file, PATHINFO_EXTENSION)." /w shell\twords: {$counter->words()}\tchars: {$counter->characters()}\n";

        $counter = Counter::get($file, false);
        echo pathinfo($file, PATHINFO_EXTENSION)."\t\twords: {$counter->words()}\tchars: {$counter->characters()}\n";
    } catch (Exception $e) {
        echo pathinfo($file, PATHINFO_EXTENSION)."\t\tERROR\n";

        dump($e->getMessage());
    }

    echo PHP_EOL;
}
