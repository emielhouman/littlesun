<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");
include_once(__DIR__ . "/classes/TimeOffRequest.php");
include_once(__DIR__ . "/classes/SickRequest.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; // Assuming you have user sessions

    if (isset($_POST['startDate'], $_POST['endDate'], $_POST['startTime'], $_POST['endTime'], $_POST['reason'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $reason = $_POST['reason'];
        TimeOffRequest::createTimeOff($userId, $startDate, $endDate, $startTime, $endTime, $reason);
    } elseif (isset($_POST['sickStartDate'], $_POST['sickEndDate'])) {
        $sickStartDate = $_POST['sickStartDate'];
        $sickEndDate = $_POST['sickEndDate'];
        SickRequest::callInSick($userId, $sickStartDate, $sickEndDate);
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$timeOffRequests = TimeOffRequest::getTimeOffRequests($_SESSION['user_id']);
$sickRequests = SickRequest::getSickRequests($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Sun</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex w-screen h-screen">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="flex flex-col flex-1 ml-72 px-14 py-10">
            <!-- Time-off Button -->
            <div class="flex justify-center mt-10">
                <button id="timeOffBtn" class="px-6 py-2 text-white bg-yellow-400 rounded">Request Time Off</button>
                <button id="callSickBtn" class="px-6 py-2 ml-4 text-white bg-yellow-400 rounded">Call in Sick</button>
            </div>

            <!-- Time-off Modal -->
            <div id="timeOffModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-8 rounded-lg w-1/2">
                    <h2 class="font-extrabold text-4xl pb-12">Request Time Off</h2>
                    <form id="timeOffForm" method="POST">
                        <div class="mb-4">
                            <label for="startDate" class="block text-lg font-semibold">Start Date:</label>
                            <input type="date" id="startDate" name="startDate" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="endDate" class="block text-lg font-semibold">End Date:</label>
                            <input type="date" id="endDate" name="endDate" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="startTime" class="block text-lg font-semibold">Start Time:</label>
                            <input type="time" id="startTime" name="startTime" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="endTime" class="block text-lg font-semibold">End Time:</label>
                            <input type="time" id="endTime" name="endTime" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="reason" class="block text-lg font-semibold">Reason:</label>
                            <textarea id="reason" name="reason" class="w-full border-2 border-gray-300 p-2 rounded"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="closeTimeOffModal" class="px-4 py-2 bg-gray-300 rounded mr-4">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-yellow-400 text-white rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sick Call Modal -->
            <div id="callSickModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-8 rounded-lg w-1/2">
                    <h2 class="text-2xl font-bold mb-4">Call in Sick</h2>
                    <form id="callSickForm" method="POST">
                        <div class="mb-4">
                            <label for="sickStartDate" class="block text-lg font-semibold">Start Date:</label>
                            <input type="date" id="sickStartDate" name="sickStartDate" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="mb-4">
                            <label for="sickEndDate" class="block text-lg font-semibold">End Date:</label>
                            <input type="date" id="sickEndDate" name="sickEndDate" class="w-full border-2 border-gray-300 p-2 rounded">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="closeCallSickModal" class="px-4 py-2 bg-gray-300 rounded mr-4">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-yellow-400 text-white rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display Time-off Requests -->
            <div class="mt-10 px-10">
                <h2 class="text-2xl font-bold mb-4">Time-Off Requests</h2>
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Start Date</th>
                            <th class="px-4 py-2 border">End Date</th>
                            <th class="px-4 py-2 border">Start Time</th>
                            <th class="px-4 py-2 border">End Time</th>
                            <th class="px-4 py-2 border">Reason</th>
                            <th class="px-4 py-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($timeOffRequests as $request): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_start']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_end']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['start_time']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['end_time']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['reason']); ?></td>
                            <td class="px-4 py-2 border">
                                <?php
                                if ($request['approved'] == 1) {
                                    echo '<span class="text-green-500">Approved</span>';
                                } elseif ($request['declined'] == 1) {
                                    echo '<span class="text-red-500">Declined</span>';
                                } else {
                                    echo '<span class="text-yellow-500">Pending</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Display Sick Requests -->
            <div class="mt-10 px-10">
                <h2 class="text-2xl font-bold mb-4">Sick Requests</h2>
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Start Date</th>
                            <th class="px-4 py-2 border">End Date</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sickRequests as $request): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_start']); ?></td>
                            <td class="px-4 py-2 border"><?php echo htmlspecialchars($request['date_end']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('timeOffBtn').addEventListener('click', function() {
            document.getElementById('timeOffModal').classList.remove('hidden');
        });

        document.getElementById('callSickBtn').addEventListener('click', function() {
            document.getElementById('callSickModal').classList.remove('hidden');
        });

        document.getElementById('closeTimeOffModal').addEventListener('click', function() {
            document.getElementById('timeOffModal').classList.add('hidden');
        });

        document.getElementById('closeCallSickModal').addEventListener('click', function() {
            document.getElementById('callSickModal').classList.add('hidden');
        });

        document.getElementById('timeOffForm').addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });

        document.getElementById('callSickForm').addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    </script>
</body>

</html>
