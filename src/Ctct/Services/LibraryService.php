<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Components\Library\File;
use Ctct\Components\Library\FileUploadStatus;
use Ctct\Components\Library\Folder;
use Ctct\Components\ResultSet;
use Ctct\Exceptions\IllegalArgumentException;
use Ctct\Util\Config;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Post\PostFile;

class LibraryService extends BaseService
{
    /**
     * Get files from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return ResultSet
     * @throws CtctException
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

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @throws CtctException
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

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @throws CtctException
     */
    public function getLibraryFile($accessToken, $fileId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file'), $fileId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return File::create($response->json());
    }

    /**
     * Delete a File
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $fileId - Specified File Id
     * @return boolean
     * @throws CtctException
     */
    public function deleteLibraryFile($accessToken, $fileId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file'), $fileId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Get folders from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return ResultSet
     * @throws CtctException
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

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        $body = $response->json();
        $libraryFolders = array();
        foreach ($body['results'] as $folder) {
            $libraryFolders[] = Folder::create($folder);
        }

        return new ResultSet($libraryFolders, $body['meta']);
    }

    /**
     * Get a specific Folder
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $folderId - ID of the Folder
     * @return Folder
     * @throws CtctException
     */
    public function getLibraryFolder($accessToken, $folderId)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get(sprintf('endpoints.library_folder', $folderId));

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        $body = $response->json();
        return Folder::create($body);
    }

    /**
     * Delete a Library Folder
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $folderId - ID of the Folder
     * @return boolean
     * @throws CtctException
     */
    public function deleteLibraryFolder($accessToken, $folderId)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get(sprintf('endpoints.library_folder', $folderId));

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Upload a file to the Library. Must be one of PNG, JPG, JPEG, GIF, or PDF.
     * The server scans files for viruses, so this returns an ID for a FileUploadStatus.
     * @param string $accessToken - Constant Contact Oauth2 Access Token
     * @param string $fileName - Name of the file
     * @param string $fileLocation - Path to the location of the file on the server
     * @throws IllegalArgumentException if file type is not one listed in the description
     * @param string $description - Description of the file
     * @param string $source - Source
     * @param string $folderId - Folder ID to upload file to. Set as 0 for no folder.
     * @return string File upload status ID
     * @throws CtctException
     */
    public function uploadFile($accessToken, $fileName, $fileLocation, $description, $source, $folderId)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime =  finfo_file($finfo, $fileLocation);
        finfo_close($finfo);

        if ($mime == "image/png") {
            $fileType = "PNG";
        } elseif ($mime = "image/jpeg") {
            $fileType = "JPG";
        } elseif ($mime = "image/gif") {
            $fileType = "GIF";
        } elseif ($mime ="application/pdf") {
            $fileType = "PDF";
        } else {
            throw new IllegalArgumentException(sprintf(Config::get('errors.file_extension'), "PNG, JPG, JPEG, GIF, PDF was " . $mime));
        }

        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_files');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);
        $request->setHeader("Content-Type", "multipart/form-data");

        $body = new PostBody();
        $body->setField("folderId", $folderId);
        $body->setField("file_name", $fileName);
        $body->setField("file_type", $fileType);
        $body->setField("description", $description);
        $body->setField("source", $source);
        $body->addFile(new PostFile("data", fopen($fileLocation, 'r'), $fileName));
        $request->setBody($body);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return $response->getHeader("Id");
    }

    /**
     * Get the status of a File upload
     * @param string $accessToken - Constant Contact OAuth2 token
     * @param string $uploadStatusIds - Single ID or ID's of statuses to check, separated by commas (no spaces)
     * @return FileUploadStatus[] - Array of FileUploadStatus
     * @throws CtctException
     */
    public function getFileUploadStatus($accessToken, $uploadStatusIds)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file_upload_status'), $uploadStatusIds);
        $request = parent::createBaseRequest($accessToken, "GET", $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        $fileUploadStatuses = array();
        foreach ($response->json() as $fileUploadStatus) {
            $fileUploadStatuses[] = FileUploadStatus::create($fileUploadStatus);
        }
        return $fileUploadStatuses;
    }
}