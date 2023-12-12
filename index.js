function closePopup() {
    $("#addMemberPopup").hide();
}

function isValidName(name) {
    // Check if the name contains only letters and spaces
    return /^[a-zA-Z\s]+$/.test(name);
}



$(document).ready(function () {
    function appendMemberToTree(html, parentId) {
        var parentUl = $("#membersContainer").find("ul[data-id='" + parentId + "']");
        if (!parentUl.length) {
            parentUl = $("#membersContainer");
        }
        parentUl.append(html);
    }

    // Dynamically updating value in drop dropdown
    function refreshMemberDropdown() {
        var dropdown = $("#parentDropdown");
        dropdown.empty();
        dropdown.append('<option value="0">None</option>');
        $.ajax({
            type: "GET",
            url: "memberAction.php",
            data: { action: "getMembers" },
            dataType: "json",
            success: function (members) {
                if (Array.isArray(members)) {
                    members.forEach(function (member) {
                        dropdown.append('<option value="' + member.id + '">' + member.name + '</option>');
                    });
                } else {
                    console.error("Invalid response format for members:", members);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX request error:", status, error);
            }
        });
    }

    $("#addMemberBtn").click(function () {
        $("#addMemberPopup").show();
        $("#parentDropdown").val(0);
        $("#nameField").val("");
    });

    $("#saveChangesBtn").click(function () {
        var parent = $("#parentDropdown").val();
        var name = $("#nameField").val();

        if (name == "") {
            alert("Name cannot be empty.");
            return;
        }

        if (!isValidName(name)) {
            alert("Name must be a string.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "memberAction.php",
            data: { action: "saveMember", parent: parent, name: name },
            dataType: "json",
            success: function (response) {
                console.log("Ajax Response:", response);
                if (response.parentId !== undefined && response.html !== undefined) {
                    if (response.parentId === '0') {
                        $("#membersContainer").append(response.html);
                    } else {
                        var parentLi = $("li[data-id='" + response.parentId + "']");
                        var childUl = parentLi.children("ul");
                        if (childUl.length === 0) {
                            childUl = $("<ul></ul>");
                            parentLi.append(childUl);
                        }
                        childUl.append(response.html);
                    }
                    closePopup();
                    refreshMemberDropdown();
                } else {
                    alert("Failed to insert data into the database.");
                }
            }
        });
    });


    refreshMemberDropdown();
});




