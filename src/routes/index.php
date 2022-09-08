<?php

use App\Controllers\StudentController;
use App\Controllers\CourseController;
use App\Controllers\AuthController;

$app->post('/login', AuthController::class . ':login');
$app->post('/register', AuthController::class . ':register');

$app->group('/api', function () use ($app) {
  // Students Route
  $app->get('/students', StudentController::class . ':index');
  $app->get('/students/{id}', StudentController::class . ':single');
  $app->post('/students', StudentController::class . ':create');
  $app->delete('/students', StudentController::class . ':delete');
  $app->put('/students', StudentController::class . ':update');

  // Course Route
  $app->get('/course', CourseController::class . ':index');
  $app->get('/course/{id}', CourseController::class . ':single');
  $app->post('/course', CourseController::class . ':create');
  $app->delete('/course', CourseController::class . ':delete');
  $app->put('/course', CourseController::class . ':update');
});
