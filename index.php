<?php
require_once "Member.php";
require_once "MemberRepository.php";
require_once "Mysql.php";

$sqlObj = new Mysql();
$pdo = $sqlObj->getConnection();
$memberRepository = new MemberRepository($pdo);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Tree</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            margin: 20px;
        }
    </style>
</head>

<body>
    <!-- Display Members -->
    <div id="membersContainer">
        <?php echo $memberRepository->fetchMembers(); ?>
    </div>

    <!-- Add Member Button -->
    <button id="addMemberBtn">Add Member</button>

    <!-- Add Member Popup -->
    <div id="addMemberPopup" style="display: none;">
        <div class="close-btn" onclick="closePopup()">X</div>
        <form id="addMemberForm">
            Parent:
            <select name="parent" id="parentDropdown">
                <option value="0">None</option>
                <!-- The dropdown options will be dynamically updated by JavaScript -->
            </select><br>
            Name:
            <input type="text" name="name" id="nameField"><br>
            <button type="button" id="saveChangesBtn">Save Changes</button>
            <button type="button" id="closeBtn" onclick="closePopup()">Close</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="index.js"></script>
</body>

</html>