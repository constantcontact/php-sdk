<?php
namespace Ctct\Services;

use Ctct\Components\Library\File;
use Ctct\Components\Library\Folder;
use Ctct\Components\ResultSet;
use Ctct\Util\Config;

class LibraryService extends BaseService
{
    /**
     * Get files from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return ResultSet
     */
    public function getLibraryFiles($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_files');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $libraryFiles = array();
        foreach ($body['results'] as $file) {
            $libraryFiles[] = File::create($file);
        }

        return new ResultSet($libraryFiles, $body['meta']);
    }

    /**
     * Get files from the Library in a specific Folder
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $folderId - Specified Folder Id
     * @param array $params - array of query parameters/values to append to the request
     * @return ResultSet
     */
    public function getLibraryFilesByFolder($accessToken, $folderId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_files_by_folder'), $folderId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $libraryFiles = array();
        foreach ($body['results'] as $file) {
            $libraryFiles[] = File::create($file);
        }

        return new ResultSet($libraryFiles, $body['meta']);
    }

    /**
     * Get File by Id
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $fileId - Specified File Id
     * @return File
     */
    public function getLibraryFile($accessToken, $fileId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file'), $fileId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return File::create($response->json());
    }

    /**
     * Get folders from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return ResultSet
     */
    public function getLibraryFolders($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_folders');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $libraryFolders = array();
        foreach ($body['results'] as $folder) {
            $libraryFolders[] = Folder::create($folder);
        }

        return new ResultSet($libraryFolders, $body['meta']);
    }
}