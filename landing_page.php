<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: url('sunset.jpg') no-repeat;
  background-size: cover;
  text-align: center;
  padding-top: 50px;
}

.table-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

table {
  border-collapse: collapse;
  width: 80%;
}

th, td {
  border: 1px solid white;
  padding: 8px;
  text-align: center;
}

th {
  background-color: rgba(0, 0, 0, 0.5);
}

td {
  background-color: rgba(0, 0, 0, 0.3);
}

.title {
  text-align: left;
  width: 80%;
  margin-bottom: 20px;
}

button {
  font-family: 'Poppins', sans-serif;
  padding: 5px 10px;
  margin: 0 5px;
  cursor: pointer;
  background-color: #8B4513; 
  color: whitesmoke;
  border: none;
}
</style>
</head>
<body>

<?php

function connectDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "login";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Select users and display in table
function selectUsers() {
    $conn = connectDatabase();
    $sql = "SELECT id, firstName, lastName, email, password FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'>
                <div class='title'><h2>List of Users</h2></div>
                <table border='1'>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["firstName"] . "</td>
                    <td>" . $row["lastName"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["password"] . "</td>
                    <td>
                        <button onclick='editUser(" . $row["id"] . ", \"" . $row["firstName"] . "\", \"" . $row["lastName"] . "\", \"" . $row["email"] . "\", \"" . $row["password"] . "\")'>Edit</button>
                        <button onclick='deleteUser(" . $row["id"] . ")'>Delete</button>
                    </td>
                </tr>";
        }
        echo "</table></div>";
    } else {
        echo "<div class='table-container'><div class='title'><h2>List of Users</h2></div>0 results</div>";
    }

    $conn->close();
}

selectUsers();

?>

<!-- JavaScript functions for edit and delete actions -->
<script>
    function editUser(id, firstName, lastName, email, password) {
        const newFirstName = prompt("Enter new first name:", firstName);
        const newLastName = prompt("Enter new last name:", lastName);
        const newEmail = prompt("Enter new email:", email);
        const newPassword = prompt("Enter new password:", password);
        if (newFirstName && newLastName && newEmail && newPassword) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('firstName', newFirstName);
            formData.append('lastName', newLastName);
            formData.append('email', newEmail);
            formData.append('password', newPassword);

            fetch('edit_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function deleteUser(id) {
        if (confirm("Are you sure you want to delete this user?")) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('delete_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

</body>
</html>
