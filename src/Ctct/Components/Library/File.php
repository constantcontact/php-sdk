<?php
namespace Ctct\Components\Library;

use Ctct\Components\Component;

/**
 * Represents a single File in a Constant Contact Library
 *
 * @package        Components
 * @subpackage     Library
 * @author         Constant Contact
 */
class File extends Component {
    /**
     * The ID of the file
     * @var String
     */
    public $id;

    /**
     * The name of the file
     * @var String
     */
    public $name;

    /**
     * The file's description
     * @var String
     */
    public $description;

    /**
     * The name of the folder that the file is in
     * @var String
     */
    public $folder;

    /**
     * The ID of the folder that the file is in
     * @var String
     */
    public $folder_id;

    /**
     * Is this file an image?
     * @var Boolean
     */
    public $is_image;

    /**
     * Type of the file, must be one of "JPG", "GIF", "PDF", "PNG", "DOC", "XLS", "PPT", "DOCX", "XLSX", "PPTX"
     * @var String
     */
    public $type;

    /**
     * File's height in pixels, if File is an image
     * @var int
     */
    public $height;

    /**
     * File's width in pixels, if File is an image
     * @var int
     */
    public $width;

    /**
     * File's size in bytes
     * @var int
     */
    public $size;

    /**
     * URL of the image hosted by Constant Contact
     * @var String
     */
    public $url;

    /**
     * Source of the image, must be one of "ALL", "MY_COMPUTER", "STOCK_IMAGE", "FACEBOOK", "INSTAGRAM", "SHUTTERSTOCK", "MOBILE"
     * @var String
     */
    public $source;

    /**
     * Status of the file, must be one of "ACTIVE", "PROCESSING", "UPLOADED", "VIRUS_FOUND", "FAILED", "DELETED"
     * @var String
     */
    public $status;

    /**
     * Thumbnail of the file, if File is an image
     * @var Thumbnail
     */
    public $thumbnail;

    /**
     * Date the file was created, in ISO-8601 format
     * @var String
     */
    public $created_date;

    /**
     * Date the file was last modified, in ISO-8601 format
     * @var String
     */
    public $modified_date;

    public static function create(array $props) {
        $file = new File();

        $file->id = parent::getValue($props, "id");
        $file->name = parent::getValue($props, "name");
        $file->description = parent::getValue($props, "description");
        $file->folder = parent::getValue($props, "folder");
        $file->folder_id = parent::getValue($props, "folder_id");
        $file->is_image = parent::getValue($props, "is_image");
        $file->type = parent::getValue($props, "file_type");
        $file->height = parent::getValue($props, "height");
        $file->width = parent::getValue($props, "width");
        $file->size = parent::getValue($props, "size");
        $file->url = parent::getValue($props, "url");
        $file->source = parent::getValue($props, "source");
        $file->status = parent::getValue($props, "status");
        if (array_key_exists("thumbnail", $props)) {
            $file->thumbnail = Thumbnail::create($props['thumbnail']);
        }
        $file->created_date = parent::getValue($props, "created_date");
        $file->modified_date = parent::getValue($props, "modified_date");

        return $file;
    }

    /**
     * Create json used for a POST/PUT request, also handles removing attributes that will cause errors if sent
     * @return String
     */
    public function toJson() {
        unset($this->created_date);
        unset($this->modified_date);
        unset($this->status);
        return json_encode($this);
    }
}