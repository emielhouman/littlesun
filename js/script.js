{
    const $calendarCmds = {};

    const handleLocationForm = () => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        $popup.innerHTML = `<div class="w-1/3 p-12 bg-white rounded-lg relative">
        <h3 class="font-bold text-2xl pb-8">HUB Location:</h3>
        <form class="flex flex-col gap-6" action="" method="post">
            <input class="w-full px-3.5 py-2.5 col-span-2 text-lg rounded border-gray-300 border-2" type="text" id="location" name="location" placeholder="Location name..." required>
            <div class="flex justify-between gap-4">
                <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-gray-100 border-gray-300 border-2 close__btn" name="cancel" type="button">Cancel</button>
                <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-yellow-400 border-yellow-400 border-2" name="create" type="submit">Create</button>
            </div>
        </form>
        <button class="w-7 h-7 m-3.5 p-px absolute right-0 top-0 font-bold opacity-25 leading-none close__btn">
            <img class="w-full h-auto" src="./assets/icons/close.svg" alt="close icon">
        </button></div>`;

        const $closeBtns = $popup.querySelectorAll('.close__btn');

        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });
    };

    const handleTaskForm = () => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        $popup.innerHTML = `<div class="w-1/3 p-12 bg-white rounded-lg relative">
        <h3 class="font-bold text-2xl pb-8">HUB Task:</h3>
        <form class="flex flex-col gap-6" action="" method="post">
            <input class="w-full px-3.5 py-2.5 col-span-2 text-lg rounded border-gray-300 border-2" type="text" id="task" name="task" placeholder="Task name...">
            <div class="flex justify-between gap-4">
                <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-gray-100 border-gray-300 border-2 close__btn" name="cancel" type="button">Cancel</button>
                <button class="w-1/2 px-3 py-2.5 font-extrabold uppercase rounded cursor-pointer bg-yellow-400 border-yellow-400 border-2" name="create" type="submit">Create</button>
            </div>
        </form>
        <button class="w-7 h-7 m-3.5 p-px absolute right-0 top-0 font-bold opacity-25 leading-none close__btn">
            <img class="w-full h-auto" src="./assets/icons/close.svg" alt="close icon">
        </button></div>`;

        const $closeBtns = $popup.querySelectorAll('.close__btn');

        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });
    };

    const handleManagerForm = () => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        const $closeBtns = $popup.querySelectorAll('.close__btn');

        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });
    };

    const handleLocationSelect = () => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        const $closeBtns = $popup.querySelectorAll('.close__btn');
        const $selectBtns = $popup.querySelectorAll('.select__btn');

        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });

        $selectBtns.forEach($selectBtn => {
            $selectBtn.addEventListener('click', () => {
                selectLocation($selectBtn);
            });
        });
    }

    const selectLocation = ($selectedLocation) => {
        const locationId = $selectedLocation.dataset.id;
        const locationName = $selectedLocation.querySelector(`.location`).textContent;

        const $locationInput = document.querySelector('.location__input');
        $locationInput.value = locationId;

        const $locationSelect = document.querySelector('.location__select');
        $locationSelect.innerHTML = `<span class="text-lg">${locationName}</span>
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
            <path d="M325,650c-43.7,0-86.18-8.63-126.27-25.66-38.66-16.42-73.41-39.9-103.29-69.78-29.88-29.88-53.36-64.63-69.78-103.29C8.63,411.18,0,368.7,0,325s8.63-86.18,25.66-126.27c16.42-38.66,39.9-73.41,69.78-103.29,29.88-29.88,64.63-53.36,103.29-69.78C238.82,8.63,281.3,0,325,0s86.18,8.63,126.27,25.66c38.66,16.42,73.41,39.9,103.29,69.78,29.88,29.88,53.36,64.63,69.78,103.29,17.03,40.09,25.66,82.57,25.66,126.27s-8.63,86.18-25.66,126.27c-16.42,38.66-39.9,73.41-69.78,103.29-29.88,29.88-64.63,53.36-103.29,69.78-40.09,17.03-82.57,25.66-126.27,25.66ZM325,56.09c-71.5,0-138.94,28.06-189.89,79.02-50.95,50.95-79.02,118.39-79.02,189.89s28.06,138.94,79.02,189.89c50.95,50.95,118.39,79.02,189.89,79.02s138.94-28.06,189.89-79.02c50.95-50.95,79.02-118.39,79.02-189.89s-28.06-138.94-79.02-189.89c-50.95-50.95-118.39-79.02-189.89-79.02Z" />
            <path d="M364.66,325l64.21-64.21c10.95-10.95,10.95-28.71,0-39.66-10.95-10.95-28.71-10.95-39.66,0l-64.21,64.21-64.21-64.21c-10.95-10.95-28.71-10.95-39.66,0-10.95,10.95-10.95,28.71,0,39.66l64.21,64.21-64.21,64.21c-10.95,10.95-10.95,28.71,0,39.66,5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21l64.21-64.21,64.21,64.21c5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21c10.95-10.95,10.95-28.71,0-39.66l-64.21-64.21Z" />
        </svg>`;

        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'visible';
        $popup.style.display = 'none';
    };

    const handleManagerSelect = () => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        const $closeBtns = $popup.querySelectorAll('.close__btn');
        const $selectBtns = $popup.querySelectorAll('.select__btn');

        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });

        $selectBtns.forEach($selectBtn => {
            $selectBtn.addEventListener('click', () => {
                selectManager($selectBtn);
            });
        });
    };

    const selectManager = ($selectedManager) => {
        const managerId = $selectedManager.dataset.id;
        const managerName = $selectedManager.querySelector(`.manager`).textContent;

        const $managerInput = document.querySelector('.manager__input');
        $managerInput.value = managerId;

        const $managerSelect = document.querySelector('.manager__select');
        $managerSelect.innerHTML = `<span class="text-lg">${managerName}</span>
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 650 650">
            <path d="M325,650c-43.7,0-86.18-8.63-126.27-25.66-38.66-16.42-73.41-39.9-103.29-69.78-29.88-29.88-53.36-64.63-69.78-103.29C8.63,411.18,0,368.7,0,325s8.63-86.18,25.66-126.27c16.42-38.66,39.9-73.41,69.78-103.29,29.88-29.88,64.63-53.36,103.29-69.78C238.82,8.63,281.3,0,325,0s86.18,8.63,126.27,25.66c38.66,16.42,73.41,39.9,103.29,69.78,29.88,29.88,53.36,64.63,69.78,103.29,17.03,40.09,25.66,82.57,25.66,126.27s-8.63,86.18-25.66,126.27c-16.42,38.66-39.9,73.41-69.78,103.29-29.88,29.88-64.63,53.36-103.29,69.78-40.09,17.03-82.57,25.66-126.27,25.66ZM325,56.09c-71.5,0-138.94,28.06-189.89,79.02-50.95,50.95-79.02,118.39-79.02,189.89s28.06,138.94,79.02,189.89c50.95,50.95,118.39,79.02,189.89,79.02s138.94-28.06,189.89-79.02c50.95-50.95,79.02-118.39,79.02-189.89s-28.06-138.94-79.02-189.89c-50.95-50.95-118.39-79.02-189.89-79.02Z" />
            <path d="M364.66,325l64.21-64.21c10.95-10.95,10.95-28.71,0-39.66-10.95-10.95-28.71-10.95-39.66,0l-64.21,64.21-64.21-64.21c-10.95-10.95-28.71-10.95-39.66,0-10.95,10.95-10.95,28.71,0,39.66l64.21,64.21-64.21,64.21c-10.95,10.95-10.95,28.71,0,39.66,5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21l64.21-64.21,64.21,64.21c5.48,5.48,12.65,8.21,19.83,8.21s14.35-2.74,19.83-8.21c10.95-10.95,10.95-28.71,0-39.66l-64.21-64.21Z" />
        </svg>`;

        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'visible';
        $popup.style.display = 'none';
    };

    const navigateCalendar = (e) => {
        const $command = e.target.dataset.command;
        if ($command === 'prev' || $command === 'next') {
            $calendarCmds[$command] = ($calendarCmds[$command] ?? 0) + 1;
        } else if ($command === 'today') {
            $calendarCmds.prev = 0;
            $calendarCmds.next = 0;
        }

        let formData = new FormData();
        formData.append('calendarCmds', JSON.stringify($calendarCmds));

        fetch('./schedule.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const $calendar = document.querySelector('.calendar');
                    const $calendarTitle = document.querySelector('.calendar__title');

                    $calendar.innerHTML = result.calendar;
                    $calendarTitle.textContent = result.calendarTitle;
                } else {
                    console.error('failed to update calendar:', result.message);
                }
            });
    };

    const handleCalendarForm = (e = null) => {
        const $body = document.body;
        const $popup = document.getElementById('popup-scrn');
        $body.style.overflow = 'hidden';
        $popup.style.display = 'flex';

        if (e && e.target && e.target.dataset.date) {
            const $dateInput = $popup.querySelector('input[name="date"]');
            $dateInput.value = e.target.dataset.date;
        }

        const $closeBtns = $popup.querySelectorAll('.close__btn');
        $closeBtns.forEach($closeBtn => {
            $closeBtn.addEventListener('click', () => {
                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            });
        });

        const $createBtn = $popup.querySelector('.create__btn');
        $createBtn.removeEventListener('click', handleCreateClick);
        $createBtn.addEventListener('click', handleCreateClick);
    };

    const handleCreateClick = (e) => {
        e.preventDefault();
        const $popup = document.getElementById('popup-scrn');
        createCalendarTask($popup);
    };

    const createCalendarTask = ($popup) => {
        const $taskInput = $popup.querySelector('select[name="task"]').value;
        const $userInput = $popup.querySelector('select[name="user"]').value;
        const $dateInput = $popup.querySelector('input[name="date"]').value;
        const $startTimeInput = $popup.querySelector('input[name="start_time"]').value;
        const $endTimeInput = $popup.querySelector('input[name="end_time"]').value;

        let formData = new FormData();
        formData.append('task_id', $taskInput);
        formData.append('user_id', $userInput);
        formData.append('date', $dateInput);
        formData.append('start_time', $startTimeInput);
        formData.append('end_time', $endTimeInput);

        fetch('./ajax/save_schedule_task.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                console.log("success:", result);
                const $body = document.body;

                const $calendarCell = document.querySelector(`.calendar__cell[data-date="${result.body.date}"]`);
                if ($calendarCell) $calendarCell.innerHTML += createTaskElement(result.body);

                $body.style.overflow = 'visible';
                $popup.style.display = 'none';
            })
            .catch(error => {
                console.error('error:', error);
            });
    };

    const createTaskElement = (task) => {
        const taskName = task.task_name;
        const taskStartTime = task.start_time;
        const taskEndTime = task.end_time;

        const $result = `<div class="w-full p-1 px-1.5 flex flex-col self-start text-xs rounded border-2 border-yellow-400/75 bg-yellow-400/50">
        <span class="font-bold text-xs">${taskName}</span>
        <span class="font-light pt-0.5">${taskStartTime} - ${taskEndTime}</span></div>`;

        return $result;
    };

    const init = () => {
        const $taskBtn = document.querySelector('.task__btn');
        const $managerSelect = document.querySelector('.manager__select');
        const $managerBtn = document.querySelector('.manager__btn');
        const $locationSelect = document.querySelector('.location__select');
        const $locationBtn = document.querySelector('.location__btn');
        const $calendarNavs = document.querySelectorAll('.calendar__nav');
        const $calendarBtn = document.querySelector('.calendar__btn');
        const $calendarCells = document.querySelectorAll('.calendar__cell');

        if ($locationBtn) {
            $locationBtn.addEventListener('click', handleLocationForm);
        };

        if ($managerBtn) {
            $managerBtn.addEventListener('click', handleManagerForm);
        };

        if ($taskBtn) {
            $taskBtn.addEventListener('click', handleTaskForm);
        };

        if ($managerSelect && $managerSelect.getAttribute('disabled') === null) {
            $managerSelect.addEventListener('click', handleManagerSelect);
        };

        if ($locationSelect && $locationSelect.getAttribute('disabled') === null) {
            $locationSelect.addEventListener('click', handleLocationSelect);
        };

        if ($calendarNavs) {
            $calendarNavs.forEach($calendarNav => {
                $calendarNav.addEventListener('click', e => navigateCalendar(e));
            });
        };

        if ($calendarBtn) {
            $calendarBtn.addEventListener('click', handleCalendarForm);
        };

        if ($calendarCells) {
            $calendarCells.forEach($calendarCell => {
                $calendarCell.addEventListener('click', e => handleCalendarForm(e));
            });
        };
    }

    init();
}