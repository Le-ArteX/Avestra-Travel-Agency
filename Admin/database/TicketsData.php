<?php
function insert_ticket($conn, $ticket_code, $route, $bus_class, $seat_count, $status) {
    $ticket_type = 'Bus';
    // Check for duplicate ticket_code
    $check = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE ticket_code = ?");
    $check->bind_param("s", $ticket_code);
    $check->execute();

    $check->fetch();
    $check->close();
    $stmt = $conn->prepare("INSERT INTO tickets (ticket_code, ticket_type, route, bus_class, seat_count, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $ticket_code, $ticket_type, $route, $bus_class, $seat_count, $status);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function update_ticket($conn, $id, $ticket_code, $route, $bus_class, $seat_count, $status) {
    // Check for duplicate ticket_code (excluding this ticket)
    $check = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE ticket_code = ? AND id != ?");
    $check->bind_param("si", $ticket_code, $id);
    $check->execute();
    $check->fetch();
    $check->close();
    $stmt = $conn->prepare("UPDATE tickets SET ticket_code=?, route=?, bus_class=?, seat_count=?, status=? WHERE id=?");
    $stmt->bind_param("sssssi", $ticket_code, $route, $bus_class, $seat_count, $status, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


