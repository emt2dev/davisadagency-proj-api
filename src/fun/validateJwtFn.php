<?php

function validateJwtFn($userToken, jwtModel $serverToken) {
    if($userToken['iss'] == $serverToken->getServerName()) return true;
    else return false;
}

?>