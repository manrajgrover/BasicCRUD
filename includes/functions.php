<?php

  /**
   * Checks if name is valid
   * @param  String  $name Employee Name
   * @return boolean       If name is valid
   */
  function isValidName($name) {
    return preg_match("/^[a-zA-Z ]+$/", $name) && strlen($name) < 50;
  }

  /**
   * Check if email is valid
   * @param  String  $email Employee email
   * @return boolean        If employee is valid
   */
  function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) < 50;
  }

  /**
   * Check if contact is valid
   * @param  String  $contact Employee contact
   * @return boolean          If contact is valid
   */
  function isValidContact($contact) {
    return preg_match("/^(0|\+91)?[789]\d{9}$/", $contact) && strlen($contact) < 15;
  }

  /**
   * Check if designation is valid
   * @param  String  $designation Employee designation
   * @return boolean              If designation is valid
   */
  function isValidDesignation($designation) {
    return !empty($designation) && is_string($designation);
  }

  /**
   * Check if all given fields are valid or not
   * @param  Array  $data  Employee details
   * @return Array         Any errors, if yes then their details
   */
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

    if(!isValidDesignation($data['designation'])) {
      $arrResponse['error'] = true;
      array_push($arrResponse['message'], "Invalid Designation");
    }

    return $arrResponse;
  }

?>
