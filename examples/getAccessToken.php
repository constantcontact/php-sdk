<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 OAuth2 Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<!--
README: Get an access token
This example flow illustrates how to get an access token for a Constant Contact account owner using the OAuth2 server flow. 
You must have a valid Constant Contact API Key, consumer sercret, and associated redirect_uri. All of these can be obtained from
http://constantcontact.mashery.com.
-->

<?php
// require the autoloaders
require_once '../src/Ctct/autoload.php';
require_once '../vendor/autoload.php';

use Ctct\Auth\CtctOAuth2;
use Ctct\Exceptions\OAuth2Exception;

// Enter your Constant Contact APIKEY, CONSUMER_SECRET, and REDIRECT_URI
define("APIKEY", "ENTER YOUR API KEY");
define("CONSUMER_SECRET", "ENTER YOUR CONSUMER SECRET");
define("REDIRECT_URI", "ENTER YOUR REDIRECT URI");

// instantiate the CtctOAuth2 class
$oauth = new CtctOAuth2(APIKEY, CONSUMER_SECRET, REDIRECT_URI);
?>

<body>
<div class="well">
    <h3>OAuth 2 Authorization Example</h3>

    <?php
    // print any error from Constant Contact that occurs during the authorization process
    if (isset($_GET['error'])) {
        echo '<span class="label label-important">OAuth2 Error!</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        echo 'Error: ' . htmlspecialchars( $_GET['error'] );
        echo '<br />Description: ' . htmlspecialchars( $_GET['error_description'] );
        echo '</pre></div>';
        die();
    }

    // If the 'code' query parameter is present in the uri, the code can exchanged for an access token
    if (isset($_GET['code'])) {
        try {
            $accessToken = $oauth->getAccessToken($_GET['code']);
        } catch (OAuth2Exception $ex) {
            echo '<span class="label label-important">OAuth2 Error!</span>';
            echo '<div class="container alert-error"><pre class="failure-pre">';
            echo 'Error: ' . htmlspecialchars( $ex->getMessage() );
            echo '</pre></div>';
            die();
        }

        echo '<span class="label label-success">Access Token Retrieved!</span>';
        echo '<div class="container alert-success"><pre class="success-pre">';
        print_r( htmlspecialchars( $accessToken ) );
        echo '</pre></div>';

    } else {
        ?>
        <!-- If the 'code' query parameter is not present, display the link the user needs to visit to initiate the oauth flow -->
        <button class="btn btn-primary" type="button"
                onclick="window.location.href='<?php echo $oauth->getAuthorizationUrl(); ?>';">Get Access Token
        </button>
    <?php } ?>
</div>

</body>
</html>
