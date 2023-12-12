<?php
    require_once "Member.php";
    require_once "MemberRepository.php";
    require_once "Mysql.php";

    $mysqlObj = new Mysql();
    $pdo = $mysqlObj->getConnection();
    $memberRepository = new MemberRepository($pdo);

    $action = $_REQUEST['action'];
	if(!$_REQUEST['action']) {
		throw new Exception("Invalid Action");
	}

    switch ($action) {
        case 'saveMember':
            saveMember($memberRepository);
            break;
        case 'getMembers':
            getMembers($memberRepository);
            break;
        
        default:
            $default = array("isSuccess"=>false,"msg"=>"invalid action");
            echo json_encode($default);
            break;
    }

    function saveMember($memberRepository)
    {
        $parent = isset($_POST['parent']) ? $_POST['parent'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
    
        if (empty($name)) {
            die("Name cannot be empty.");
        }
    
        $newMember = $memberRepository->insertMember($parent, $name);
        if ($newMember) {
            $html = "<li data-id='{$newMember->getId()}'>{$newMember->getName()}";
            $html .= "<ul></ul>"; // Child container
            $html .= "</li>";
            echo json_encode(['parentId' => $parent, 'html' => $html]);
        } else {
            echo json_encode(['error' => 'Failed to insert data into the database.']);
        }
    }
    

    function getMembers($memberRepository){

        // Fetch all members
        $members = $memberRepository->getAllMembers();

        echo json_encode(array_map(function($member) {
            return [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'parentId' => $member->getParentId(),
            ];
        }, $members));
    }

?>