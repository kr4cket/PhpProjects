<?php 
    return [
        '/goods/([-_a-z0-9]+)' => [\App\Controllers\GoodsController::class, 'show'],
        '/goods' => [\App\Controllers\GoodsController::class, 'add'],
        '/' => [\App\Controllers\CatalogsController::class, 'index'],
        '/review' => [\App\Controllers\ReviewsController::class, 'add']
    ];
?>