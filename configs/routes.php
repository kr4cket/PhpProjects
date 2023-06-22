<?php 
    return [
        '/goods/([-_a-z0-9]+)' => [\App\Controllers\GoodsController::class, 'show'],
        '/goods' => [\App\Controllers\GoodsController::class, 'add'],
        '/' => [\App\Controllers\CatalogsController::class, 'index'],
        '/([-_a-z0-9]+)' => [\App\Controllers\CatalogsController::class, 'index'],
        '/([-_a-z0-9]+)/([-_a-zA-Z0-9]+)' => [\App\Controllers\CatalogsController::class, 'index'],
        '/review/([-_a-z0-9]+)' => [\App\Controllers\ReviewsController::class, 'add']
    ];
?>