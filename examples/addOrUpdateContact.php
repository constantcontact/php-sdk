<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 Add/Update Contact Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head> 

<?php
require_once '../src/Ctct/autoload.php';

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\Contacts\ContactList;
use Ctct\Exceptions\CtctException;

// define("APIKEY", "");
// define("ACCESS_TOKEN", "");
define("APIKEY", "98ts65r3mjjq8fr9ftvxj95u");
define("ACCESS_TOKEN", "11030d71-beee-4707-a261-280558ea2030");

$cc = new ConstantContact(APIKEY);

// attempt to fetch lists in the account, catching any exceptions and printing the errors to screen
try{
    $lists = $cc->getLists(ACCESS_TOKEN);
} catch (CtctException $ex) {
    foreach ($ex->getErrors() as $error) {
        print_r($error);
    }     
}

// check if the form was submitted
if (isset($_POST['email']) && strlen($_POST['email']) > 1) {

    try {
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail(ACCESS_TOKEN, $_POST['email']);

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $contact = new Contact();
            $contact->addEmail($_POST['email']);
            $contact->addList($_POST['list']);
            $contact->first_name = $_POST['first_name'];
            $contact->last_name = $_POST['last_name'];
            $returnContact = $cc->addContact(ACCESS_TOKEN, $contact); 

        // update the existing contact if address already existed
        } else {            
            $contact = $response->results[0];
            $contact->addList($_POST['list']);
            $contact->first_name = $_POST['first_name'];
            $contact->last_name = $_POST['last_name'];
            $returnContact = $cc->updateContact(ACCESS_TOKEN, $contact);  
        }
        
    // catch any exceptions thrown during the process and print the errors to screen
    } catch (CtctException $ex) {
        echo '<div class="container alert-error"><pre class="failure-pre">';
        print_r($ex->getErrors()); 
        echo '</pre></div>';
        die();
    }
} 
?>

<body>
    <div class="well">
        <h3>Add or Update a Contact</h3>
        <form class="form-horizontal" name="submitContact" id="submitContact" method="POST" action="addOrUpdateContact.php">        
            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                  <input type="email" id="email" name="email" placeholder="Email Address">
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="first_name">First Name</label>
                <div class="controls">
                  <input type="text" id="first_name" name="first_name" placeholder="First Name">
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="last_name">Last Name</label>
                <div class="controls">
                  <input type="text" id="last_name" name="last_name" placeholder="Last Name">
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="list">List</label>
                <div class="controls">
                  <select name="list">
                    <?php 
                        foreach ($lists as $list) {
                            echo '<option value="'.$list->id.'">'.$list->name.'</option>';
                        }
                    ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
                <div class="controls">
                    <input type="submit" value="Submit" class="btn btn-primary"/>
                </div>
            </div>    
        </form> 
    </div>    

    <!-- Success Message -->
    <?php if (isset($returnContact)) {
        echo '<div class="container alert-success"><pre class="success-pre">';
        print_r($returnContact); 
        echo '</pre></div>';
    } ?>

</body>
</html>