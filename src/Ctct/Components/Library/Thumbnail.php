<?php
namespace Ctct\Components\Library;

use Ctct\Components\Component;

/**
 * Represents a Thumbnail of a File
 *
 * @package        Components
 * @subpackage     Library
 * @author         Constant Contact
 */
class Thumbnail extends Component {
    /**
     * URL to the thumbnail hosted by Constant Contact
     * @var String
     */
    public $url;

    /**
     * Width of the thumbnail, in pixels
     * @var int
     */
    public $width;

    /**
     * Height of the thumbnail, in pixels
     * @var int
     */
    public $height;

    public static function create(array $props) {
        $thumbnail = new Thumbnail();

        $thumbnail->url = parent::getValue($props, "url");
        $thumbnail->width = parent::getValue($props, "width");
        $thumbnail->height = parent::getValue($props, "height");

        return $thumbnail;
    }
}