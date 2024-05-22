<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

// Redirect based on user_id
$userId = $_SESSION['user_id'];
if ($userId == 1) {
    header("Location: tasks.php");
    exit();
} elseif (!in_array($userId, [3])) {
    header("Location: timeoff.php");
    exit();
}

// Check if the user is a manager and get their location_id
$managerId = $_SESSION['user_id'];
$managerLocation = Member::getMemberWithId($managerId);
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
$members = Member::getAll($locationId);

$report = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report_type'], $_GET['user_id'], $_GET['start_date'], $_GET['end_date'])) {
    $reportType = $_GET['report_type'];
    $userId = $_GET['user_id'];
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];

    if ($reportType === 'hours_worked') {
        if ($userId === 'all') {
            $report = [];
            foreach ($members as $member) {
                $hoursWorked = ScheduledTask::getHoursWorked($member['id'], $startDate, $endDate);
                $report[] = [
                    'name' => $member['firstname'] . ' ' . $member['lastname'],
                    'hours_worked' => $hoursWorked
                ];
            }
        } else {
            $member = Member::getMemberWithId($userId);
            $hoursWorked = ScheduledTask::getHoursWorked($userId, $startDate, $endDate);
            $report = [
                'name' => $member['firstname'] . ' ' . $member['lastname'],
                'hours_worked' => $hoursWorked
            ];
        }
    } elseif ($reportType === 'holiday_hours') {
        if ($userId === 'all') {
            $report = [];
            foreach ($members as $member) {
                $holidayHours = TimeOffRequest::getApprovedTimeOffHours($member['id'], $startDate, $endDate);
                $report[] = [
                    'name' => $member['firstname'] . ' ' . $member['lastname'],
                    'holiday_hours' => $holidayHours
                ];
            }
        } else {
            $member = Member::getMemberWithId($userId);
            $holidayHours = TimeOffRequest::getApprovedTimeOffHours($userId, $startDate, $endDate);
            $report = [
                'name' => $member['firstname'] . ' ' . $member['lastname'],
                'holiday_hours' => $holidayHours
            ];
        }
    } elseif ($reportType === 'sick_days') {
        if ($userId === 'all') {
            $report = [];
            foreach ($members as $member) {
                $sickDays = SickRequest::getSickDays($member['id'], $startDate, $endDate);
                $report[] = [
                    'name' => $member['firstname'] . ' ' . $member['lastname'],
                    'sick_days' => $sickDays
                ];
            }
        } else {
            $member = Member::getMemberWithId($userId);
            $sickDays = SickRequest::getSickDays($userId, $startDate, $endDate);
            $report = [
                'name' => $member['firstname'] . ' ' . $member['lastname'],
                'sick_days' => $sickDays
            ];
        }
    }
}
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
        <div class="flex flex-1 ml-72 px-14 py-10 relative">
            <div class="w-1/2 h-full p-4 bg-white overflow-y-auto">
                <h2 class="font-extrabold text-2xl pb-4">Manage Time-Off Requests</h2>
                
                <!-- Time-Off Requests -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <?php if (empty($timeOffRequests)): ?>
                        <p class="text-gray-500">There are no time-off requests at the moment.</p>
                    <?php else: ?>
                        <?php foreach ($timeOffRequests as $request): ?>
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <p class="font-bold">Member: <?php echo htmlspecialchars($request['user_name']); ?></p>
                            <p>Date Range: <?php echo htmlspecialchars($request['date_start']); ?> - <?php echo htmlspecialchars($request['date_end']); ?></p>
                            <p>Reason: <?php echo htmlspecialchars($request['reason']); ?></p>
                            <p>Status: 
                                <?php
                                if ($request['approved'] == 1) {
                                    echo '<span class="text-green-500">Approved</span>';
                                } elseif ($request['declined'] == 1) {
                                    echo '<span class="text-red-500">Declined</span>';
                                } else {
                                    echo '<span class="text-yellow-500">Pending</span>';
                                }
                                ?>
                            </p>
                            <div class="mt-2">
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
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <h2 class="font-extrabold text-2xl py-4">Sick Notifications</h2>

                <!-- Sick Requests -->
                <div class="grid grid-cols-1 gap-4">
                    <?php if (empty($sickRequests)): ?>
                        <p class="text-gray-500">There are no sick notifications at the moment.</p>
                    <?php else: ?>
                        <?php foreach ($sickRequests as $request): ?>
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <p class="font-bold">Employee: <?php echo htmlspecialchars($request['firstname']) . ' ' . htmlspecialchars($request['lastname']); ?></p>
                            <p>Date Range: <?php echo htmlspecialchars($request['date_start']); ?> - <?php echo htmlspecialchars($request['date_end']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="absolute bottom-0 right-0 w-1/2 h-2/3 p-12 bg-white rounded-lg shadow-lg relative">
                <h3 class="font-bold text-2xl pb-8">Generate Report</h3>
                <form class="flex flex-col gap-6" action="" method="GET">
                    <label class="block">
                        <span class="text-gray-700">Select Report Type:</span>
                        <select name="report_type" class="form-select mt-1 block w-full">
                            <option value="hours_worked">Hours Worked</option>
                            <option value="sick_days">Sick Days</option>
                            <option value="holiday_hours">Holiday Hours</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Member:</span>
                        <select name="user_id" class="form-select mt-1 block w-full">
                            <option value="all">All Workers</option>
                            <?php foreach ($members as $member): ?>
                                <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['firstname']) . ' ' . htmlspecialchars($member['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">Start Date:</span>
                        <input type="date" name="start_date" class="form-input mt-1 block w-full" required>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">End Date:</span>
                        <input type="date" name="end_date" class="form-input mt-1 block w-full" required>
                    </label>
                    <div class="flex justify-between gap-4">
                        <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-gray-100 border-gray-300 border-2" type="button">Cancel</button>
                        <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-yellow-400 border-yellow-400 border-2" type="submit">Generate</button>
                    </div>
                </form>

                <!-- Report Results -->
                <?php if ($report && $reportType === 'sick_days'): ?>
                <div class="mt-12 p-12 bg-white rounded-lg shadow-lg relative w-full">
                    <h4 class="font-bold text-xl pb-4">Report Results</h4>
                    <?php if ($userId === 'all'): ?>
                        <table class="min-w-full bg-white border rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2 border">Member:</th>
                                    <th class="px-4 py-2 border">Sick Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($report as $entry): ?>
                                <tr>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($entry['name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($entry['sick_days']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="bg-white p-4 rounded-lg shadow-md mt-4 w-full">
                            <p class="font-bold">Member: <?php echo htmlspecialchars($report['name']); ?></p>
                            <p>Sick Days: <?php echo htmlspecialchars($report['sick_days']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
