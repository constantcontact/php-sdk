CHANGELOG for the Constant Contact PHP SDK
=====================================

1.1.0 | 5-4-2013
------------------

* Added 'permalink_url' property to EmailMarketing\Campaign 
* Added the ability to query EmailMarketing, Contacts, and Lists collections with the 'modified_since' parameter
    ie: GET on /contacts?modified_since=2013-01-12T20:04:59.436Z
* Added the ability to query Contact and Campaign Tracking collections with the 'created_since' parameter.
* Changed the structure of the methods in ConstantContact.php that are impacted by the above changes to 
    allow an array of parameters to be passed in and used as query parameters. For example:

    $cc = new ConstantContact($apiKey);
    $contactsPageOne = $cc->getContacts($accessToken, array(
        'limit' => 10,
        'modified_since' => 2013-01-12T20:04:59.436Z 
    ));

    $contactsPageTwo = $cc->getContacts($accessToken, array(
        'next' => $contactsPageOne->next
    ));

    For details on paged output, please visit: http://developer.constantcontact.com/docs/developer-guides/paginated-output.html