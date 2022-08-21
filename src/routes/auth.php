<?php

use App\Controllers\AuthController;

$app->post('/login', AuthController::class . ':login');
$app->post('/register', AuthController::class . ':register');
