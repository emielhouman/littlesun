<?php
include_once(__DIR__ . "/Db.php");

class TimeOffRequest
{
    public static function createTimeOff($userId, $startDate, $endDate, $startTime, $endTime, $reason)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("INSERT INTO time_off (user_id, date_start, date_end, start_time, end_time, reason) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $startDate, $endDate, $startTime, $endTime, $reason]);
    }

    public static function callInSick($userId, $sickDate, $sickTime, $reason)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("INSERT INTO time_off (user_id, date_start, date_end, start_time, end_time, is_sick, reason) VALUES (?, ?, ?, ?, ?, 1, ?)");
        $stmt->execute([$userId, $sickDate, $sickDate, $sickTime, $sickTime, $reason]);
    }

    public static function getTimeOffRequests($userId)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM time_off WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRequestsByLocation($locationId)
    {
        $conn = Db::getConnection();
        // Adjust the query to match the actual column names in your users table
        $stmt = $conn->prepare("SELECT time_off.*, CONCAT(users.firstname, ' ', users.lastname) as user_name FROM time_off JOIN users ON time_off.user_id = users.id WHERE users.location_id = ?");
        $stmt->execute([$locationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function approveRequest($requestId)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("UPDATE time_off SET approved = 1, declined = 0 WHERE id = ?");
        $stmt->execute([$requestId]);
    }

    public static function declineRequest($requestId)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("UPDATE time_off SET approved = 0, declined = 1 WHERE id = ?");
        $stmt->execute([$requestId]);
    }
}
?>

