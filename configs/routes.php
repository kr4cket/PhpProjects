<?php 
    return [
        '/goods/show' => [\App\Controllers\GoodsController::class, 'show'],
        '/goods' => [\App\Controllers\GoodsController::class, 'add'],
        '/' => [\App\Controllers\CatalogsController::class, 'index'],
        '/review' => [\App\Controllers\ReviewsController::class, 'add']
    ];
?>