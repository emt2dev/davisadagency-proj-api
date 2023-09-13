<?php
declare(strict_types=1);

require ("vendor/autoload.php");
require ("src/fun/sanitizeFn.php");
require ("src/fun/validateJwtFn.php");
require ("src/fun/parseJwtFn.php");
require ("src/models/contentModel.php");
require ("src/models/messageModel.php");
require ("src/models/userModel.php");
require ("src/models/jwtModel.php");
require ("src/controllers/entryController.php");
require ("src/controllers/contentController.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$fullPath = $_SERVER['REQUEST_URI'];
$routingArray = explode('/', $_SERVER['REQUEST_URI']);

// $__db[] array
$__db[0] ="localhost"; // server
$__db[1] ="root"; // user
$__db[2] ="david"; // pass
$__db[3] ="vanapi"; //db name

// Create our database connection
$connection = new mysqli($__db[0], $__db[1], $__db[2], $__db[3]);

// Ensure our database connection works
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

header("Content-Type:application/json");

/*
*
[1]. vanapi
[2]  api.php *need to remove file extension*
[3]. entry | content
[4]. login, register, dashboard | upload, all
*
*/

// header('Location: http://127.0.0.1/vanapi/api');
// exit();

// validate token and get user id here
$validToken = false;
$jwtUserId = "";
if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
    if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
        header('HTTP/1.0 400 Bad Request');
        echo 'Token not found in request';
        exit;
    } else {
        $validToken = true;

        $serverToken = new jwtModel();
        $userToken = new jwtModel();
        $jwt = $userToken->issueToken('user@user.com');

        $parsedToken = parseJwtFn($jwt, $serverToken);
        $jwtUserId = $parsedToken['sub'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === "GET" && count($routingArray) >= 5) {
    if ($routingArray[3] == "user" && $validToken !== true) {
        // This bracket prevents api calls to protected data if invalid JWT
        $message = new messageModel(2,'Invalid or non-existant Token');
        $serverResponse = array($message->getKey() => $message->getValue());
        header('HTTP/1.0 400 Bad Request');
        echo json_encode($serverResponse);
        exit();

    } else if (isset($routingArray[3])){
        // Here we begin our switch statement to determine what the user wants to do

        switch ($routingArray[3]) {
            case 'content':
                $contentController = new contentController($connection);
                if(isset($routingArray[4]) &&  $routingArray[4] == "all") {
                    // gets all content from the database without pagination
                    $contentData = $contentController->getAll();
    
                    $message = new messageModel(1,'');

                    $serverResponse = array($message->getKey() => $message->getValue(), "content", $contentData);

                    header("HTTP/1.1 200");
                    echo json_encode($serverResponse);

                    exit();
                    break;
                } else if (isset($routingArray[4]) &&  $routingArray[4] == "details" && isset($routingArray[5])) {
                    // gets content details of specified content
                    $contentData = $contentController->getOne(intval($routingArray[5]));
                        
                    $message = new messageModel(1,'');
                    
                    $serverResponse = array($message->getKey() => $message->getValue(), "content", $contentData);

                    header("HTTP/1.1 200");
                    echo json_encode($serverResponse);

                    exit();
                    break;
                } else {
                    $message = new messageModel(2,'Did not specify content/all or content/details/blogNumber');
                    $serverResponse = array($message->getKey() => $message->getValue());
                    header('HTTP/1.0 400 Bad Request');
                    echo json_encode($serverResponse);
                }
                break;
            default:
                break;
            }
        $message = new messageModel(2,'Reached Working Get Route!');
        $serverResponse = array($message->getKey() => $message->getValue());
        header('HTTP/1.0 200 Good Request');
        echo json_encode($serverResponse);
        exit();
    }

} else if ($_SERVER['REQUEST_METHOD'] === "POST" && count($routingArray) >= 5) {
    if ($routingArray[3] == "user" && $validToken !== true || $routingArray[3] == "content" && $validToken !== true) {
        // This bracket prevents api calls to submit protected data if invalid JWT
        $message = new messageModel(2,'Invalid or non-existant Token');
        $serverResponse = array($message->getKey() => $message->getValue());
        header('HTTP/1.0 400 Bad Request');
        echo json_encode($serverResponse);
        exit();
    } else if (isset($routingArray[3]) && isset($routingArray[4])) {
        // Here we begin our switch statement to determine what the user wants to do
        switch ($routingArray[3]) {
            case 'content':
                $contentController = new contentController($connection);
                if($routingArray[4] == "upload") {
                    $upload_dir = "uploads/assets/uploads/";

                    $imageFileType = strtolower(pathinfo(basename($_FILES["uploadedFile"]["name"]),PATHINFO_EXTENSION));
                    $target_file = $upload_dir . basename($_FILES["uploadedFile"]["name"]).".".$imageFileType;

                    // here we move the file into the directory AND create out product model which will be used to save our product into our database.
                    if  (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {

                        $AuthorName = $contentController->getAuthorName($jwtUserId);

                        if($AuthorName !== false) {
                            $newContent = new newContentModel($_POST['title'], $_POST['body'], $_POST['keywords'], $AuthorName);

                            $Success = $contentController->upload($newContent);

                            if($Success) {
                                $message = new messageModel(1,'');
                                $serverResponse = array($message->getKey() => $message->getValue());
                                header('HTTP/1.0 200 Success');
                                echo json_encode($serverResponse);
                                exit();
                                break;
                            } else {
                                $message = new messageModel(1,'');
                                $serverResponse = array($message->getKey() => $message->getValue());
                                header('HTTP/1.0 400 Bad Request');
                                echo json_encode($serverResponse);
                                exit();
                                break;
                            }
                        }

                        $message = new messageModel(1,'');
                        $serverResponse = array($message->getKey() => $message->getValue());
                        header('HTTP/1.0 403 Jwt does not contain author name');
                        echo json_encode($serverResponse);
                        exit();
                        break;
                    } else {

                        $message = new messageModel(2,'The file upload was not successful. Please submit a .png file');
                        $serverResponse = array($message->getKey() => $message->getValue());
                        header('HTTP/1.0 400 File was not uploaded properly');
                        echo json_encode($serverResponse);
                        exit();
                        break;
                    }
                }
            break;

            case 'entry':
                $entryController = new entryController($connection);
                switch ($routingArray[4]) {
                    case 'login':
                        $userExists = $entryController->userExistsPreFlight($_POST['email']);

                        if ($userExists) {
                            $Success = $entryController->loginUserRole($_POST['password']);
                            
                            if($Success) {
                                header("HTTP/1.1 200");
                                $message = new messageModel(2,'User logged in.');
                                $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                echo json_encode($serverResponse);
                                exit();
                            } else {

                                $message = new messageModel(2,'Invalid password or email.');
                                header("HTTP/1.1 204");
                                $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                echo json_encode($serverResponse);
                                exit();
                            }
                        } else {
                            $message = new messageModel(2,'User does not exist.');
                            header("HTTP/1.1 204 NO CONTENT");
                            $serverResponse = array($message->getKey() => $message->getValue());
                            echo json_encode($serverResponse);
                            exit();
                        }
                        break;
                    case 'register':
                        $userExists = $entryController->userExistsPreFlight($_POST['email']);
    
                            if ($userExists) {
                                $Success = $entryController->loginUserRole($_POST['password']);

                                if($Success) {
                                    header("HTTP/1.1 200");
                                    $message = new messageModel(2,'User logged in.');
                                    $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                    echo json_encode($serverResponse);
                                    exit();
                                } else {

                                    $message = new messageModel(2,'Invalid password or email.');
                                    header("HTTP/1.1 204");
                                    $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                    echo json_encode($serverResponse);
                                    exit();
                                }
                            } else {
                                $Success = $entryController->registerUserRole($_POST['password'], $_POST['email'], $_POST['name']);
    
                                if($Success) {
                                    header("HTTP/1.1 200");
                                    $message = new messageModel(2,'User registered.');
                                    $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                    echo json_encode($serverResponse);
                                    exit();
                                } else {

                                    $message = new messageModel(2,'Unable to register user, please try again.');
                                    header("HTTP/1.1 204");
                                    $serverResponse = array($message->getKey() => $message->getValue(), "user" => $entryController->userRow);
                                    echo json_encode($serverResponse);
                                    exit();
                                }
                            }
                        break;
                    default:
                        $message = new messageModel(2,'User does not exist.');
                        header("HTTP/1.1 204 NO CONTENT");
                        $serverResponse = array($message->getKey() => $message->getValue());
                        echo json_encode($serverResponse);
                        exit();
                        break;
                }
                break;
            default:
            $message = new messageModel(2,'This route does not exist yet!');
            $serverResponse = array($message->getKey() => $message->getValue());

            echo json_encode($serverResponse);
            break;
        }
    } else {
        // This bracket is reached if invalid routing or not a POST or GET request
        $message = new messageModel(2,'Invalid Routes or Invalid Request Method!');
        $serverResponse = array($message->getKey() => $message->getValue());

        echo json_encode($serverResponse);
    }
}