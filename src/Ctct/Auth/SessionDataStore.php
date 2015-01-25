<?php
namespace Ctct\Auth;

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
     * @param string $username - Constant Contact username
     * @param array $params - additional parameters
     */
    public function addUser($username, array $params)
    {
        $_SESSION['datastore'][$username] = $params;
    }

    /**
     * Get an existing user from the data store
     * @param string $username - Constant Contact username
     * @return Array params of the username in the datastore, or false if the username doesn't exist
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
     * @param string $username - Constant Contact username
     * @param array $params - additional parameters
     */
    public function updateUser($username, array $params)
    {
        if (array_key_exists($username, $_SESSION['datastore'])) {
            $_SESSION['datastore'][$username] = $params;
        }
    }

    /**
     * Delete an existing user from the data store
     * @param string $username - Constant Contact username
     */
    public function deleteUser($username)
    {
        unset($_SESSION['datastore'][$username]);
    }
}
