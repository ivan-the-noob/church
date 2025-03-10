<?php
    function getLatestWaitingBookings($conn) {
        $query = "SELECT * FROM booking WHERE status = 'Waiting' ORDER BY id DESC LIMIT 3";
        $result = $conn->query($query);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getLatestDeclinedBookings($conn) {
    $query = "SELECT * FROM booking WHERE status = 'Declined' ORDER BY id DESC LIMIT 3";
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
    }

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $bookings_waiting = getLatestWaitingBookings($conn);

        $bookings_declined = getLatestDeclinedBookings($conn);

    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
?>