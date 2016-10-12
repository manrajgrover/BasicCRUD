<?php
  function isValidName($name) {
    return preg_match("^[a-zA-Z ]+$", $name) && strlen($name) < 50;
  }

  function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) < 50;
  }

  function isValidContact($contact) {
    return preg_match("^(0|\+91)?[789]\d{9}$", $contact) && strlen($contact) < 15;
  }

?>
