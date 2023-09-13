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

$fullPath = $_SERVER['REQUEST_URI'];
$routingArray = explode('/', $_SERVER['REQUEST_URI']);

/*
*
[1]. vanapi
[2]  index.php
[3]. api
[4]. entry | content
[5]. login, register, dashboard | upload, all
*
*/

switch ($routingArray[3]) {
    case 'api':
        switch ($routingArray[4]) {
            case 'entry':
                $entryController = new portfolioEntryController();
                if(isset($routingArray[6])) $entryController->setDescriptiveOne($routingArray[5]);
                if(isset($routingArray[7])) $entryController->setDescriptiveTwo($routingArray[6]);

                switch ($routingArray[5]) {
                    case 'login':
                        $userExists = $entryController->userExistsPreFlight('user@user.com');

                        if ($userExists) {
                            $finalData = $entryController->loginUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo json_encode($finalData);
                            exit();

                        } else {

                            $message = new messageModel(2,'user does not exist.');
                            header("HTTP/1.1 204 NO CONTENT");
                            echo json_encode($message);
                            exit();
                        }

                        break;
                    
                    case 'register':
                        $userExists = $entryController->userExistsPreFlight('user@user.com');

                        if ($userExists) {
                            $finalData = $entryController->loginUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo $finalData;
                            exit();

                        } else {
                            $finalData = $entryController->registerUserRole('user@user.com');

                            header("HTTP/1.1 200");
                            echo json_encode($finalData);
                            exit();

                        }
                        break;
                    
                    case 'dashboard':
                        // Pulls Header to determine if jwt exists
                        $portfolioHeader = "Bearer jwt"; // mock header 'jwt' placeholder for actual token

                        if (! preg_match('/Bearer\s(\S+)/', $portfolioHeader , $matches)) {
                            header('HTTP/1.0 400 Bad Request');
                            echo 'Token not found in request';
                            exit;
                        }

                        $serverToken = new jwtModel();
                        $userToken = new jwtModel();
                        $jwt = $userToken->issueToken('user@user.com');

                        $parsedToken = parseJwtFn($jwt, $serverToken);
                        
                        if(validateJwtFn($parsedToken, $serverToken) && $parsedToken['roles'] == 'user') {
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
                if(isset($routingArray[6])) $contentController->setDescriptiveOne($routingArray[5]);
                if(isset($routingArray[7])) $contentController->setDescriptiveTwo($routingArray[6]);

                switch ($routingArray[5]) {
                    case 'all':
                        $contentData = $contentController->getAll();


                        $message = new messageModel(1,'');
                        $finalData = new returnDataAsJsonModel();

                        $finalData->addMessage($message);
                        $finalData->addToArray("content", $contentData);

                        header("HTTP/1.1 200");
                        echo json_encode($finalData);

                        exit();
                        break;
                    
                    case 'upload':
                        echo "reached";
                        break;

                    default:
                        $message = new messageModel(2,'Non-existant Controller Method');
                        $finalData = new returnDataAsJsonModel();

                        $finalData->addMessage($message);

                        echo json_encode($finalData);
                        break;
                }

                break;
            
            default:
            $message = new messageModel(2,'Non-existant Controller Method');
            $finalData = new returnDataAsJsonModel();

            $finalData->addMessage($message);

            echo json_encode($finalData);
            break;

        }
        break;
    
    default:
        $message = new messageModel(2,'Non-existant url');
        $finalData = new returnDataAsJsonModel();

        $finalData->addMessage($message);

        echo json_encode($finalData);
        break;
}