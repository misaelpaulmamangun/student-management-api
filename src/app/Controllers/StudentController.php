<?php

namespace App\Controllers;

use PDO;
use PDOException;

class StudentController extends Controller
{
  public function index($request, $response)
  {
    $sql = "
      SELECT 
        s.first_name,
        s.last_name,
        s.age,
        y.title as year,
        c.name as course
      FROM
        student s
      LEFT JOIN course c
        ON c.id = s.course_id
      LEFT JOIN year y
        ON s.year_id = y.id
    ";

    $data = $this->c->db->query($sql)->fetchAll(PDO::FETCH_OBJ);

    return $response->withJSON([
      'data' => $data,
      'success' => $data ? true : false,
    ]);
  }

  public function single($request, $response, $args)
  {
    $sql = "
      SELECT 
        s.first_name,
        s.last_name,
        s.age,
        y.title as year,
        c.name as course
      FROM
        student s
      LEFT JOIN course c
        ON c.id = s.course_id
      LEFT JOIN year y
        ON s.year_id = y.id
      WHERE s.id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $args['id']
    ]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);

    return $response->withJSON([
      'data' => $data,
      'success' => $data ? true : false
    ]);
  }

  public function create($request, $response)
  {
    try {
      $sql = "
        INSERT INTO student (
          first_name,
          last_name,
          age,
          year_id,
          course_id,
          created_at
        ) VALUES (
          :first_name,
          :last_name,
          :age,
          :year_id,
          :course_id,
          :created_at
        )
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':first_name' => $request->getParam('first_name'),
        ':last_name' => $request->getParam('last_name'),
        ':age' => $request->getParam('age'),
        ':year_id' => $request->getParam('year_id'),
        ':course_id' => $request->getParam('course_id'),
        ':created_at' => date('Y-m-d H:i:s'),
      ]);

      $data = [
        'success' => true,
        'status' => 200
      ];

      return $response->withJSON($data);
    } catch (PDOException $e) {
      $data = [
        'message' => $e->getMessage(),
        'success' => false,
        'status' => 500
      ];

      return $response->withJSON($data);
    }
  }

  public function delete($request, $response)
  {
    $sql = "
      DELETE FROM
        student
      WHERE
        id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $request->getParam('id')
    ]);

    $data = [
      'status' => true,
      'status' => 200
    ];

    return $response->withJSON($data);
  }

  public function update($request, $response)
  {
    $sql = "
      UPDATE 
        student
      SET (
        first_name = :first_name,
        last_name = :last_name,
        age = :age,
        year_id = :year_id,
        course_id = :course_id
      )
      WHERE
        id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      ':id' => $request->getParam('id'),
      ':first_name' => $request->getParam('first_name'),
      ':last_name' => $request->getParam('last_name'),
      ':age' => $request->getParam('age'),
      ':year_id' => $request->getParam('year_id'),
      ':course_id' => $request->getParam('course_id')
    ]);

    $data = [
      'success' => true,
      'status' => 200
    ];

    return $response->withJSON($data);
  }
}
