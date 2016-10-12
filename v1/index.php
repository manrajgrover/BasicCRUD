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
  $app = new \Slim\App;

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

      if(isValidName()) {

      }

      /**
       * Prepare query for getting details of doctors on that page
       * @var String
       */
      $prepare_query = "SELECT * FROM doctors LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);

      /**
       * Query the database and get rows of each doctor
       * @var Array
       */
      $data = $db->query($query, $params);

      /**
       * Check if any doctor exist on the page
       */
      if (count($data) === 0) {
        $arrResponse = array('error' => true, 'message' => 'No more pages exist');
      } else {
        $arrResponse = array('error' => false, 'doctors' => $data);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
    }

    /**
     * Send JSON as response
     */
    return $response->withJson($arrResponse);
  });

  $app->run();
?>
