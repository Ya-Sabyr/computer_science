<?php
$encryptionKey = "2b7e151628aed2a6abf7158809cf4f3c"; // Replace "your_encryption_key_here" with your actual encryption key

$servername = "localhost";
$username = "root";
$password = "";
$database = "school";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];

    // Retrieve the encrypted password from the database
    $sql = "SELECT password FROM authentication WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $encrypted_password = $row['password'];

        // Decrypt the password
        $decrypted_password = openssl_decrypt($encrypted_password, "aes-256-cbc", $encryptionKey, 0, $encryptionKey);

        // Display the decrypted password to the user
        echo "Your password is: $decrypted_password";
    } else {
        echo "No such user exists";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional custom styles */
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
            <label for="email">Email:</label>
            <input type="text" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Retrieve Password</button>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
