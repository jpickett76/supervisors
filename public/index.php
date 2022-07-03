<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);


$app->get('/api/supervisors', function ($request, $response, array $args) {
    // Get JSON data of supervisors
	$url = "https://o3m5qixdng.execute-api.us-east-1.amazonaws.com/api/managers";
	$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
       "Accept: application/json",
    );
	
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
	$supervisorArray = json_decode(curl_exec($curl), true);
	//Sort supervisors by Jurisdiction, lastName, firstName
	usort($supervisorArray, function(array $a, array $b) : int {
    return strcasecmp($a['jurisdiction'], $b['jurisdiction']) ?: strcasecmp($a['lastName'], $b['lastName']) ?: strcasecmp($a['firstName'], $b['firstName']);
    });
	// Exclude Numeric jurisdictions 
	$supervisorString = "";
	foreach($supervisorArray as $supervsor){
		if(is_numeric($supervsor['jurisdiction'])) {	
		} else {
		  $jurisdiction = $supervsor['jurisdiction'];
		  $firstName = $supervsor['firstName'];
		  $lastName = $supervsor['lastName'];
		  $supervisorString .= "{$jurisdiction} - {$lastName}, {$firstName} \r";
		}
	}
	//Create Response
	$response-> getBody()->write($supervisorString);
	curl_close($curl);
    //var_dump($resp);
	// Return Response
	return $response;
});

$app->post('/api/submit', function (Request $request, Response $response, $args): Response {
	//NOTE: Proper Json String is a multidimensional array when parsed with the getParsedBody method
    $all_attributes = $request->getParsedBody();
	//Check if requestion body has any content
	// FUTURE: Really should have strong parameters checking for only acceptable attributes
	if ($all_attributes){
    //    //Access inner array	
  	    $attributes = $all_attributes[0];
	
	    //print_r($attributes);
  	    //$attributes = json_decode($data, true);
	
	    //Check submitted data for required attributes,and if those attribute have values
	    //DEFINTELY NEEDS REFACTORED
	    // Possibly use an is_valid function that reutnrs TRUE or FALSE
	    $has_key = FALSE;
	    $has_value = FALSE;
	    $is_valid = FALSE;
	    $required_attributes = array("firstName","lastName","supervisor");
	    foreach ($required_attributes as $attribute) {
	        if (array_key_exists($attribute, $attributes)){
		        $has_key = TRUE;
		        if (!empty($attributes[$attribute])){
			        $has_value = TRUE;
	            }else{
		  	       $has_value = FALSE;
		        }
	        }else{
		        $has_key = FALSE;
	        }
	        if ($has_key == FALSE || $has_value == FALSE){
		        $is_valid = FALSE;
		        break;
		    } else {
			    $is_valid = TRUE;
		    }
	    }
	} else {
		$is_valid = FALSE;
	}
	
	if($is_valid){
	  //print("Is Valid? {$is_valid} \r\n");
      $html = var_export($attributes, true);
      $response->getBody()->write($html);
      return $response;
	}else{
	  //print("Not Valid");
	  $response->getBody()->write('Sorry, Your Request was missing firstName, and / or lastName, and / or supervisor or was malformed');
	  return $response->withStatus(422);
	}
});


$app->run();
