<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../users/web/login.php');
    exit();
}
require '../../../db.php';  



include '../function/php/table-dashboard.php';  
$queryWaiting = "SELECT COUNT(*) AS waiting_count FROM booking WHERE status = 'Waiting'";
$queryDeclined = "SELECT COUNT(*) AS declined_count FROM booking WHERE status = 'Declined'";
$resultWaiting = $conn->query($queryWaiting);
$resultDeclined = $conn->query($queryDeclined);
$rowWaiting = $resultWaiting->fetch_assoc();
$rowDeclined = $resultDeclined->fetch_assoc();
$waitingCount = $rowWaiting['waiting_count'];
$declinedCount = $rowDeclined['declined_count'];

$query = "SELECT SUM(cost) AS payment_amount FROM booking WHERE status = 'Finished'";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $payment_amount = $row['payment_amount'];
} else {
    $payment_amount = 0; 
}

$email = $_SESSION['email'];
$query = "SELECT image_profile FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

$image = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row['image_profile'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amiel's MOM Event's Place</title>
<link rel="icon" href="../../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <div class="">   
        <div class="navbar flex-column  shadow-sm p-3 collapse show" id="navbar">
            <div class="navbar-header d-flex justify-content-between align-items-center w-100">
                <a class="navbar-brand d-none d-md-block logo-container" href="#">
                    <img src="../../../assets/logo.png" alt="Logo">
                </a>
            </div>
            <div class="navbar-links">
                <a href="dashboard.php" class="navbar-highlight">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
                <a href="calendar.php">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Calendar</span>
                </a>
                <a href="pending.php">
                    <i class="fa-solid fa-clock"></i>
                    <span>Pending Booking</span>
                </a>
                <a href="approve.php">
                   <i class="fas fa-clipboard-check"></i>
                    <span>Approved Booking</span>
                </a>
                <a href="on-going.php">
                    <i class="fa-solid fa-spinner"></i>
                    <span>On-going Booking</span>
                </a>
                <a href="refund.php">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Refund Pending</span>
                </a>
                <a href="cancel.php">
                    <i class="fa-solid fa-ban"></i>
                    <span>Cancelled Booking</span>
                </a>
                <a href="unavailable.php">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <span>Unavailable</span>
                </a>
                <a href="invoice.php">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Invoice</span>
                </a>
                <a href="reviews.php">
                    <i class="fa-solid fa-star"></i>
                    <span>Reviews</span>
                </a>
                <a href="history.php">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>History</span>
                </a>
                <div class="dropdown dropup">
                    <a href="#" class="dropdown-toggle" id="eventsListDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-list"></i>
                        <span>Events List</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="eventsListDropdown">
                        <li><a class="dropdown-item" href="events_list.php">Events List</a></li>
                        <li><a class="dropdown-item" href="package_list.php">Package List</a></li>
                        <li><a class="dropdown-item" href="extra.php">Extra</a></li>
                        <li><a class="dropdown-item" href="pax.php">Pax</a></li>
                        <li><a class="dropdown-item" href="dish.php">Dish</a></li>
                    </ul>
                </div>
                <a href="reports.php">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Reports & Analytics</span>
                </a>
                <a href="admin-user.php">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Manage Admin Users</span>
                </a>
                <div class="dropdown dropup">
                    <a href="#" class="dropdown-toggle" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>CMS</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="front_cms.php">Front CMS</a></li>
                        <li><a class="dropdown-item" href="scope_service.php">Scope Service</a></li>
                        <li><a class="dropdown-item" href="extras.php">Extras</a></li>
                        <li><a class="dropdown-item" href="features.php">Venue Features</a></li>
                        <li><a class="dropdown-item" href="terms_condition.php">Terms & Condition</a></li>
                    </ul>
                </div>
            </div>
        </div>
    
    </div>

    </div>
    </div>
    <div class="content flex-grow-1">
        <div class="header">
        <button class="btn btn-outline-secondary toggle-nav mt-1" id="toggleNavbarBtn">
            <i class="fa-solid fa-bars"></i>
        </button>
       

            <div class="profile-admin">
                <div class="dropdown">
                   <?php if (!empty($image)): ?>
                        <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../../../assets/profile/<?php echo htmlspecialchars($image); ?>" 
                                style="width: 40px; height: 40px; object-fit: cover;">
                        </button>
                    <?php endif; ?>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../users/function/authentication/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="container mt-4">
            <h3>Dashboard</h3>
            <div class="row">
                <div class="col-md-2">
                    <div class="card p-0 mt-2">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="col-md-12">
                                    <p class="mb-1">Total Sales</p>
                                    <h5>₱<?php echo number_format($payment_amount, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-0 mt-2">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="col-md-12">
                                    <p class="mb-1">Approved Books</p>
                                    <h5><?php echo $waitingCount; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-0 mt-2">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="col-md-12">
                                    <p class="mb-1">Cancelled Books</p>
                                    <h5><?php echo $declinedCount ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mt-2 d-flex justify-content-center">
                    <div class="chart-container">
                        <h5 class="chart-title">Weekly Sales</h5>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="col-md-5 mt-2  d-flex justify-content-center">
                    <div class="chart-container">
                        <h5 class="chart-title">Yearly Sales</h5>
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>

                <div id="chartContainer">
                    <canvas id="ratingPieChart" width="400" height="400"></canvas>
                </div>
              
                
               
                <div class="col-md-9 mt-4">
                    <h5>Approve Bookings</h5>
                    <div class="card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Event Date</th>
                                    <th>Event</th>
                                    <th>Pax</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings_waiting as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['events_date']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['guest_count']); ?></td>
                                        <td>₱100</td>
                                        <td class="bg-warnings"><?php echo htmlspecialchars($booking['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="approve.php" class="d-flex justify-content-center text-decoration-none mt-2">Show all</a>

                    <h5 class="mt-4">Cancelled Bookings</h5>
                    <div class="card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Event Date</th>
                                    <th>Event</th>
                                    <th>Pax</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings_declined as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['events_date']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['guest_count']); ?></td>
                                        <td>₱100</td>
                                        <td class="bg-declines"><?php echo htmlspecialchars($booking['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="cancel.php" class="d-flex justify-content-center text-decoration-none mt-2">Show all</a>
                </div>

                <!-- Reminders -->
              
                <div class="col-md-3 mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="mt-0 d-flex align-items-center mb-0">Reminders</h5>
                        <button class="reminder-notif"> <i class="fa-regular fa-bell fa-xl"></i></button>
                    </div>

                    <?php
                    require '../../../db.php'; 

                    $query = "SELECT full_name, event_type 
                    FROM booking
                    WHERE events_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";

                    $result = mysqli_query($conn, $query);

                    $pastEvents = [];

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $pastEvents[] = $row;   
                        }
                    } else {
                        $pastEvents = null;  
                    }
                    ?>

                    <div class="card">
                            <div class="card-header">
                                <p class="text-center fw-bold mb-0">Incoming Events</p>
                            </div>
                            <div class="card-body">
                                <?php if ($pastEvents): ?>
                                    <ul class="list-group">
                                        <?php foreach ($pastEvents as $event): ?>
                                            <li class="list-group-item">
                                                <strong>Full Name:<br></strong> <?php echo htmlspecialchars($event['full_name']); ?><br>
                                                <strong>Event Type:<br></strong> <?php echo htmlspecialchars($event['event_type']); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No events before 3 days ago.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- Add Reminder Modal -->
        <div class="modal fade" id="addReminderModal" tabindex="-1" aria-labelledby="addReminderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="../function/php/add_reminder.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addReminderModalLabel">Add Reminder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <select class="form-control" id="start_time" name="start_time" required>
                                    <?php
                                    for ($hour = 8; $hour <= 23; $hour++) {
                                        $formatted_time = date('h:i A', strtotime("$hour:00"));
                                        echo "<option value='" . date('H:i', strtotime("$hour:00")) . "'>$formatted_time</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="finish_time" class="form-label">Finish Time</label>
                                <input type="time" class="form-control" id="finish_time" name="finish_time" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Reminder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('start_time').addEventListener('change', function() {
                const startTime = this.value; 
                const [hour, minute] = startTime.split(':').map(Number);

                const finishTime = new Date();
                finishTime.setHours(hour + 5, minute); 

                const formattedFinishTime = finishTime.toTimeString().slice(0, 5);
                document.getElementById('finish_time').value = formattedFinishTime;
            });
        </script>





</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../function/script/daily-chart.js"></script>
<script src="../function/script/month-chart.js"></script>
<script src="../function/script/status.js"></script>
<script src="../function/script/pie-chart.js"></script>
<script src="../function/script/nav-toggle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</html>