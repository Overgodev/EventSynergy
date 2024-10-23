<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header('Location: login.php');
    exit;
}
include 'db_connect.php';

// Fetch total number of Users and Admins
$total_users = $conn->query("SELECT COUNT(*) AS total FROM Users WHERE user_type = 'User'")->fetch_assoc()['total'];
$total_admins = $conn->query("SELECT COUNT(*) AS total FROM Users WHERE user_type = 'Admin'")->fetch_assoc()['total'];

// Fetch users list by roles
$users = $conn->query("SELECT * FROM Users WHERE user_type = 'User' ORDER BY user_id ASC");
$admins = $conn->query("SELECT * FROM Users WHERE user_type = 'Admin' ORDER BY user_id ASC");

// Fetch upcoming events for the calendar
$events = $conn->query("SELECT event_name, event_date FROM Events ORDER BY event_date ASC");
$eventData = [];
while ($row = $events->fetch_assoc()) {
    $eventData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Layout Styles */
        .container {
            display: flex;
            flex-direction: column;
        }
        .section {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Calendar Styles */
        .calendar-container {
            flex: 1;
            margin-top: 10px;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            padding: 10px;
        }
        .day {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            position: relative;
        }
        .event {
            background-color: #ffeb3b;
            color: #000;
            font-size: 12px;
            padding: 2px;
            margin-top: 5px;
            border-radius: 3px;
            text-align: center;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .today {
            background-color: #90caf9;
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
                align-items: flex-start;
            }
            .users-section {
                flex: 2;
            }
            .calendar-container {
                flex: 1;
                margin-left: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="admin_events.php">Manage Events</a>
        <a href="admin_users.php">Manage Users</a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Users Section (Left Column) -->
        <div class="users-section">
            <!-- Overview Section -->
            <div class="section">
                <h2>Overview</h2>
                <p>Total Users: <?php echo $total_users; ?></p>
                <p>Total Admins: <?php echo $total_admins; ?></p>
            </div>

            <!-- Admins Table -->
            <div class="section">
                <h2>Admin Users</h2>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                    <?php while ($admin = $admins->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $admin['user_id']; ?></td>
                        <td><?php echo $admin['username']; ?></td>
                        <td><?php echo $admin['email']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <!-- Users Table -->
            <div class="section">
                <h2>Regular Users</h2>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- Calendar Section (Right Column) -->
        <div class="calendar-container">
            <div class="section">
                <h2>Upcoming Events Calendar</h2>
                <div class="calendar" id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Calendar -->
    <script>
        // Event data from PHP
        const events = <?php echo json_encode($eventData); ?>;
        
        // Get today's date
        const today = new Date();

        // Generate the calendar for the current month
        function generateCalendar() {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            // Get the first day of the current month
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            const startDay = firstDay.getDay();
            const totalDays = lastDay.getDate();

            // Add empty cells before the start of the month
            for (let i = 0; i < startDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'day';
                calendar.appendChild(emptyCell);
            }

            // Add day cells
            for (let day = 1; day <= totalDays; day++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'day';
                dayCell.innerText = day;

                // Highlight today
                if (day === today.getDate()) {
                    dayCell.classList.add('today');
                }

                // Check for events on this day
                const currentDate = new Date(today.getFullYear(), today.getMonth(), day);
                events.forEach(event => {
                    const eventDate = new Date(event.event_date);
                    if (eventDate.toDateString() === currentDate.toDateString()) {
                        const eventDiv = document.createElement('div');
                        eventDiv.className = 'event';
                        eventDiv.innerText = event.event_name;
                        dayCell.appendChild(eventDiv);
                    }
                });

                calendar.appendChild(dayCell);
            }
        }

        // Initialize calendar on page load
        document.addEventListener('DOMContentLoaded', generateCalendar);
    </script>

</body>
</html>

