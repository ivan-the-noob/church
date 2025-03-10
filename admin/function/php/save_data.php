    <?php
    // Include database connection
    include('../../../db.php');

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $amount = $_POST['amount']; // Capture the amount input

        // Prepare SQL query
        $sql = "INSERT INTO donations (name, type, amount) VALUES (?, ?, ?)";

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssd", $name, $type, $amount);  // 's' for string, 'd' for decimal

            // Execute query
            if ($stmt->execute()) {
                // Success response
                echo json_encode(['status' => 'success', 'message' => 'Donation added successfully!']);
            } else {
                // Error response
                echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
            }

            // Close statement
            $stmt->close();
        } else {
            // Error preparing statement
            echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
        }

        // Close connection
        $conn->close();
    }
    ?>
