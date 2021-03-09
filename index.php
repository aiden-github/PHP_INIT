<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

try{
  $connect = new PDO('mysql:host='.DB_HOST.';charset=utf8', DB_USER, DB_PASSWORD);
  $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $stmt = $connect->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".DB_DATABASE_NAME."'");
  $db_exist = (bool) $stmt->fetchColumn();
}catch(PDOException $ex){
  die('데이터베이스에 연결할 수 없습니다.');
}

if(!$db_exist){
  include ROOT.'/setting/dbinit.php';
}

include ROOT.'/page/home/index.php';