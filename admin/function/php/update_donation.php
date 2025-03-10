<?php
// Include database connection
include('../../../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];

    // Prepare SQL query to update the donation record
    $sql = "UPDATE donations SET name = ?, type = ?, amount = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdi", $name, $type, $amount, $id);

        if ($stmt->execute()) {
            // Respond with success message and updated data
            echo json_encode([
                'status' => 'success',
                'message' => 'Donation updated successfully!',
                'data' => [
                    'name' => $name,
                    'type' => $type,
                    'amount' => $amount
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update donation']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing SQL query']);
    }

    $conn->close();
}
?>
