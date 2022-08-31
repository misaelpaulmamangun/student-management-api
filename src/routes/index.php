<?php

require __DIR__ . '/auth.php';
$app->group('/api', function () use ($app) {
  require __DIR__ . '/student.php';
});
