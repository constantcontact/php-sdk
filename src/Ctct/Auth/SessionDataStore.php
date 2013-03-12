<?php
namespace Ctct\Auth;

use Ctct\Auth\CtctDataStore;

/**
 * Example implementation of the CTCTDataStore interface that uses session for access token storage
 *
 * @package     Auth
 * @author         Constant Contact
 */
class SessionDataStore implements CtctDataStore
{
    public function __construct()
    {
        session_start();
        
        if (!isset($_SESSION['datastore'])) {
            $_SESSION['datastore'] = array();
        }
        
    }
    
    /**
     * Add a new user to the data store
     * @params string $username - Constant Contact username
     * @params array $params - additional parameters
     */
    public function addUser($username, array $params)
    {
        $_SESSION['datastore'][$username] = $params;
    }
    
    /**
     * Get an existing user from the data store
     * @params string $username - Constant Contact username
     */
    public function getUser($username)
    {
        if (array_key_exists($username, $_SESSION['datastore'])) {
            return $_SESSION['datastore'][$username];
        } else {
            return false;
        }
    }
    
    /**
     * Update an existing user in the data store
     * @params string $username - Constant Contact username
     * @params array $params - additional parameters
     */
    public function updateUser($username, array $params)
    {
        if (array_key_exists($username, $_SESSION['datastore'])) {
            $_SESSION['datastore'][$username] = $params;
        }
    }
    
    /**
     * Delete an existing user from the data store
     * @params string $username - Constant Contact username
     */
    public function deleteUser ($username)
    {
        unset($_SESSION['datastore'][$username]);
    }
}
