<?php
require('src/Ctct/autoload.php');
use Ctct\Auth\CtctOAuth2;
use Ctct\Auth\SessionDataStore;


$client_id = "3171873e-4631-49a8-9dd8-bfde4cafade5";
$client_secret = "2c26d07c1d78470fb6bce92bcd8ad116";
$redirect_uri = "http://localhost:8888/php-sdk/authorization.php";

$oauth = new CtctOAuth2($client_id, $client_secret, $redirect_uri);

if (isset($_GET['error'])) {
    die('Error: ' . $_GET['error']);
}

if (isset($_GET['code'])) {


    $token = $oauth->getAccessToken($_GET['code']);
    print_r($token);


} else {
    echo "<a href='".$oauth->getAuthorizationUrl()."'>Click to authorize</a>";
}

