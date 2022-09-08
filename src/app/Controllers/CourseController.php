<?php

namespace App\Controllers;

use PDO;
use PDOException;

class CourseController extends Controller
{
  public function index($request, $response)
  {
    $sql = "
      SELECT 
        c.id,
        c.name,
        COUNT(s.id) AS students
      FROM
        course
      LEFT JOIN
        student
      ON
        c.id = s.course_id
    ";

    $data = $this->c->db->query($sql)->fetchAll(PDO::FETCH_OBJ);

    return $response->withJSON([
      'data' => $data,
      'success' => $data ? true : false
    ]);
  }

  public function single($request, $response, $args)
  {
    $sql = "
      SELECT 
        c.id,
        c.name,
        COUNT(s.id) AS students
      FROM
        course
      LEFT JOIN
        student
      ON
        c.id = s.course_id
      WHERE id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $args['id']
    ]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $response->withJSON([
      'data' => $data,
      'status' => $data ? true : false
    ]);
  }

  public function create($request, $response, $args)
  {
    $sql = "
      INSERT INTO 
        course (
          id,
          name
        ) 
      VALUES (
        :id,
        :name
      )
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $request->getParam('id'),
      ':name' => $request->getParam('name')
    ]);

    return $response->withJSON([
      'success' => true,
      'status' => 200
    ]);
  }

  public function delete($request, $response, $args)
  {
    $sql = "
      DELETE FROM
        course
      WHERE
        id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $request->getParams('id')
    ]);

    return $response->withJSON([
      'success' => true,
      'status' => 200
    ]);
  }

  public function update($request, $response, $args)
  {
    $sql = "
      UPDATE
        course
      SET
        name = :name
      WHERE
        id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $request->getParam('id'),
      ':name' => $request->getParam('name')
    ]);

    return $response->withJSON([
      'success' => true,
      'status' => 200
    ]);
  }
}
