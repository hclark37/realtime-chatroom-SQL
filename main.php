
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();  
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Messenger</title>
</head>
<body>
    <?php include('navbar.php');  // Include the navigation bar ?>
    <h2>Post a Message</h2>
    <form id="message-form">
        <textarea name="message" id="message" required></textarea><br><br>
        <button type="submit">Post Message</button>
    </form>

    <h3>Messages:</h3>
    <div id="messages"></div>

    <script>
		function submitMessage(event) {
			event.preventDefault();  //prevent the form from submitting normally

			var messageText = document.getElementById('message').value;

			//create data to send with POST 
			var formData = 'message=' + encodeURIComponent(messageText);

			//send the message via POST request using fetch
			fetch('messages.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'  //URL encoded format 
				},
				body: formData  //the message data we want to send
			})
			.then(response => response.json())  //parse response as JSON
			.then(data => {
				if (data.status === 'success') {
					//clear message input field if message was successfully sent
					document.getElementById('message').value = '';
					
					//reload messages after successful submission
					loadMessages();
				}
			})
			.catch(error => {
				console.error('Error:', error);  //log errors that occur
			});
		}

		//function to load messages from the server via GET request
		function loadMessages() {
			//fetch messages from 'messages.php' using GET method
			fetch('messages.php', { method: 'GET' })
			.then(response => response.json())  //parse as JSON
			.then(messages => {
				var messagesHtml = '';  //initialize variables to hold the HTML for messages

				//loop through messages and create HTML content for each one
				messages.forEach(function(message) {
					messagesHtml += '<p>[' + message.created_at + '] <strong>' + message.username + ':</strong> ' + message.message + '</p>';
				});

				//update content of message container with the new messages
				document.getElementById('messages').innerHTML = messagesHtml;
			})
			.catch(error => {
				console.error('Error:', error);  //Log any errors that occur
			});
		}

		//set up event listener when message form is submitted
		document.getElementById('message-form').addEventListener('submit', submitMessage);

		//refresh the messages every 5 seconds 
		setInterval(loadMessages, 5000);

		//load messages immediately when the page is loaded
		loadMessages();
	</script>

</body>
</html>
