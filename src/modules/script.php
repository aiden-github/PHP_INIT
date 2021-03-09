<?php

function redirect($url){
  echo '
    <script>
      window.location.href = "'.$url.'";
    </script>
  ';
}

function alert($msg){
  echo '
    <script>
      alert("'.$msg.'");
    </script>
  ';
}