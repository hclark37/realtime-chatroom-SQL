<?php
session_start();

//1800 seconds until timeout of user session
$timeout = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}

$_SESSION['last_activity'] = time();

//check login
if (!isset($_SESSION['user_id'])) {
	echo "You must be logged in to post a message.";
    die("You must be logged in to post a message.");
}

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

//message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id']; //get user ID
	$_SESSION['last_activity'] = time();
    $statement = $pdo->prepare('INSERT INTO messages (user_id, message) VALUES (?, ?)');
    $statement->execute([$user_id, $message]);
    echo json_encode(['status' => 'success']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch messages from the database
    $statement = $pdo->query('SELECT messages.message, messages.created_at, users.username FROM messages JOIN users ON messages.user_id = users.id ORDER BY messages.created_at DESC');
    $messages = [];
    
    while ($row = $statement->fetch()) {
        $messages[] = [
			'created_at' => htmlspecialchars($row['created_at']),
            'username' => htmlspecialchars($row['username']),
            'message' => htmlspecialchars($row['message']),
        ];
    }

    // Return the messages as a JSON response
    echo json_encode($messages);
    exit();
}
?>
