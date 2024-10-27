<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'User') {
    header('Location: ../auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = htmlspecialchars(trim($_POST['username']));
    $new_email = htmlspecialchars(trim($_POST['email']));
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    // Update query
    $update_query = "UPDATE Users SET username = ?, email = ?" . ($new_password ? ", password = ?" : "") . " WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);

    if ($new_password) {
        $stmt->bind_param("sssi", $new_username, $new_email, $new_password, $user_id);
    } else {
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username; // Update session username
        $success_message = "Profile updated successfully.";
    } else {
        $error_message = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Header styling */
        header {
            background-color: #1e5bb7; /* Dark blue */
            color: white; /* White text */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }
        header h1 {
            margin: 0;
        }
        header p {
            margin: 0;
        }
        header a {
            color: white; /* White text for logout link */
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }
        header a:hover {
            text-decoration: underline;
        }

        /* Navigation bar styling */
        nav {
            background-color: #252525; /* Dark blue */
            display: flex;
            justify-content: center;
            padding: 10px 0;
            width: 100%;
            box-sizing: border-box;
        }
        nav a {
            color: white; /* White text */
            font-weight: bold;
            margin: 0 20px;
            text-decoration: none; /* No underline */
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }

        /* Body styling */
        body {
            background-color: #1e1e1e; /* Light grey */
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Form container styling */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            width: 100%;
        }

        .container {
            width: 400px;
            background-color: #333333; /* White background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
        }

        /* Heading styling */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #f4f4f4; /* Dark blue for headings */
        }

        /* Form group styles */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc; /* Light grey border */
            border-radius: 5px;
            font-size: 16px;
        }

        /* Button styling */
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #4caf50; /* Green */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Message styling */
        .success-message {
            color: green; /* Green for success messages */
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .error-message {
            color: red; /* Red for error messages */
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Profile</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="../auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="user.php">Browse Events</a>
        <a href="user_profile.php">Profile</a>
    </nav>

    <!-- Centered Profile Update Form -->
    <div class="form-container">
        <div class="container">
            <h2>Update Profile</h2>

            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="user_profile.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password (Leave blank if not changing)</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                </div>
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

</body>
</html>
