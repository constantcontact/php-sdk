<?php
require_once '../src/Ctct/autoload.php';

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\Contacts\ContactList;
use Ctct\Exceptions\CtctException;

define("APIKEY", "");
define("ACCESS_TOKEN", "");

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
if (isset($_POST['email'])) {
    
    try {
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail(ACCESS_TOKEN, $_POST['email']);

        // create a new contact if one does not exist
        if (empty($response->results)) {
            echo "adding ...";
            $contact = new Contact();
            $contact->addEmail($_POST['email']);
            $contact->addList($_POST['list']);
            $contact->first_name = $_POST['first_name'];
            $contact->last_name = $_POST['last_name'];
            $responseContact = $cc->addContact(ACCESS_TOKEN, $contact); 

        // update the existing contact if address already existed
        } else {            
            echo "updating ...";
            $contact = $response->results[0];
            $contact->addList($_POST['list']);
            $contact->first_name = $_POST['first_name'];
            $contact->last_name = $_POST['first_name'];
            $responseContact = $cc->updateContact(ACCESS_TOKEN, $contact);  
        }

        // print the response to the screen
        print_r($responseContact);

    // catch any exceptions thrown during the process and print the errors to screen
    } catch (CtctException $ex) {
        foreach ($ex->getErrors() as $error) {
            print_r($error);
        }              
    }
}
?>

<html>
<head>
    <title>Constant Contact API v2 Add/Update Contact Example</title>
</head> 

<body>
    <h3>Add or Update a Contact</h3>
    <form name="submitContact" id="submitContact" method="POST" action="addOrUpdate.php">
            Email: <input type="text" name="email" id="email"/><br />
            First name: <input type="text" name="first_name" id="first_name" /><br />
            Last name: <input type="text" name="last_name" id="last_name" /><br />
        <select name="list">
        <?php 
            foreach ($lists as $list) {
                echo '<option value="'.$list->id.'">'.$list->name.'</option>';
            }
        ?>
        </select>
        <br /><br />
        <input type="submit" value="Submit" />
    </form>
</body>
</html>

