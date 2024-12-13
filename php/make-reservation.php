<?php
// Database connection
$host = 'localhost';
$db = 'hotelmanagement';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guestID = $_POST['guest_id'];
    $roomNo = $_POST['room_no'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    try {
        // Check if the room exists and is available
        $checkRoomQuery = "SELECT Status FROM Room WHERE RoomNo = :roomNo";
        $stmt = $pdo->prepare($checkRoomQuery);
        $stmt->execute(['roomNo' => $roomNo]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room) {
            if ($room['Status'] === 'Available') {
                // Insert the reservation
                $insertReservationQuery = "
                    INSERT INTO Reservation (GuestID, RoomNo, CheckInDate, CheckOutDate)
                    VALUES (:guestID, :roomNo, :checkInDate, :checkOutDate)";
                $stmt = $pdo->prepare($insertReservationQuery);
                $stmt->execute([
                    'guestID' => $guestID,
                    'roomNo' => $roomNo,
                    'checkInDate' => $checkInDate,
                    'checkOutDate' => $checkOutDate
                ]);

                // Update room status to "Occupied"
                $updateRoomQuery = "UPDATE Room SET Status = 'Occupied' WHERE RoomNo = :roomNo";
                $stmt = $pdo->prepare($updateRoomQuery);
                $stmt->execute(['roomNo' => $roomNo]);

                $message = "Room reserved successfully!";
            } else {
                $message = "Room is not available for reservation.";
            }
        } else {
            $message = "Room not found.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation</title>
    <link rel="stylesheet" href="../styles/reservation.css"> <!-- CSS file linked here -->
</head>
<body>
    <div class="container">
    <section class="dashboard" style="background-image: url('../image/HotelPic-5.jpg');">
        <h1>Make a Reservation</h1>

        <?php if (isset($message)): ?>
            <div class="message <?= strpos($message, 'successfully') !== false ? '' : 'error'; ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="guest_id">Guest ID:</label>
            <input type="number" name="guest_id" id="guest_id" required><br><br>

            <label for="room_no">Room Number:</label>
            <input type="number" name="room_no" id="room_no" required><br><br>

            <label for="check_in_date">Check-In Date:</label>
            <input type="date" name="check_in_date" id="check_in_date" required><br><br>

            <label for="check_out_date">Check-Out Date:</label>
            <input type="date" name="check_out_date" id="check_out_date" required><br><br>

            <button type="submit">Reserve Room</button>
        </form>
    </div>
</body>
</html>
