<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "school";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $search_id = $_POST['search_id'];

    // Sanitize input
    $search_id = mysqli_real_escape_string($conn, $search_id);

    $sql = "SELECT * FROM form WHERE ID = '$search_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Club</th><th>Phone</th><th>Address</th></tr>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID"]. "</td>";
            echo "<td>" . $row["first_name"]. "</td>";
            echo "<td>" . $row["last_name"]. "</td>";
            echo "<td>" . $row["club"]. "</td>";
            echo "<td>" . $row["tel"]. "</td>";
            echo "<td>" . $row["address"]. "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search by ID</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search_id">Enter ID to search:</label>
        <input type="text" name="search_id" required>
        <input type="submit" value="Search">
    </form>
</body>
</html>