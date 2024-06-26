<?php
include_once(__DIR__ . "/Db.php");

class SickRequest
{
    public static function callInSick($userId, $startDate, $endDate)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("INSERT INTO sick (user_id, date_start, date_end) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $startDate, $endDate]);
    }

    public static function getSickRequests($userId)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT * FROM sick WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSickRequestsByLocation($locationId)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT sick.*, users.firstname, users.lastname
            FROM sick
            JOIN users ON sick.user_id = users.id
            JOIN locations ON users.location_id = locations.id
            WHERE locations.id = ?
        ");
        $stmt->execute([$locationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSickDays($userId, $startDate, $endDate)
    {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            SELECT SUM(DATEDIFF(LEAST(date_end, ?) + 1, GREATEST(date_start, ?))) AS sick_days
            FROM sick
            WHERE user_id = ?
            AND date_start <= ?
            AND date_end >= ?
        ");
        $stmt->execute([$endDate, $startDate, $userId, $endDate, $startDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['sick_days'] ?? 0;
    }
}
?>
