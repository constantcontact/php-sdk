<?php

namespace Ctct\Components\Library;

use Ctct\Components\Component;

/**
 * Represents the status of a file upload in the Constant Contact Library
 *
 * @package        Components
 * @subpackage     Library
 * @author         Constant Contact
 */
class FileUploadStatus extends Component {
    /**
     * Detailed information about the upload status
     * @var String
     */
    public $description;

    /**
     * Unique ID for the File
     * @var String
     */
    public $file_id;

    /**
     * Indicates the file status.
     * Will be one of: Active, Processing, Uploaded, VirusFound, Failed, Deleted
     * @var String
     */
    public $status;

    public static function create(Array $props)
    {
        $fileUploadStatus = new FileUploadStatus();

        $fileUploadStatus->description = parent::getValue($props, "description");
        $fileUploadStatus->file_id = parent::getValue($props, "file_id");
        $fileUploadStatus->status = parent::getValue($props, "status");

        return $fileUploadStatus;
    }
}