<?php

namespace App\Controllers;

use PDO;
use PDOException;

class StudentController extends Controller
{
  public function index($req, $res)
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

    return $res->withJSON([
      'data' => $data,
      'success' => $data ? true : false,
    ]);
  }

  public function single($req, $res, $args)
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

    return $res->withJSON([
      'data' => $data,
      'success' => $data ? true : false
    ]);
  }

  public function create($req, $res)
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
        ':first_name' => $req->getParam('first_name'),
        ':last_name' => $req->getParam('last_name'),
        ':age' => $req->getParam('age'),
        ':year_id' => $req->getParam('year_id'),
        ':course_id' => $req->getParam('course_id'),
        ':created_at' => date('Y-m-d H:i:s'),
      ]);

      $data = [
        'success' => true,
        'status' => 200
      ];

      return $res->withJSON($data);
    } catch (PDOException $e) {
      $data = [
        'message' => $e->getMessage(),
        'success' => false,
        'status' => 500
      ];

      return $res->withJSON($data);
    }
  }

  public function delete($req, $res, $args)
  {
    $sql = "
      DELETE FROM
        student
      WHERE
        id = :id
    ";

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      'id' => $req->getParam('id')
    ]);

    $data = [
      'status' => true,
      'status' => 200
    ];

    return $res->withJSON($data);
  }
}
