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
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
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
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.library_files_by_folder'), $folderId);
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
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
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.library_file'), $fileId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return File::create(json_decode($response->body, true));
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
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $libraryFolders = array();

        foreach ($body['results'] as $folder) {
            $libraryFolders[] = Folder::create($folder);
        }
        return new ResultSet($libraryFolders, $body['meta']);
    }
}