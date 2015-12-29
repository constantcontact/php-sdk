<?php
namespace Ctct\Services;

use Ctct\Components\Library\File;
use Ctct\Components\Library\FileUploadStatus;
use Ctct\Components\Library\Folder;
use Ctct\Components\ResultSet;
use Ctct\Exceptions\CtctException;
use Ctct\Exceptions\IllegalArgumentException;
use Ctct\Util\Config;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;

class LibraryService extends BaseService {
    /**
     * Get files from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 1000, default = 50.
     *      sort_by - Specifies how the list of files is sorted; valid sort options are:
     *                CREATED_DATE, CREATED_DATE_DESC, MODIFIED_DATE, MODIFIED_DATE_DESC, NAME, NAME_DESC, SIZE, SIZE_DESC DIMENSION, DIMENSION_DESC
     *      source - Specifies to retrieve files from a particular source:
     *               ALL, MyComputer, Facebook, Instagram, Shutterstock, Mobile
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet
     * @throws CtctException
     */
    public function getLibraryFiles($accessToken, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_files');

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 1000, default = 50.
     *      sort_by - Specifies how the list of files is sorted; valid sort options are:
     *                CREATED_DATE, CREATED_DATE_DESC, MODIFIED_DATE, MODIFIED_DATE_DESC, NAME, NAME_DESC, SIZE, SIZE_DESC DIMENSION, DIMENSION_DESC
     *      source - Specifies to retrieve files from a particular source:
     *               ALL, MyComputer, Facebook, Instagram, Shutterstock, Mobile
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet
     * @throws CtctException
     */
    public function getLibraryFilesByFolder($accessToken, $folderId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_files_by_folder'), $folderId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
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
    public function getLibraryFile($accessToken, $fileId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file'), $fileId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return File::create(json_decode($response->getBody(), true));
    }

    /**
     * Delete a File
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $fileId - Specified File Id
     * @return boolean
     * @throws CtctException
     */
    public function deleteLibraryFile($accessToken, $fileId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file'), $fileId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'DELETE', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Get folders from the Library
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 1000, default = 50.
     *      sort_by - Specifies how the list of files is sorted; valid sort options are:
     *                CREATED_DATE, CREATED_DATE_DESC, MODIFIED_DATE, MODIFIED_DATE_DESC, NAME, NAME_DESC
     * @return ResultSet
     * @throws CtctException
     */
    public function getLibraryFolders($accessToken, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_folders');

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
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
    public function getLibraryFolder($accessToken, $folderId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_folder'), $folderId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        return Folder::create($body);
    }

    /**
     * Delete a Library Folder
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $folderId - ID of the Folder
     * @return boolean
     * @throws CtctException
     */
    public function deleteLibraryFolder($accessToken, $folderId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_folder'), $folderId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'DELETE', $baseUrl);
        } catch (TransferException $e) {
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
    public function uploadFile($accessToken, $fileName, $fileLocation, $description, $source, $folderId) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileLocation);
        finfo_close($finfo);
        if ($mime == "image/png") {
            $fileType = "PNG";
        } elseif ($mime == "image/jpeg") {
            $fileType = "JPG";
        } elseif ($mime == "image/gif") {
            $fileType = "GIF";
        } elseif ($mime == "application/pdf") {
            $fileType = "PDF";
        } else {
            throw new IllegalArgumentException(sprintf(Config::get('errors.file_extension'), "PNG, JPG, JPEG, GIF, PDF was " . $mime));
        }


        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_files');
        $request = new Request('POST', $baseUrl, [
            parent::getHeadersForMultipart($accessToken)
        ]);

        try {
            $response = parent::getClient()->send($request, [
                'multipart' => [
                    [
                        'folder_id' => $folderId,
                        'file_name' => $fileName,
                        'file_type' => $fileType,
                        'description' => $description,
                        'source' => $source,
                        'data' => fopen($fileLocation, 'r')
                    ]
                ]
            ]);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return $response->getHeader("Id");
    }

    /**
     * Creates a new Library folder
     * @param string $accessToken - Constant Contact OAuth2 token
     * @param Folder $folder
     * @return \Ctct\Components\Library\Folder - Newly created folder
     * @throws CtctException
     */
    public function createLibraryFolder($accessToken, Folder $folder) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.library_folders');

        try {
            $response = parent::sendRequestWithBody($accessToken, "POST", $baseUrl, $folder);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        return Folder::create($body);
    }

    /**
     * Get the status of a File upload
     * @param string $accessToken - Constant Contact OAuth2 token
     * @param string $uploadStatusIds - Single ID or ID's of statuses to check, separated by commas (no spaces)
     * @return FileUploadStatus[] - Array of FileUploadStatus
     * @throws CtctException
     */
    public function getFileUploadStatus($accessToken, $uploadStatusIds) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.library_file_upload_status'), $uploadStatusIds);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, "GET", $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $fileUploadStatuses = array();
        foreach (json_decode($response->getBody(), true) as $fileUploadStatus) {
            $fileUploadStatuses[] = FileUploadStatus::create($fileUploadStatus);
        }
        return $fileUploadStatuses;
    }
}