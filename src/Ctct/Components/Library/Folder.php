<?php
namespace Ctct\Components\Library;

use Ctct\Components\Component;

class Folder extends Component {
    public $id;

    public $name;

    public $children;

    public $itemCount;

    public $parentId;

    public $level;

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

        return $folder;
    }

    public function toJson() {
        return json_encode($this);
    }
} 