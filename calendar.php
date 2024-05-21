<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");
include_once(__DIR__ . "/classes/TimeOffRequest.php");
include_once(__DIR__ . "/classes/SickRequest.php");

// Check if the user is a manager and get their location_id
$managerId = $_SESSION['user_id'];
$managerLocation = Manager::getManagerWithId($managerId);
$locationId = $managerLocation['location_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        TimeOffRequest::approveRequest($requestId);
    } elseif ($action === 'decline') {
        TimeOffRequest::declineRequest($requestId);
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$timeOffRequests = TimeOffRequest::getRequestsByLocation($locationId);
$sickRequests = SickRequest::getSickRequestsByLocation($locationId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Time Off Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex w-screen h-screen">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="flex flex-col flex-1 ml-72 px-14 py-10">
            <h2 class="font-extrabold text-4xl pb-12">Manage Time-Off Requests</h2>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">User</th>
                        <th class="px-4 py-2 border">Start Date</th>
                        <th class="px-4 py-2 border">End Date</th>
                        <th class="px-4 py-2 border">Start Time</th>
                        <th class="px-4 py-2 border">End Time</th>
                        <th class="px-4 py-2 border">Reason</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timeOffRequests as $request): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['user_name']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_start']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_end']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['start_time']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['end_time']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['reason']); ?></td>
                        <td class="px-4 py-2 border">
                            <?php
                            if ($request['approved'] == 1) {
                                echo 'Approved';
                            } elseif ($request['declined'] == 1) {
                                echo 'Declined';
                            } else {
                                echo 'Pending';
                            }
                            ?>
                        </td>
                        <td class="px-4 py-2 border">
                            <?php if ($request['approved'] == 0 && $request['declined'] == 0): ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="px-4 py-2 bg-green-400 text-white rounded">Approve</button>
                                </form>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="decline" class="px-4 py-2 bg-red-400 text-white rounded">Decline</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2 class="font-extrabold text-4xl py-12">Manage Sick Requests</h2>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">User</th>
                        <th class="px-4 py-2 border">Start Date</th>
                        <th class="px-4 py-2 border">End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sickRequests as $request): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['firstname']) . ' ' . htmlspecialchars($request['lastname']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_start']); ?></td>
                        <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_end']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
