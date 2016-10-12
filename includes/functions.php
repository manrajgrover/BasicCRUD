<?php
  function isValidName($name) {
    return preg_match("/^[a-zA-Z ]+$/", $name) && strlen($name) < 50;
  }

  function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) < 50;
  }

  function isValidContact($contact) {
    return preg_match("/^(0|\+91)?[789]\d{9}$/", $contact) && strlen($contact) < 15;
  }

  function isValid($data) {
    $arrResponse = array('error' => false, 'message' => array());
    if (!isValidName($data['name'])) {
      $arrResponse['error'] = true;
      array_push($arrResponse['message'], "Invalid Name");
    }

    if (!isValidEmail($data['email'])) {
      $arrResponse['error'] = true;
      array_push($arrResponse['message'], "Invalid Email");
    }

    if (!isValidContact($data['contact'])) {
      $arrResponse['error'] = true;
      array_push($arrResponse['message'], "Invalid Contact");
    }

    return $arrResponse;
  }

?>
