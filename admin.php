<?php
// Check if the user is logged in
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    // Redirect to the login page if not logged in
    header("location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "school";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add or Change Record
if (isset($_POST['submit'])) {
    // Field validation
    $id = mysqli_real_escape_string($conn, $_POST['ID']);
    $republic_id = mysqli_real_escape_string($conn, $_POST['Republic_ID']);
    $first_name = mysqli_real_escape_string($conn, $_POST['First_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['Last_name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $clubs = mysqli_real_escape_string($conn, $_POST['clubs']);
    $tel = mysqli_real_escape_string($conn, $_POST['tel']);
    $home = mysqli_real_escape_string($conn, $_POST['home']);

    // Validate form fields using JavaScript before submission
    echo '<script>
            function validateForm() {
                var id = document.getElementById("Republic_ID").value;
                var firstName = document.getElementById("First_name").value;
                var lastName = document.getElementById("Last_name").value;
                var age = document.getElementById("age").value;
                var tel = document.getElementById("tel").value;
                var home = document.getElementById("home").value;

                if (!/^\D*\d{12}\D*$/.test(id)) {
                    alert("Republic ID must be a 12-digit number.");
                    return false;
                }                

                if (!/^[a-zA-Z]+$/.test(firstName) || !/^[a-zA-Z]+$/.test(lastName)) {
                    alert("First name and last name must contain only letters.");
                    return false;
                }

                if (age < 1) {
                    alert("Age must be a positive number.");
                    return false;
                }

                if (!/^\d{10}$/.test(tel)) {
                    alert("Phone number must be a 10-digit number.");
                    return false;
                }

                return true;
            }
        </script>';

    // SQL query
    $sql = "INSERT INTO club_registration (ID, republic_id, first_name, last_name, age, club, tel, home) 
            VALUES ('$id', '$republic_id', '$first_name', '$last_name', '$age', '$clubs', '$tel', '$home') 
            ON DUPLICATE KEY UPDATE republic_id = '$republic_id', first_name = '$first_name', last_name = '$last_name', age='$age', club = '$clubs', tel = '$tel', home = '$home'";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="alert alert-success" role="alert">Record added/changed successfully</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $sql . '<br>' . mysqli_error($conn) . '</div>';
    }
}

// Delete Record
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);

    $sql = "DELETE FROM club_registration WHERE ID ='$id'";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="alert alert-success" role="alert">Record deleted successfully</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting record: ' . mysqli_error($conn) . '</div>';
    }
}

$result = "";

// Search Functionality
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM club_registration WHERE last_name LIKE '%$search%' OR club LIKE '%$search%' OR tel LIKE '%$search%'";

    $result = mysqli_query($conn, $sql);
}

// Fetch all records from club_registration table if no search performed
if (!$result) {
    $sql = "SELECT * FROM club_registration";
    $result = mysqli_query($conn, $sql);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add/Change Record</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ID">ID:</label>
                    <input type="text" name="ID" id="ID" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="Republic_ID">Republic ID</label>
                    <input type="text" name="Republic_ID" id="Republic_ID" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="First_name">First Name:</label>
                    <input type="text" name="First_name" id="First_name" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Last_name">Last Name:</label>
                    <input type="text" name="Last_name" id="Last_name" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" class="form-control" min="1">
                </div>
                <div class="form-group col-md-6">
                    <label for="clubs">Club:</label>
                    <input type="text" name="clubs" id="clubs" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tel">Phone Number:</label>
                    <input type="tel" name="tel" id="tel" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="home">Address:</label>
                    <input type="text" name="home" id="home" class="form-control" required>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2 class="mt-5">Search Records</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mb-3">
            <div class="form-row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search by surname, club, or phone number">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info">Search</button>
                </div>
            </div>
        </form>

        <h2>Search Results</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Republic ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Club</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($result)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>".$row['ID']."</td>
                                        <td>".$row['republic_id']."</td>
                                        <td>".$row['first_name']."</td>
                                        <td>".$row['last_name']."</td>
                                        <td>".$row['club']."</td>
                                        <td>".$row['tel']."</td>
                                        <td>".$row['home']."</td>
                                        <td><a href='edit_record.php?id=".$row['ID']."' class='btn btn-sm btn-primary'>Edit</a></td>
                                        <td><a href='?delete=".$row['ID']."' class='btn btn-sm btn-danger'>Delete</a></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No records found</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
