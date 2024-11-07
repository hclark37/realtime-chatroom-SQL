A real-time chatroom web application that allows users to register, log in, and chat with others. This app uses PHP for server-side scripting, SQL to manage user logins and messages, and AJAX for real-time chat updates.

Features:
- User Registration & Login: Users can register with a unique username and password (one per IP). Existing users can log in using their credentials.
- Real-Time Messaging: AJAX is used to update the chat with new messages without requiring a page reload.
- User Session Management: Sessions are managed through cookies to keep users logged in across different pages. Users must be logged in to access the main chatroom; otherwise, they will be redirected to the login page.
- Message Persistence: All messages are stored in a MySQL database, allowing users to see a history of past messages even if they reload the page.

The project consists of the following PHP files:

- register.php: Page for new users to register.
- login.php: Page for existing users to log in.
- main.php: Main chatroom page where users can send and receive messages in real-time.
