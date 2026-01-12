<?php
protected $routeMiddleware = [
    'can:admin' => \App\Http\Middleware\EnsureAdmin::class,
];
protected $routeMiddleware = [

    'is_trainer' => \App\Http\Middleware\TrainerMiddleware::class,
];
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\VerifyCsrfToken::class,
    ]];