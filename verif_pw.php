<?php
function verif_pw($pw) {
  if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $pw)) {
    return true;
  }
  else {
    return false;
  }
}
?>
