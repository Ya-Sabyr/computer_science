<?php
session_start();

// Check if form is submitted
if(isset($_POST['submit'])){
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

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if(empty($username) || empty($password)) {
        echo "<div class='alert alert-danger mt-3' role='alert'>Username and password are required.</div>";
        exit;
    }

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);

    // Check if the username exists in the authentication table
    $sql = "SELECT * FROM authentication WHERE user_name = '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Decrypt the stored password
        $encryptionKey = "2b7e151628aed2a6abf7158809cf4f3c"; // Change this to your own secret key
        $iv = substr($encryptionKey, 0, 16); // Use the first 16 bytes of the encryption key as the IV
        $decryptedPassword = openssl_decrypt($row['password'], "aes-256-cbc", $encryptionKey, 0, $iv);
        // Verify password
        if($password === $decryptedPassword) {
            $_SESSION['loggedin'] = true;

            // Check if the user is an admin
            if(isset($_POST['admin']) && $_POST['admin'] == 'on' && $row['admin'] == '1') {
                $_SESSION['admin'] = true;
                header("location: admin.php");
                exit; // Ensure script termination after redirect
            } else {
                header("location: form.php");
                exit; // Ensure script termination after redirect
            }
        } else {
            echo "<div class='alert alert-danger mt-3' role='alert'>Invalid password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3' role='alert'>User '$username' is not registered.</div>";
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Validation</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-5">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="admin" class="form-check-input" id="admin">
            <label class="form-check-label" for="admin">Admin?</label>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Log in</button>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
