<?php

function Get($index, $defaultValue) {
  return isset($_GET[$index]) ? $_GET[$index] : $defaultValue;
}

# check SaasActivationPassword
if (Get('SaasActivationPassword', 'invalid') != '{{SaasActivationPassword}}') {
  echo '{"success": false, "msg": "invalid SaasActivationPassword"}';
  exit(1);
}

try {
  $USER_EMAIL_ADDRESS = Get('UserEmailAddress', '');
  if (empty($USER_EMAIL_ADDRESS)) {
    echo '{"success": false, "msg": "missing email address"}';
    exit(1);
  }

  $DB_NAME = "{{pac}}_{{user}}";
  $DB_USER = "{{pac}}_{{user}}";
  $DB_PASSWORD = "{{password}}";
  $pdo = new PDO('mysql:host=localhost;dbname='.$DB_NAME, $DB_USER, $DB_PASSWORD);
  $sql = "update lime_users set email=:email where uid=1";
  $statement = $pdo->prepare($sql);
  $statement->execute(array(':email' => $USER_EMAIL_ADDRESS));
}
catch (Exception $e) {
    // echo 'Exception caught: ',  $e->getMessage(), "\n";
    echo '{"success": false, "msg": "error happened"}';
    exit(1);
}

echo '{"success": true}';
?>
