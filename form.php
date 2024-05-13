<?php
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

if(isset($_POST['submit'])){
    $id = $_POST['ID'];
    $first_name = $_POST['First_name'];
    $last_name = $_POST['Last_name'];
    $age = $_POST['age'];
    $clubs = $_POST['clubs']; // Array of selected club IDs
    $tel = $_POST['tel'];
    $home = $_POST['home'];

    // Insert data into the club_registration table
    foreach ($clubs as $club_id) {
        $sql = "INSERT INTO club_registration (republic_id, first_name, last_name, age, club, tel, home) 
                VALUES ('$id', '$first_name', '$last_name', '$age', '$club_id', '$tel', '$home')";
        if (mysqli_query($conn, $sql)) {
            if(count($clubs) > 1){
                echo "<div class='alert alert-success'>You have a discount 15%. A lesson costs 850 tg now!</div>";
            } elseif($age < 10){
                echo "<div class='alert alert-success'>You have a discount 5%. A lesson costs 950 tg now!</div>";
            } else{
                echo "<div class='alert alert-success'>You successfully registered! A lesson costs 1000tg!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</div>";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">   
    <h1>Club registration</h1>
    </div>
    <div class="container mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="ID">Republic ID</label>
                <input type="text" name="ID" id="ID" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="First_name">First Name</label>
                <input type="text" name="First_name" id="First_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Last_name">Last Name</label>
                <input type="text" name="Last_name" id="Last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" name="age" id="age" class="form-control" min="1" required>
            </div>
            <div class="form-group">
                <label for="clubs">Clubs</label>
                <select name="clubs[]" id="clubs" class="form-control" multiple required>
                    <option value="Choreography">Choreography</option>
                    <option value="Music">Music</option>
                    <option value="Art">Art</option>
                    <option value="Robotic">Robotic</option>
                    <option value="Acting">Acting</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tel">Phone number</label>
                <input type="tel" name="tel" id="tel" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="home">Address</label>
                <input type="text" name="home" id="home" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            var id = document.getElementById("ID").value;
            var firstName = document.getElementById("First_name").value;
            var lastName = document.getElementById("Last_name").value;
            var age = document.getElementById("age").value;
            var tel = document.getElementById("tel").value;
            var home = document.getElementById("home").value;

            if (!/^\d{12}$/.test(id)) {
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
    </script>
</body>
</html>
