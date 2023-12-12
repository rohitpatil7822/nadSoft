<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
class MemberRepository
{
    private $pdo;

    // Constructor
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all members from the database
    public function getAllMembers()
    {
        $stmt = $this->pdo->query("SELECT * FROM Members");
        $members = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $member = new Member($row['Id'], $row['Name'], $row['ParentId']);
            $members[] = $member;
        }

        return $members;    
    }

    public function fetchMembers($parentId = 0)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Members WHERE ParentId " . ($parentId == 0 ? "IS NULL" : "= :parentId"));
    
        if ($parentId != 0) {
            $stmt->bindParam(":parentId", $parentId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $html = "";
        foreach ($members as $member) {
            $memberObj = new Member($member['Id'], $member['Name'], $member['ParentId']);
            $html .= "<li data-id='{$memberObj->getId()}'>{$memberObj->getName()}";
            $html .= "<ul>" . $this->fetchMembers($memberObj->getId()) . "</ul>"; // Recursive call
            $html .= "</li>";
        }
    
        return $html;
    }


    // Insert member into the database
    public function insertMember($parent, $name)
    {   
        $values ="(CreatedDate, Name, ParentId) VALUES (NOW(), '$name', $parent)";

        if ($parent == 0) {
            $values ="(CreatedDate, Name) VALUES (NOW(), '$name')";
        }

        $sql = "INSERT INTO Members ".$values;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Check if data was inserted successfully
        if ($stmt->rowCount() > 0) {
            // Return the newly added member
            $lastInsertId = $this->pdo->lastInsertId();
            return new Member($lastInsertId, $name, $parent);
        } else {
            // Data insertion failed
            return null;
        }
    }
}

?>
