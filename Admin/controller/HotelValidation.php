<?php
function validateHotelForm(array $post): array {
    $errors = [];

    $name     = trim($post['name'] ?? '');
    $location = trim($post['location'] ?? '');
    $rating   = (string)($post['rating'] ?? '');
    $rooms    = (string)($post['rooms'] ?? '');
    $status   = (string)($post['status'] ?? '');

    if ($name === '') $errors[] = "Hotel name is required.";
    if ($location === '') $errors[] = "Location is required.";

    if (!in_array($rating, ['1','2','3','4','5'], true)) {
        $errors[] = "Please select a rating (1–5 stars).";
    }

    if (!ctype_digit($rooms) || (int)$rooms <= 0) {
        $errors[] = "Rooms must be a positive number.";
    }

    if (!in_array($status, ['Active','Inactive'], true)) {
        $errors[] = "Invalid status selected.";
    }

    return $errors;
}
