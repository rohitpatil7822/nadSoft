<?php

class Member
{
    private $id;
    private $name;
    private $parentId;

    // Constructor
    public function __construct($id, $name, $parentId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
    }

    // Getter methods
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParentId()
    {
        return $this->parentId;
    }
}

?>
