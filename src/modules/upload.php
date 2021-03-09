<?php

// 파일 업로드 모듈(1개의 파일)
function upload($file, $folder = ''){
  $err_msg = null;
  $path = '';
  $url = '';

  try{
    if(!empty($file['error'])){
      throw new Exception('파일 업로드중 에러가 발생했습니다.');
    }

    if($file['size'] > 50000000){
      throw new Exception('파일의 용량이 너무 큽니다. (50MB 초과)');
    }

    $target_dir = empty($folder) ? ROOT.'/uploads/' : ROOT.'/'.$folder.'/';
    $data_dir = empty($folder) ? '/uploads/' : '/'.$folder.'/';

    // 폴더 존재 유무 검사(폴더 생성)
    if(!file_exists($target_dir)){
      mkdir($target_dir, 0700);
    }

    $filename = $file['name'];
    $target_file = $target_dir . basename($filename);
    $data_path = $data_dir . basename($filename);
    
    // 파일 이름 중복 방지
    $count = 0;
    $target_path = $target_file;
    $file_ext = substr(strrchr($filename, '.'), 1);
    $file_name = substr($filename, 0, strlen($filename) - strlen($file_ext) - 1);
    
    while(file_exists($target_path)){
      $target_path = $target_dir . basename($file_name.'(' . ++$count . ').' . $file_ext);
      $data_path = $data_dir . basename($file_name.'(' . $count . ').' . $file_ext);
    }

    // 파일 업로드
    if(move_uploaded_file($file["tmp_name"], $target_path)) {
      $path .= $target_path;
      $url .= $data_path;
    }else{
      throw new Exception('파일 저장 중에 에러가 발생했습니다.');
    }
  }catch(Exception $e){
    $err_msg = $e->getMessage();
  }

  return array(
    'path' => $path, 
    'url' => $url, 
    'err_msg' => $err_msg
  );
}

// 여러개 파일 업로드 모듈
function upload_files($file, $folder = ''){
  $err_msg = '';
  $paths = [];
  $urls = [];

  try{
    foreach($file['error'] as $index => $err){
      if($err != 0){
        throw new Exception((string)$index.'번째 파일 업로드중 에러가 발생했습니다.');
      }
    }

    foreach($file['size'] as $index => $size){
      if($size > 10000000){
        throw new Exception((string)$index.'번째 파일의 용량이 너무 큽니다. (10MB 초과)');
      }
    }

    $upload_dir = ROOT.'/uploads/';
    $target_dir = empty($folder) ? $upload_dir : $upload_dir.$folder.'/';
    $data_dir = empty($folder) ? '/uploads/' : '/uploads/'.$folder.'/';

    // 폴더 존재 유무 검사
    if(!file_exists($upload_dir)){
      mkdir($upload_dir, 0700);
    }
    if(!file_exists($target_dir)){
      mkdir($target_dir, 0700);
    }


    foreach($file['name'] as $index => $filename){
      $target_file = $target_dir . basename($filename);
      $data_path = $data_dir . basename($filename);
      
      // 파일 이름 중복 방지
      $count = 0;
      $target_path = $target_file;
      $file_ext = substr(strrchr($filename, '.'), 1);
      $file_name = substr($filename, 0, strlen($filename) - strlen($file_ext) - 1);
      
      while(file_exists($target_path)){
        $target_path = $target_dir . basename($file_name.'(' . ++$count . ').' . $file_ext);
        $data_path = $data_dir . basename($file_name.'(' . $count . ').' . $file_ext);
      }

      // 파일 업로드
      if(move_uploaded_file($file["tmp_name"][$index], $target_path)) {
        $paths[] = $target_path;
        $urls[] = $data_path;
      }else{
        throw new Exception((string)$index.'번째 파일 저장 중에 에러가 발생했습니다.');
      }
    }
  }catch(Exception $e){
    $err_msg = $e;
  }

  return array(
    'paths' => $paths, 
    'urls' => $urls, 
    'err_msg' => $err_msg
  );
}