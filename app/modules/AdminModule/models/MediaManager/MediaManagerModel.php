<?php

namespace MediaManager;

use Nette;

class MediaManagerModel {

    public $db;
    public $path;
    protected $tree;
    protected $files;

    public function __construct(Nette\Database\Context $db, $path) {
        $this->db = $db;
        $this->path = $path;
        $this->tree = $this->dirtree($path);
    }

    protected function router() {
        
    }

    protected function dirtree($path) {
        $result = [];
        $iterator = new \DirectoryIterator($path);
        forEach ($iterator as $node) {
            if ($node->isDot()) {
                continue;
            }
            if ($node->isDir()) {
                $result[$path . DIRECTORY_SEPARATOR . $node->__toString()] = $path . DIRECTORY_SEPARATOR . $node->__toString();
                $list[$path . DIRECTORY_SEPARATOR . $node->__toString()] = $this->dirtree($path . DIRECTORY_SEPARATOR . $node->__toString());
                if (empty($list) === false) {
                    $result = array_merge_recursive($result, $list);
                }
            }
        }
        sort($result);
        return $result;
    }

    /**
     *
     * @param
     *
     */
    public function getTree() {
        return $this->tree;
    }

}
