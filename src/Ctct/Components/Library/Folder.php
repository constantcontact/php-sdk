<?php
namespace Ctct\Components\Library;

use Ctct\Components\Component;

class Folder extends Component {
    /**
     * ID of the Folder
     * @var String
     */
    public $id;

    /**
     * Name of the Folder
     * @var String
     */
    public $name;

    /**
     * Array of Folders that are children of this folder
     * @var Folder[]
     */
    public $children;

    /**
     * Number of items in this folder
     * @var int
     */
    public $itemCount;

    /**
     * ID of this folder's parent, if there is one
     * @var String
     */
    public $parentId;

    /**
     * Depth that this folder is in the hierarchy, must be 1, 2, or 3
     * @var int
     */
    public $level;

    /**
     * Date and time the folder was created
     * @var String
     */
    public $createdDate;

    /**
     * Date and time the folder was last modified
     * @var String
     */
    public $modifiedDate;

    public static function create(array $props) {
        $folder = new Folder();

        $folder->id = parent::getValue($props, "id");
        $folder->name = parent::getValue($props, "name");
        foreach ($props['children'] as $child) {
            $folder->children[] = Folder::create($child);
        }
        $folder->itemCount = parent::getValue($props, "item_count");
        $folder->parentId = parent::getValue($props, "parent_id");
        $folder->level = parent::getValue($props, "level");
        $folder->createdDate = parent::getValue($props, "created_date");
        $folder->modifiedDate = parent::getValue($props, "modified_date");

        return $folder;
    }

    public function toJson() {
        return json_encode($this);
    }
} 