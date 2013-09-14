<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require '../../libs/Slim/Slim.php';
require "../../libs/NotORMLib/NotORM.php";
require_once 'conf.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();


/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

function getDb(){
    return new NotORM(mysqlConnector());
}

$app->get(
    '/verticals',
	function () use ($app){
		$db = getDb();
        $result = array();
        $rows = $db->T_PRJ_VERTICAL()
                     ->select("VER_ID, VER_NAME");
            
        foreach ($rows as $row) {
            $result[]  = array(
                "id" => $row["VER_ID"],
                "title" => $row["VER_NAME"]
            );
        }
        $app->response()->header("Content-Type", "application/json");    
        echo json_encode($result);
	}
);

$app->get(
    '/PROJECT/:ID',
	function ($ID) use ($app){
		$db = getDb();
        $result = array();
        $rows = $db->T_PRJ_WEEK_STATUS()
                     ->select("OFS_RESOURCES,ACC_ID, ONS_RESOURCES,GROSS_MARGIN,UNPLN_ATTRITION,NEAR_RESOURCES,PLN_REV,ACT_REV,NEAR_RESOURCES+OFS_RESOURCES+ONS_RESOURCES")
					 ->where("PRJ_ID",$ID);
            			 
        foreach ($rows as $row) {		
            $result[]  = array(
				"URL"=>"/ACCOUNT/:" . $row["ACC_ID"],
                "OFS_RESOURCES" => $row["OFS_RESOURCES"],
                "ONS_RESOURCES" => $row["ONS_RESOURCES"],
				"GROSS_MARGIN" => $row["GROSS_MARGIN"],
                "UNPLN_ATTRITION" => $row["UNPLN_ATTRITION"],
				"TOTAL"=>$row["NEAR_RESOURCES+OFS_RESOURCES+ONS_RESOURCES"],
				"PLN_REV" => $row["PLN_REV"],
                "ACT_REV" => $row["ACT_REV"]
            );
        }
		
        $app->response()->header("Content-Type", "application/json");    
        echo json_encode($result);
	}
);

// GET route
$app->get(
    '/',
    function () use ($app) { 
        $db = getDb();
        $result = array();
        $rows = $db->SampleTable()
                     ->select("Id, Name");
            
        foreach ($rows as $row) {
            $result[]  = array(
                "id" => $row["Id"],
                "title" => $row["Name"]
            );
        }
        $app->response()->header("Content-Type", "application/json");
    
        echo json_encode($result);
        
    }
);

// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
    '/delete',
    function () {
        echo 'This is a DELETE route';
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();