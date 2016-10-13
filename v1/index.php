<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  /**
   * Load dependencies and includes
   */
  require_once '../vendor/autoload.php';
  require_once '../includes/DbAccess.php';
  require_once '../includes/functions.php';

  /**
   * Slim App instance
   * @var Object
   */
  $app = new \Slim\App();

  /**
   * Matches route to /employee/new and creates a new employee
   * @return JSON     Whether employee was created or not
   */
  $app->post('/employee/new', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'employees');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get all query parameters
       */
      $allParams = $request->getQueryParams();

      /**
       * Validate fields
       * @var Array
       */
      $validate = isValid($allParams);

      if($validate['error'] === false) {

        /**
         * Parameter Array for Query
         * @var Array
         */
        $params = array(
          'name' => $allParams['name'],
          'email' => $allParams['email'],
          'contact' => $allParams['contact'],
          'designation' => $allParams['designation']
        );

        /**
         * Prepare query for inserting an employee and execute it
         */
        $prepare_query = "INSERT INTO `employee`(`name`, `email`, `contact`, `designation`) VALUES (:name, :email, :contact, :designation)";
        $query = $db_connect->prepare($prepare_query);
        $query->execute($params);

        /**
         * Get last inserted id
         * @var Integer
         */
        $lastInsertId = $db_connect->lastInsertId();

        if ($lastInsertId) {
          /**
           * Response if no error occured
           * @var array
           */
          $arrResponse = array('error' => false, 'insert_id' => $lastInsertId);
        }
        else {
          /**
           * Response if error occured
           * @var array
           */
          $arrResponse = array('error' => true, 'message' => 'An error occured');
        }

        /**
         * Send JSON as response
         */
        return $response->withJson($arrResponse);

      }
      else {

        /**
         * Response when validations failed
         */
        return $response->withJson($validate);
      }
    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }

  });

  /**
   * Matches route to /employee/:id and gets employee details
   * @return JSON     Employee details
   */
  $app->get('/employee/{id}', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'employees');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get id from the URL
       * @var Integer
       */
      $emp_id = ((int) $request->getAttribute('id'));

      /**
       * Prepare query for getting employee details and execute
       * @var String
       */
      $prepare_query = "SELECT * FROM `employee` WHERE `id` = :id";
      $query = $db_connect->prepare($prepare_query);
      $query->execute(array('id' => $emp_id));

      /**
       * Fetch details from result as Array of rows
       * @var Array
       */
      $results = $query->fetchAll();

      if (count($results) > 0) {
        /**
         * If employee found, return response
         */
        return $response->withJson($results[0]);
      }
      else {
        /**
         * Response for no employee exist
         */
        return $response->withJson(array('error'=> true, 'message' => 'No employee exist with this id'));
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }
  });

  /**
   * Matches route to /employee/:id and updates the employee details
   * @return JSON     Whether employee was updated or not
   */
  $app->put('/employee/{id}', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'employees');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get all query parameters
       * @var Array
       */
      $allParams = $request->getQueryParams();

      /**
       * Get employee id from URL
       * @var Integer
       */
      $emp_id = ((int) $request->getAttribute('id'));

      /**
       * Validate all parameters
       * @var Array
       */
      $validate = isValid($allParams);


      if($validate['error'] === false) {

        /**
         * Parameter Array for Query
         * @var Array
         */
        $params = array(
          'id' => $emp_id,
          'name' => $allParams['name'],
          'email' => $allParams['email'],
          'contact' => $allParams['contact'],
          'designation' => $allParams['designation']
        );

        /**
         * Prepare query for updating the employee details
         * @var String
         */
        $prepare_query = "UPDATE `employee` SET `name`=:name, `email`=:email, `contact`=:contact, `designation`=:designation WHERE `id`=:id";
        $query = $db_connect->prepare($prepare_query);

        /**
         * Execute query
         */
        if ($query->execute($params)) {
          /**
           * If employee details updated, return 'Rows updated'
           */
          $arrResponse = array('error' => false, 'message' => 'Row updated');
        }
        else {
          /**
           * If employee details are not updated, return 'An error occured'
           */
          $arrResponse = array('error' => true, 'message' => 'An error occured');
        }

        /**
         * Send JSON as response
         */
        return $response->withJson($arrResponse);

      }
      else {
        /**
         * Response when validations failed
         */
        return $response->withJson($validate);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }
  });

  /**
   * Matches route to /employee/:id and delete employee
   * @return JSON     Whether employee was deleted or not
   */
  $app->delete('/employee/{id}', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'employees');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get employee id from URL
       * @var Integer
       */
      $emp_id = ((int) $request->getAttribute('id'));

      /**
       * Prepare query for deleting employee
       * @var String
       */
      $prepare_query = "DELETE FROM `employee` WHERE `id` = :id";
      $query = $db_connect->prepare($prepare_query);
      $query->execute(array('id' => $emp_id));

      /**
       * Check if any row was effected
       */
      if ($query->rowCount() > 0) {

        /**
         * If yes, then employee deleted
         */
        return $response->withJson(array('error' => false, 'message' => 'Employee deleted!'));
      }
      else {
        /**
         * If no, then employee exist
         */
        return $response->withJson(array('error' => true, 'message' => 'No employee exist with this id'));
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }
  });

  $app->run();
?>
