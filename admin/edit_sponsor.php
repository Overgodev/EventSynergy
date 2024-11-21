<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: /auth/login.php');
    exit;
}

include '../config/db_connect.php';

// Check if sponsor_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_sponsors.php');
    exit;
}

$sponsor_id = intval($_GET['id']);

// Fetch the current sponsor details
$stmt = $conn->prepare("SELECT * FROM sponsors WHERE sponsor_id = ?");
$stmt->bind_param('i', $sponsor_id);
$stmt->execute();
$result = $stmt->get_result();
$sponsor = $result->fetch_assoc();

if (!$sponsor) {
    header('Location: admin_sponsors.php');
    exit;
}

// Update sponsor details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sponsor_name = $_POST['sponsor_name'];
    $contact_person = $_POST['contact_person'];
    $contact_email = $_POST['contact_email'];
    $phone_number = $_POST['phone_number'];

    $update_stmt = $conn->prepare("UPDATE sponsors SET sponsor_name = ?, contact_person = ?, contact_email = ?, phone_number = ? WHERE sponsor_id = ?");
    $update_stmt->bind_param('ssssi', $sponsor_name, $contact_person, $contact_email, $phone_number, $sponsor_id);

    if ($update_stmt->execute()) {
        header('Location: admin_sponsors.php?message=Sponsor updated successfully');
        exit;
    } else {
        $error = "Failed to update sponsor. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sponsor</title>
    <link rel="stylesheet" href="/assets/css/style2.css">
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Edit Sponsor</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="/auth/logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/admin/admin.php">Dashboard</a>
        <a href="/admin/admin_events.php">Manage Events</a>
        <a href="/admin/admin_users.php">Manage Users</a>
        <a href="/admin/admin_sponsors.php">Manage Sponsors</a>
        <a href="/admin/admin_locations.php">Manage Locations</a>
    </nav>

    <!-- Form to Edit Sponsor -->
    <div class="container">
        <div class="section">
            <h2>Edit Sponsor</h2>
            <form method="POST">
                <label for="sponsor_name">Sponsor Name:</label><br>
                <input type="text" id="sponsor_name" name="sponsor_name" value="<?php echo htmlspecialchars($sponsor['sponsor_name']); ?>" required><br><br>

                <label for="contact_person">Contact Person:</label><br>
                <input type="text" id="contact_person" name="contact_person" value="<?php echo htmlspecialchars($sponsor['contact_person']); ?>" required><br><br>

                <label for="contact_email">Contact Email:</label><br>
                <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($sponsor['contact_email']); ?>" required><br><br>

                <label for="phone_number">Phone Number:</label><br>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($sponsor['phone_number']); ?>" required><br><br>

                <!-- Update and Cancel Buttons -->
                <a type="submit" class="add-btn">Update</a>
                <a href="admin_sponsors.php" class="add-btn">Cancel</a>
            </form>

            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript for Auto-Formatting Phone Number -->
    <script>
        const phoneInput = document.getElementById('phone_number');

        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-numeric characters
            if (value.length > 3 && value.length <= 6) {
                value = `${value.slice(0, 3)}-${value.slice(3)}`;
            } else if (value.length > 6) {
                value = `${value.slice(0, 3)}-${value.slice(3, 6)}-${value.slice(6, 10)}`;
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
