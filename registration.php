<?php
// Start session to store CAPTCHA value
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "school";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"]; 
    $confirmPassword = $_POST["confirm_password"]; 
    $email = $_POST["email"];
    $captchaInput = $_POST["captcha"];

    // Check if admin checkbox is checked
    $admin = isset($_POST['admin']) ? 1 : 0;

    // Validate inputs
    if(empty($username) || empty($password) || empty($confirmPassword) || empty($email) || empty($captchaInput)) {
        echo "All fields are required.";
        exit;
    }

    // Verify CAPTCHA
    if ($_SESSION['captcha'] != $captchaInput) {
        echo "CAPTCHA validation failed. Please try again.";
        exit;
    }

    // Check if passwords match
    if($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }

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

    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    // Encrypt password using AES encryption
    $encryptedPassword = caesarEncrypt($password, 4);

    // Insert user data into the database
    $sql = "INSERT INTO authentication (user_name, password, email, admin) VALUES ('$username', '$encryptedPassword', '$email', '$admin')";

    if ($conn->query($sql) === TRUE) {  
        // Redirect to login page after successful registration
        header("location: login.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Generate random CAPTCHA value
$captchaValue = rand(1000, 9999);

// Store CAPTCHA value in session
$_SESSION['captcha'] = $captchaValue;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 300px;
            margin: 0 auto;
            margin-top: 100px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="captcha">CAPTCHA: <?php echo $captchaValue; ?></label>
            <input type="text" id="captcha" name="captcha" required>

            <label for="admin">Admin?</label>
            <input type="checkbox" id="admin" name="admin">

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
