<?php

use App\Controllers\StudentController;

$app->get('/students', StudentController::class . ':index');
$app->get('/students/{id}', StudentController::class . ':single');
$app->post('/students', StudentController::class . ':create');
$app->delete('/students', StudentController::class . ':delete');
