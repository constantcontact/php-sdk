<?php
namespace Ctct\Services;

use Ctct\Components\Library\File;
use Ctct\Components\Library\FileUploadStatus;
use Ctct\Components\Library\Folder;
use Ctct\Components\ResultSet;
use Ctct\Exceptions\IllegalArgumentException;
use Ctct\Util\Config;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Post\PostFile;

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

    /**
     * Upload a file to the Library. Must be one of PNG, JPG, JPEG, GIF, or PDF.
     * The server scans files for viruses, so this returns an ID for a FileUploadStatus.
     * @param string $accessToken - Constant Contact Oauth2 Access Token
     * @param string $fileName - Name of the file
     * @param string $fileLocation - Path to the location of the file on the server
     * @param string $fileType - PNG, JPG, JPEG, GIF, or PDF
     * @param string $description - Description of the file
     * @param string $source - Source
     * @param string $folderId - Folder ID to upload file to. Set as 0 for no folder.
     * @return string File upload status ID
     * @throws IllegalArgumentException if file type is not one listed in the description
     */
    public function uploadFile($accessToken, $fileName, $fileLocation, $fileType, $description, $source, $folderId)
    {
        if ($fileType != "PNG" && $fileType != "JPG" && $fileType != "JPEG" && $fileType != "GIF" && $fileType != "PDF") {
            throw new IllegalArgumentException(sprintf(Config::get('errors.file_extension'), "PNG, JPG, JPEG, GIF, PDF was " . $fileType));
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

        $response = parent::getClient()->send($request);
        return $response->getHeader("Id");
    }

    /**
     * Get the status of a File upload
     * @param string $accessToken - Constant Contact OAuth2 token
     * @param string $uploadStatusIds - Single ID or ID's of statuses to check, separated by commas (no spaces)
     * @return FileUploadStatus[] - Array of FileUploadStatus
     */
    public function getFileUploadStatus($accessToken, $uploadStatusIds)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get(sprintf('endpoints.library_file_upload_status', $uploadStatusIds));
        $request = parent::createBaseRequest($accessToken, "GET", $baseUrl);
        $response = parent::getClient()->send($request);
        $fileUploadStatuses = array();
        foreach ($response->json() as $fileUploadStatus) {
            $fileUploadStatuses[] = FileUploadStatus::create($fileUploadStatus);
        }
        return $fileUploadStatuses;
    }
}