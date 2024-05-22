<?php
session_start();
include_once(__DIR__ . "/bootstrap.php");

$today = date('d-m-Y');
$currentWeek = date('W');
$currentMonth = date('n');
$currentYear = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
    $calendarCmds = json_decode($_POST['calendarCmds'], true);

    foreach ($calendarCmds as $command => $count) {
        if ($command === 'prev') {
            $currentMonth -= $count;
            while ($currentMonth <= 0) {
                $currentYear--;
                $currentMonth += 12;
            }
        } else if ($command === 'next') {
            $currentMonth += $count;
            while ($currentMonth > 12) {
                $currentYear++;
                $currentMonth -= 12;
            }
        } else if ($command === 'today') {
            $currentYear = date('Y');
            $currentMonth = date('n');
        }
    }

    $calendar = createCalendar($currentMonth, $currentYear);
    $calendarTitle = date('F Y', strtotime("$currentYear-$currentMonth-01"));

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'calendar' => $calendar, 'calendarTitle' => $calendarTitle]);
    exit;
}

function createCalendar($currentMonth, $currentYear)
{
    $firstMonthDay = date('N', strtotime("$currentYear-$currentMonth-01"));
    $lastMonthDay = date('t', strtotime("$currentYear-$currentMonth-01"));
    $calendarCells = calculateCalendarCells($firstMonthDay, $lastMonthDay);

    $prevYear = $currentYear;
    $prevMonth = $currentMonth - 1;
    if ($prevMonth <= 0) {
        $prevYear--;
        $prevMonth = 12;
    }

    $nextYear = $currentYear;
    $nextMonth = $currentMonth + 1;
    if ($nextMonth > 12) {
        $nextYear++;
        $nextMonth = 1;
    }

    $lastDayOfPrevMonth = date('t', strtotime("$prevYear-$prevMonth-01"));

    $html = '';
    $tasks = ScheduledTask::getAll();

    for ($i = 0; $i < $calendarCells; $i++) {
        if ($i < $firstMonthDay - 1) {
            $day = $lastDayOfPrevMonth - $firstMonthDay + $i + 2;
            $date = sprintf('%04d-%02d-%02d', $prevYear, $prevMonth, $day);
            $isCurrentMonth = false;
            $html .= createCalendarCells($day, $date, $tasks, $isCurrentMonth);
        } elseif ($i >= $firstMonthDay - 1 + $lastMonthDay) {
            $day = $i - $firstMonthDay + 2 - $lastMonthDay;
            $date = sprintf('%04d-%02d-%02d', $nextYear, $nextMonth, $day);
            $isCurrentMonth = false;
            $html .= createCalendarCells($day, $date, $tasks, $isCurrentMonth);
        } else {
            $day = $i - $firstMonthDay + 2;
            $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
            $isCurrentMonth = true;
            $html .= createCalendarCells($day, $date, $tasks, $isCurrentMonth);
        }
    }

    return $html;
}

function calculateCalendarCells($firstMonthDay, $lastMonthDay)
{
    $monthDays = ($lastMonthDay + $firstMonthDay) - 1;
    $calendarCells = $monthDays + (7 - $monthDays % 7) % 7;
    return $calendarCells;
}

function createCalendarCells($day, $date, $tasks, $isCurrentMonth)
{
    $cellClass = $isCurrentMonth ? 'text-black/75 bg-white cursor-pointer overflow-auto calendar__cell' : 'text-black/25 bg-gray-100';
    $calendarCell = '<div class="h-28 p-1.5 flex flex-col items-end gap-1.5 text-xs border border-gray-200 ' . $cellClass . '" data-date="' . $date . '">
    <span class="w-8 p-0.5 px-1 flex items-center ' . ($date === date('Y-m-d') ? 'justify-center font-extrabold text-white bg-yellow-400 rounded-full' : 'justify-end') . '">' . $day . '</span>';

    foreach ($tasks as $task) {
        $taskDate = $task['date'];
        $taskStartTime = substr($task['start_time'], 0, 5);
        $taskEndTime = substr($task['end_time'], 0, 5);
        $taskDetails = Task::getTaskWithId($task['task_id']);

        if ($taskDate === $date) {
            $calendarCell .= '<div class="w-full p-1 px-1.5 flex flex-col self-start text-xs rounded border-2 border-yellow-400/75 bg-yellow-400/50">
            <span class="font-bold text-xs">' . $taskDetails['name'] . '</span>
            <span class="font-light pt-0.5">' . $taskStartTime . ' - ' . $taskEndTime . '</span></div>';
        };
    };

    $calendarCell .= '</div>';
    return $calendarCell;
}

$tasks = Task::getAll();
$members = Member::getAll();
$calendar = createCalendar($currentMonth, $currentYear);

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
    <div class="flex w-full relative">
        <?php include_once(__DIR__ . "/nav.inc.php") ?>
        <div id="popup-scrn" class="w-screen h-screen items-center justify-center absolute z-10 top-0 left-0 bg-black/50" style="display: none;">
            <div class="w-7/12 p-14 bg-white rounded-lg relative">
                <h3 class="font-bold text-2xl pb-8">Schedule HUB Task:</h3>
                <form id="schedule-form" class="w-full grid grid-cols-2 gap-x-12 gap-y-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-lg" for="task">HUB Task:</label>
                        <select class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="task" name="task" required>
                            <option value="">Select a HUB Task</option>
                            <?php foreach ($tasks as $task) : ?>
                                <option value="<?php echo $task['id'] ?>"><?php echo $task['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-lg" for="user">HUB Member:</label>
                        <select class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="user" name="user" required>
                            <option value="">Select a HUB Member</option>
                            <?php foreach ($members as $member) : ?>
                                <option value="<?php echo $member['id'] ?>"><?php echo $member['firstname'] . " " . $member['lastname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="font-semibold text-lg" for="date">Date:</label>
                        <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="date" name="date" type="date" value="<?php echo date('Y-m-d') ?>" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="font-semibold text-lg" for="start_time">From:</label>
                            <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="start_time" name="start_time" type="time" value="09:00" required>
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="font-semibold text-lg" for="end_time">Till:</label>
                            <input class="w-full h-12 px-2.5 text-lg rounded border-2 border-gray-300" id="end_time" name="end_time" type="time" value="10:30" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between gap-12 col-span-2">
                        <button class="w-full p-2.5 font-extrabold uppercase rounded cursor-pointer bg-yellow-400 border-yellow-400 border-2 create__btn" type="button">Create</button>
                    </div>
                </form>
                <button class="w-7 h-7 m-3.5 p-px absolute right-0 top-0 font-bold opacity-25 leading-none close__btn">
                    <img class="w-full h-auto" src="./assets/icons/close.svg" alt="close icon">
                </button>
            </div>
        </div>
        <div class="ml-72 px-14 py-10 flex-1">
            <div class="grid grid-cols-3 mb-8">
                <div class="justify-start text-4xl font-extrabold uppercase calendar__title">
                    <?php echo date('F Y', strtotime("$currentYear-$currentMonth-01")); ?>
                </div>
                <div class="flex items-center justify-self-center self-end rounded border-2 border-gray-200">
                    <button class="w-24 p-1 text-sm font-semibold uppercase border-r" data-mode="day">Day</button>
                    <button class="w-24 p-1 text-sm font-semibold uppercase border-x" data-mode="week">Week</button>
                    <button class="w-24 p-1 text-sm font-semibold uppercase border-l bg-gray-100" data-mode="month">Month</button>
                </div>
                <div class="flex justify-end gap-2">
                    <div class="flex gap-1">
                        <button class="h-full px-2.5 font-semibold tracking-wide uppercase rounded border-2 border-gray-200 calendar__nav" data-command="prev">&lt;</button>
                        <button class="h-full px-4 font-semibold tracking-wide uppercase rounded border-2 border-gray-200 calendar__nav" data-command="today">Today</button>
                        <button class="h-full px-2.5 font-semibold tracking-wide uppercase rounded border-2 border-gray-200 calendar__nav" data-command="next">&gt;</button>
                    </div>
                    <button class="px-4 rounded border-2 border-yellow-500 bg-yellow-400 calendar__btn">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
                            <path d="M325,650c-42.3,0-83.52-7.98-122.51-23.72-40.43-16.32-76.62-40.28-107.55-71.22-30.93-30.94-54.9-67.12-71.22-107.55C7.98,408.52,0,367.3,0,325s7.98-83.52,23.72-122.51c16.32-40.43,40.28-76.62,71.22-107.55s67.12-54.9,107.55-71.22C241.48,7.98,282.7,0,325,0s83.52,7.98,122.51,23.72c40.43,16.32,76.62,40.28,107.55,71.22,30.93,30.94,54.9,67.12,71.22,107.55,15.74,38.99,23.72,80.21,23.72,122.51s-7.98,83.52-23.72,122.51c-16.32,40.43-40.28,76.62-71.22,107.55-30.94,30.93-67.12,54.9-107.55,71.22-38.99,15.74-80.21,23.72-122.51,23.72ZM325,56.15c-72.14,0-139.74,27.87-190.36,78.49-50.61,50.61-78.49,118.22-78.49,190.36s27.87,139.74,78.49,190.36c50.61,50.61,118.22,78.49,190.36,78.49s139.74-27.87,190.36-78.49c50.61-50.61,78.49-118.22,78.49-190.36s-27.87-139.74-78.49-190.36c-50.61-50.61-118.22-78.49-190.36-78.49Z" />
                            <path d="M443.98,296.92h-90.9s0-90.9,0-90.9c0-15.51-12.57-28.08-28.08-28.08s-28.08,12.57-28.08,28.08v90.9s-90.9,0-90.9,0c-15.51,0-28.08,12.57-28.08,28.08s12.57,28.08,28.08,28.08h90.9s0,90.9,0,90.9c0,15.51,12.57,28.08,28.08,28.08s28.08-12.57,28.08-28.08v-90.9s90.9,0,90.9,0c15.51,0,28.08-12.57,28.08-28.08s-12.57-28.08-28.08-28.08Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 border border-gray-200 calendar">
                <?php echo $calendar ?>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>