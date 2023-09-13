<?php
declare(strict_types=1);

require ("vendor/autoload.php");
require ("src/fun/sanitizeFn.php");
require ("src/fun/validateJwtFn.php");
require ("src/fun/parseJwtFn.php");
require ("src/models/contentModel.php");
require ("src/models/messageModel.php");
require ("src/models/returnDataAsJsonModel.php");
require ("src/models/userModel.php");
require ("src/models/jwtModel.php");
require ("src/controllers/portfolioController/portfolioEntryController.php");
require ("src/controllers/portfolioController/portfolioContentController.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// $__db[] array
// $__db[0] ="localhost"; // server
// $__db[1] ="root"; // user
// $__db[2] ="david"; // pass
// $__db[3] ="vanapi"; //db name

// Create our database connection
// $connection = new mysqli($__db[0], $__db[1], $__db[2], $__db[3]);

// Ensure our database connection works
// if ($connection->connect_error) {
//   die("Connection failed: " . $connection->connect_error);
// }

$fullPath = $_SERVER['REQUEST_URI'];
$controllerArray = explode('/', $_SERVER['REQUEST_URI']);

/*
*
[1]. vanapi
[2] index.php
[3]. api
[4]. entry | content
[5]. login, register, dashboard | upload, all
*
*/

print_r($controllerArray);

switch ($controllerArray[3]) {
    case 'api':
        switch ($controllerArray[4]) {
            case 'entry':
                $entryController = new portfolioEntryController();
                if(isset($controllerArray[6])) $entryController->setDescriptiveOne($controllerArray[5]);
                if(isset($controllerArray[7])) $entryController->setDescriptiveTwo($controllerArray[6]);

                switch ($controllerArray[5]) {
                    case 'login':
                        // $userExists = $entryController->userExistsPreFlight($_POST['email']);
                        $userExists = $entryController->userExistsPreFlight('user@user.com');

                        if ($userExists) {

                            // $finalData = $entryController->loginUserRole($_POST['password']);
                            $finalData = $entryController->loginUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo $finalData;
                            exit();

                        } else {

                            $message = new messageModel(2,'user does not exist.');
                            header("HTTP/1.1 204 NO CONTENT");
                            echo json_encode($message);
                            exit();
                        }

                        break;
                    
                    case 'register':
                        // $userExists = $entryController->userExistsPreFlight($_POST['email']);
                        $userExists = $entryController->userExistsPreFlight('user@user.com');

                        if ($userExists) {

                            // $finalData = $entryController->loginUserRole($_POST['password']);
                            $finalData = $entryController->loginUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo $finalData;
                            exit();

                        } else {

                            // $finalData = $entryController->registerUserRole($_POST['password']);
                            $finalData = $entryController->registerUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo $finalData;
                            exit();

                        }
                        break;
                    
                    case 'dashboard':
                        // Pulls Header to determine if jwt exists
                        // Here is where we check the jwt and determine if user exists
                        // if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                        //     header('HTTP/1.0 400 Bad Request');
                        //     echo 'Token not found in request';
                        //     exit;
                        // }

                        $portfolioHeader = "Bearer jwt"; // mock header 'jwt' placeholder for actual token

                        if (! preg_match('/Bearer\s(\S+)/', $portfolioHeader , $matches)) {
                            header('HTTP/1.0 400 Bad Request');
                            echo 'Token not found in request';
                            exit;
                        }

                        // only for prod
                        /*
                        $jwt = $matches[1]; // pulls token

                        if (! $jwt) {
                            // No token was able to be extracted from the authorization header
                            $message = new messageModel(1,'');
    
                            $data = new returnDataAsJsonModel();
                            $data->addToArray("settings", $settingsArray);
                            $data->addMessage($message);

                            header('HTTP/1.0 400 Bad Request');
                            echo $data->sendData();
                            exit;
                        }
                        */

                        $serverToken = new jwtModel();
                        $userToken = new jwtModel();
                        $jwt = $userToken->issueToken('user@user.com');

                        $parsedToken = parseJwtFn($jwt, $serverToken);
                        
                        if(validateJwtFn($parsedToken, $serverToken)) {
                            $userExists = $entryController->userExistsPreFlight($parsedToken['userEmail']);

                            if($userExists) {
                                $settingsArray['darkMode'] = true;
                                $settingsArray['currency'] = "usd";
        
                                $message = new messageModel(1,'');
        
                                $data = new returnDataAsJsonModel();
                                $data->addToArray("settings", $settingsArray);
                                $data->addMessage($message);
        
                                header("HTTP/1.1 200");
                                echo json_encode($data);
                                exit();
                            } else {
                                
                                $message = new messageModel(2,'No User Exists');
    
                                $data = new returnDataAsJsonModel();
                                $data->addToArray("settings", $settingsArray);
                                $data->addMessage($message);
    
                                header("HTTP/1.1 200");
                                echo json_encode($data);
                                exit();
                            }
                        } else {
                            $message = new messageModel(2,'Invalid Token');

                            $data = new returnDataAsJsonModel();
                            $data->addMessage($message);

                            header("HTTP/1.1 401");
                            echo json_encode($data);
                            exit();
                        }

                        break;

                    default:
                        $message = new messageModel(2,'Route Does Not Exist');

                        $data = new returnDataAsJsonModel();
                        $data->addMessage($message);

                        header("HTTP/1.1 404");
                        echo json_encode($data);

                        exit();
                        break;
                }
                break;

            case 'content':
                $contentController = new portfolioContentController();
                if(isset($controllerArray[6])) $contentController->setDescriptiveOne($controllerArray[5]);
                if(isset($controllerArray[7])) $contentController->setDescriptiveTwo($controllerArray[6]);

                switch ($controllerArray[5]) {
                    case 'all':
                        $finalData = $contentController->getAll();

                        header("HTTP/1.1 200");
                        echo $finalData;

                        exit();
                        break;
                    
                    case 'upload':
                        echo "reached";
                        break;

                    default:
                        $message = new messageModel(2,'Non-existant Controller Method');
                        echo $message->getValue();
                        break;
                }

                break;
            
            default:
                $message = new messageModel(2,'Non-existing Controller');
                echo $message->getValue();
                break;
        }
        break;
    
    default:
        $message = new messageModel(2,'Incorrect URL');
        echo $message->getValue();
        break;
}