<?php
// Include database connection
include('../includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $num_people = $_POST['num_people'];
    $reservation_time = $_POST['reservation_time'];
    $table_number = $_POST['table_number'];

    // Prepare and execute the query to insert the reservation
    $sql = "INSERT INTO reservations (name, email, num_people, reservation_time, table_number) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $name, $email, $num_people, $reservation_time, $table_number);

    if ($stmt->execute()) {
        // Update table status
        $update_sql = "UPDATE rooms SET status = 'reserved' WHERE table_number = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $table_number);
        $update_stmt->execute();

        //echo "Table reserved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
