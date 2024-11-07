<?php
session_start();

$host = 'localhost';
$dbname = 'your_database';
$user = 'user';
$pass = 'password';

try {
	//pdo to work with database safer 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	//set to exception mode 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//check for failure
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //retrieve user data from database
    $statement = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $statement->execute([$username]);
    $user = $statement->fetch();
	//password comparison 
	//passwords are NOT hashed due to low security application and ease of changing user passwords or regrabbing them
	//if ($user && password_verify($password, $user['password'])) {
	//change line to ^^^ if you want to implement password hashing 
    if ($user && ($password == $user['password'])) { 
        //start user session and store id 
        $_SESSION['user_id'] = $user['id'];
        echo "Login successful!";
	    header("Location: main.php");
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php include('navbar.php');  // Include the navigation bar 
	if (!isset($_SESSION['user_id'])) {
		echo '<h2>Login</h2>
		<form action="login.php" method="POST">
			<label for="username">Username:</label>
			<input type="text" name="username" required><br><br>
			
			<label for="password">Password:</label>
			<input type="password" name="password" required><br><br>
			
			<button type="submit">Login</button>
		</form>';
	} else {
		echo '<p>You are already logged in.</p>';
	}
	?> 
</body>
</html>

