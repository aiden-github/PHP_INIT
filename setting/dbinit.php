<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

$table_dir = ROOT.'/table';

function create_table($table, $cols){
  return "
  CREATE TABLE IF NOT EXISTS ".$table."
  (
    ".$cols."
    )DEFAULT character set utf8 collate utf8_general_ci;
    ";
  }
  
  function get_table_cols($table, $table_dir){
  $myfile = fopen($table_dir.'/'.$table.'.txt', 'r') or die('Unable to open file!');
  $cols =  fread($myfile, filesize(ROOT.'/table/'.$table.'.txt'));  
  fclose($myfile);
  return $cols;
}

try {
  $connect = new PDO('mysql:host='.DB_HOST.';charset=utf8', DB_USER, DB_PASSWORD);
  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "CREATE DATABASE IF NOT EXISTS ".DB_DATABASE_NAME.";";
  $sql .= "USE ".DB_DATABASE_NAME.";";

  if(file_exists($table_dir)){
    foreach(scandir($table_dir) as $file){
      if(!is_dir($table_dir.'/'.$file)){
        $table = explode('.', $file)[0];
        $sql .= create_table($table, get_table_cols($table, $table_dir));
      }
    }
  }

  $connect->beginTransaction();
  $connect->exec($sql);
  $connect->commit();
  echo '
    <script>
      alert("데이터베이스 세팅이 완료되었습니다.");
    </script>
  ';
}
catch (PDOException $e) {
  $connect->rollBack();
  die('데이터베이스 세팅 에러: '. $e->getMessage());
}