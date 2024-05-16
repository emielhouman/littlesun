<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$calendar = new Calendar(); // Create an instance of Calendar
$month = $calendar->month;
$year = $calendar->year;
$week = $calendar->week;
$view = $calendar->view;
$daysInMonth = $calendar->getDaysInMonth();
$firstDayOfMonth = $calendar->getFirstDayOfMonth();
$today = date('j');
$currentMonth = date('n');
$currentYear = date('Y');
$currentWeek = date('W');
$firstDayOfWeek = $calendar->getFirstDayOfWeek();

$tasks = Task::getAll();

// array with dates and task name
$tasksToShow = [
  "16-05-2024" => "milk delivery"
];


$members = Member::getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Sun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-gray-900 text-gray-200">
    <div class="flex">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div class="ml-72 px-14 py-10 flex-1">
            <h2 class="font-bold text-3xl pt-1">Hello World!</h2>
            <div class="mt-10">
                <div class="flex justify-between items-center mb-4">
                    <form method="post" class="flex space-x-2">
                        <button name="prev" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">&lt;</button>
                        <button name="next" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">&gt;</button>
                        <button name="today" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">Today</button>
                        <!-- Toggle View Button -->
                        <button name="toggleView" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
                            <?php echo $view === 'month' ? 'Week View' : 'Month View'; ?>
                        </button>
                    </form>
                    <div class="text-2xl font-semibold text-yellow-400">
                        <?php echo date('F Y', strtotime("$year-$month-01")); ?>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-px border-t border-l border-gray-700">
                    <?php
                    if ($view === 'month') {
                        for ($i = 0; $i < $firstDayOfMonth; $i++) {
                            echo '<div class="border-r border-b border-gray-700 bg-gray-800 h-24"></div>';
                        }
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $isToday = ($day == $today && $month == $currentMonth && $year == $currentYear);
                            $dayClass = $isToday ? 'bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center mx-auto' : 'bg-gray-800';
                            echo '<div class="border-r border-b border-gray-700 text-center py-2 h-24 cursor-pointer" data-day="' . $day . '" onclick="openModal(' . $day . ')">';
                            if ($isToday) {
                                echo '<div class="' . $dayClass . '">' . $day . '</div>';
                            } else {
                                echo $day;
                            }

                            

                            echo '</div>';
                        }
                    } else {
                        for ($i = 0; $i < 7; $i++) {
                            $dayTimestamp = strtotime("+$i day", $firstDayOfWeek);
                            $day = date('j', $dayTimestamp);
                            $isToday = ($day == $today && date('W', $dayTimestamp) == $currentWeek && date('Y', $dayTimestamp) == $currentYear);
                            $dayClass = $isToday ? 'bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center mx-auto' : 'bg-gray-800';
                            echo '<div class="border-r border-b border-gray-700 text-center py-2 h-[40rem] cursor-pointer" data-day="' . $day . '" onclick="openModal(' . $day . ')">';
                            if ($isToday) {
                                echo '<div class="' . $dayClass . '">' . $day . '</div>';
                            } else {
                                echo $day;
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-2xl font-bold mb-4 text-white">Add Event</h2>
            <form id="eventForm">
                <!-- Hidden eventDate input field -->
                <input type="hidden" id="eventDate" name="eventDate">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2 text-gray-200" for="member">Select a HUB Member</label>
                    <select class="w-full p-2 text-gray-200 rounded border border-gray-600 bg-gray-700" id="member" name="member">
                        <?php foreach ($members as $member) : ?>
                            <option value="<?php echo $member['id']; ?>"><?php echo $member['firstname'] . ' ' . $member['lastname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500 mr-2" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openModal(day) {
            document.getElementById('eventDate').value = `<?php echo date('F'); ?> ${day}, <?php echo $year; ?>`;
            document.getElementById('eventModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            e.preventDefault();
            closeModal();
        });
    </script>
</body>
</html>