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
  $app = new \Slim\App([
      'settings' => [
          'displayErrorDetails' => true
      ]
  ]);

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
      $validate = isValid($allParams);
      if($validate['error'] === false) {

        $params = array(
          'name' => $allParams['name'],
          'email' => $allParams['email'],
          'contact' => $allParams['contact'],
          'designation' => $allParams['designation']
        );

        /**
         * Prepare query for getting details of doctors on that page
         * @var String
         */
        $prepare_query = "INSERT INTO `employee`(`name`, `email`, `contact`, `designation`) VALUES (:name, :email, :contact, :designation)";
        $query = $db_connect->prepare($prepare_query);
        $query->execute($params);

        $lastInsertId = $db_connect->lastInsertId();

        if ($lastInsertId) {
          $arrResponse = array('error' => false, 'insert_id' => $lastInsertId);
        }
        else {
          $arrResponse = array('error' => true, 'message' => 'An error occured');
        }

        /**
         * Send JSON as response
         */
        return $response->withJson($arrResponse);

      }
      else {
        return $response->withJson($validate);
      }
    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }

  });

  /**
   * Matches route to /employee/new and creates a new employee
   * @return JSON     Whether employee was created or not
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

      $emp_id = ((int) $request->getAttribute('id'));

      /**
       * Prepare query for getting details of doctors on that page
       * @var String
       */
      $prepare_query = "SELECT * FROM `employee` WHERE `id` = :id";
      $query = $db_connect->prepare($prepare_query);
      $query->execute(array('id' => $emp_id));

      $results = $query->fetchAll();

      if (count($results) > 0) {
        return $response->withJson($results[0]);
      }
      else {
        return $response->withJson(array('error'=> true, 'message' => 'No employee exist with this id'));
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
      return $response->withJson($arrResponse);
    }
  });

  $app->run();
?>
