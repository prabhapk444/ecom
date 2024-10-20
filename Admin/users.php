<?php
require("./db.php");

// Fetch users from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size:1.4rem;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Users Information</h2>
        <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search Users..." onkeyup="filterUsers()">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php foreach ($users as $user): ?>
                <tr id="user-<?php echo htmlspecialchars($user['id']); ?>">
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo htmlspecialchars($user['id']); ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterUsers() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const userTable = document.getElementById('userTable');
            const rows = userTable.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let rowContainsSearchText = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const cellText = cells[j].textContent.toLowerCase();
                        if (cellText.indexOf(searchInput) > -1) {
                            rowContainsSearchText = true;
                            break;
                        }
                    }
                }

                if (rowContainsSearchText) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "del_user.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Remove the user row from the table
                            const userRow = document.getElementById('user-' + userId);
                            userRow.parentNode.removeChild(userRow);
                        } else {
                            alert('Error deleting user: ' + response.message);
                        }
                    }
                };
                xhr.send("id=" + userId);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
