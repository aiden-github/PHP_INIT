<?php

function session_start_once(){
  if(!isset($_SESSION) || session_id() == '') {
    session_start();
  }
}