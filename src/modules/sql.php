<?php

function db_sql($queries, $select_query = []){
  try{
    $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $ex){
    throw $ex;
  }

  $result = [];
  try{
    $connect->beginTransaction();
    foreach($queries as $query => $values){
      $stmt = $connect->prepare($query);
      $stmt->execute($values);
    }
    $connect->commit();
    if(!empty($select_query)){
      foreach($select_query as $query => $values){
        $stmt = $connect->prepare($query);
        $stmt->execute($values);
        foreach($stmt as $field){
          $result[] = $field;
        }
      }
    }
    return $result;

  }catch(PDOException $ex){
    $connect->rollBack();
    throw $ex;
  }
}

function db_insert($table, $data, $names){
  $fields = '';
  $question_marks = '';
  $values = [];
  foreach($names as $field){
    $fields .= $field.',';
    $question_marks .= '?,';
    $values[] = $data[$field];
  }
  $fields = substr($fields, 0, strlen($fields) - 1);
  $question_marks = substr($question_marks, 0, strlen($question_marks) - 1);
  
  $query = '
  INSERT INTO '.$table.'
  ('.$fields.')
  VALUES
  ('.$question_marks.');
  ';

  try{
    db_sql([
      $query => $values
    ]);
  }catch(PDOException $ex){
    throw $ex;
  }
}