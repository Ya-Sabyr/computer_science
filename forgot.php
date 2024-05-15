<?php
function caesarEncrypt($str, $sdv) {
    if (!ctype_alpha($str)) {
        return $str;
    }
    $sdv = $sdv % 26;
    if ($sdv < 0) {0
        $sdv += 26;
    }
    $output = '';
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        $ascii = ord($str[$i]);
        if (ctype_upper($str[$i])) {
            $output .= chr((($ascii - 65 + $sdv) % 26) + 65);
        } else {
            $output .= chr((($ascii - 97 + $sdv) % 26) + 97);
        }
    }
    return $output;
}

function caesarDecrypt($str, $sdv) {
    return caesarEncrypt($str, 26 - $sdv);
}

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
        $decrypted_password = caesarDecrypt($password, 4)

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
