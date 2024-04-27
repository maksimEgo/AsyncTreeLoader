<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Model\Processor\ParallelTaskProcessor;
use App\Model\Tree\TreeCreate;
use Swoole\Coroutine as co;

co\run(function () {
    $treeCreate = new TreeCreate();
    $processor = new ParallelTaskProcessor(2);
    $trees = [
        $treeCreate->createBinaryTree(8),
        $treeCreate->createBinaryTree(5)
    ];

    foreach ($processor->process($trees, function($tree) use ($treeCreate) {
        $treeCreate->insertTreeAsync($tree);
    }) as $result) {
        echo $result . PHP_EOL;
    }
});