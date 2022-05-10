<?php

function Get($index, $defaultValue) {
    return isset($_GET[$index]) ? $_GET[$index] : $defaultValue;
}

function is_run_from_cli() {
    if( defined('STDIN') )
    {
        return true;
    }
    return false;
}

if (!is_run_from_cli()) {
    # check SaasActivationPassword
    if (Get('SaasActivationPassword', 'invalid') != '{{SaasActivationPassword}}') {
        echo '{"success": false, "msg": "invalid SaasActivationPassword"}';
        exit(1);
    }
}

try {
    $DB_NAME = "{{pac}}_{{user}}";
    $DB_USER = "{{pac}}_{{user}}";
    $DB_PASSWORD = "{{password}}";
    $pdo = new PDO('mysql:host=localhost;dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD);
    # deactivate all users
    $sql = "update lime_users set email=CONCAT(email,'disabled'), password=CONCAT(password,'disabled') where email NOT LIKE '%disabled'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
}
catch (Exception $e) {
    // echo 'Exception caught: ',  $e->getMessage(), "\n";
    echo '{"success": false, "msg": "error happened"}';
    exit(1);
}

echo '{"success": true}';

?>