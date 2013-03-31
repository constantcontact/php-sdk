<?php
require_once '../src/Ctct/autoload.php';

use Ctct\Auth\CtctOAuth2;
use Ctct\Auth\SessionDataStore;
use Ctct\Exceptions\OAuth2Exception;

define("APIKEY", "ENTER YOUR API KEY");
define("CONSUMER_SECRET", "ENTER YOUR CONSUMER SECRET");
define("REDIRECT_URI", "ENTER YOUR REDIRECT URI");

$oauth = new CtctOAuth2(APIKEY, CONSUMER_SECRET, REDIRECT_URI);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 OAuth2 Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head> 

<body>
    <div class="well">
        <h3>OAuth 2 Authorization Example</h3>

<?php
// print an error to the screen if one occurs during the authorization process
if (isset($_GET['error'])) {
    echo '<span class="label label-important">OAuth2 Error!</span>';
    echo '<div class="container alert-error"><pre class="failure-pre">';
    echo 'Error: ' . $_GET['error'];
    echo '<br />Description: ' . $_GET['error_description'];
    echo '</pre></div>';
    die();
}

// If 'code' is detected in the uri, the code can now be exchanged for an access token
if (isset($_GET['code'])) {
    try{
        $accessToken = $oauth->getAccessToken($_GET['code']);
    } catch (OAuth2Exception $ex) {
        echo '<span class="label label-important">OAuth2 Error!</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        echo 'Error: ' . $ex->getMessage();
        echo '</pre></div>';
        die();
    }

    echo '<span class="label label-success">Access Token Retrieved!</span>';
    echo '<div class="container alert-success"><pre class="success-pre">';
    print_r($accessToken); 
    echo '</pre></div>';

} else { 
?>
    <button class="btn btn-primary" type="button" onclick="window.location.href='<?php echo $oauth->getAuthorizationUrl();?>';">Get Access Token</button>
    
</div>    

</body>
</html>
<?php } ?>