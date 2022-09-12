<?php

use App\Controllers\StudentController;
use App\Controllers\CourseController;
use App\Controllers\AuthController;

$app->post('/login', AuthController::class . ':login');
$app->post('/register', AuthController::class . ':register');

$app->group('/api', function () use ($app) {
  // Students Route
  $student = '/student';
  $app->get($student, StudentController::class . ':index');
  $app->get($student . '/{id}', StudentController::class . ':single');
  $app->post($student, StudentController::class . ':create');
  $app->delete($student, StudentController::class . ':delete');
  $app->put($student, StudentController::class . ':update');

  // Course Route
  $course = '/course';
  $app->get($course, CourseController::class . ':index');
  $app->get($course . '/{id}', CourseController::class . ':single');
  $app->post($course, CourseController::class . ':create');
  $app->delete($course, CourseController::class . ':delete');
  $app->put($course, CourseController::class . ':update');
});
