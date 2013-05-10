<?php
namespace Ctct\Auth;

/**
 * Interface containing the necessary functionality to manage an OAuth2 data store
 *
 * @package     Auth
 * @author         Constant Contact
 */

interface CtctDataStore
{

    /**
     * Add a new user to the data store
     * @param $id - unique identifier
     * @param array $params - additional parameters
     */
    public function addUser($id, array $params);

    /**
     * Get an existing user from the data store
     * @param $id - unique identifier
     */
    public function getUser($id);

    /**
     * Update an existing user in the data store
     * @param $id - unique identifier
     * @param array $params - additional parameters
     */
    public function updateUser($id, array $params);

    /**
     * Delete an existing user from the data store
     * @param $id - unique identifier
     */
    public function deleteUser($id);
}
