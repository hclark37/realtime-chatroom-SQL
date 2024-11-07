<?php
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

//check for if post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
	//no hash for easier reset - not intended to be secure 
	//to hash, make it password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password = $_POST['password']; 
    $ip_address = $_SERVER['REMOTE_ADDR']; 

    // check if there's an existing user already with your IP address
	// prepare statement querying all entries with same ip 
    $statement = $pdo->prepare('SELECT * FROM users WHERE ip_address = ?');
    //execute that statement
	$statement->execute([$ip_address]);
	//compare total to 0-- should be 0 if creating new login 
    if ($statement->rowCount() > 0) {
		//kill if true, return error 
        die("Error: There is already a user registered from this IP address.");
    }

	//add new user
    $statement = $pdo->prepare('INSERT INTO users (username, password, ip_address) VALUES (?, ?, ?)');
    $statement->execute([$username, $password, $ip_address]);

    echo "Registration successful!";
	header("Location: login.php");

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>	
<body>
    <?php include('navbar.php');  // Include the navigation bar ?>
    
	<h2>Register</h2>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>
