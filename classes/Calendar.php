<?php
class Calendar {
    public $month;
    public $year;
    public $week;
    public $view;

    public function __construct() {
        $this->initSession();
        $this->handlePost();
    }

    private function initSession() {
        if (!isset($_SESSION['view'])) {
            $_SESSION['view'] = 'month';
            $_SESSION['month'] = date('n');
            $_SESSION['year'] = date('Y');
            $_SESSION['week'] = date('W');
        }
        $this->month = $_SESSION['month'];
        $this->year = $_SESSION['year'];
        $this->week = $_SESSION['week'];
        $this->view = $_SESSION['view'];
    }

    private function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['toggleView'])) {
                $this->toggleView();
            }
            if (isset($_POST['prev'])) {
                $this->navigate(-1);
            }
            if (isset($_POST['next'])) {
                $this->navigate(1);
            }
            if (isset($_POST['today'])) {
                $this->resetDate();
            }
            $this->updateSession();
        }
    }

    private function toggleView() {
        if ($this->view === 'month') {
            $this->view = 'week';
            $this->week = $this->getWeekFromMonth();
        } else {
            $this->view = 'month';
        }
    }

    private function getWeekFromMonth() {
        // Set the week to the first week of the current month
        $date = new DateTime("{$this->year}-{$this->month}-01");
        return $date->format('W');
    }

    private function navigate($step) {
        if ($this->view === 'month') {
            $this->month += $step;
            if ($this->month < 1) {
                $this->month = 12;
                $this->year--;
            } elseif ($this->month > 12) {
                $this->month = 1;
                $this->year++;
            }
        } else {
            $this->week += $step;
            if ($this->week < 1) {
                $this->week = 52;
                $this->year--;
            } elseif ($this->week > 52) {
                $this->week = 1;
                $this->year++;
            }
        }
    }

    private function resetDate() {
        $this->month = date('n');
        $this->year = date('Y');
        $this->week = date('W');
    }

    private function updateSession() {
        $_SESSION['month'] = $this->month;
        $_SESSION['year'] = $this->year;
        $_SESSION['week'] = $this->week;
        $_SESSION['view'] = $this->view;
    }

    public function getDaysInMonth() {
        return cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    }

    public function getFirstDayOfMonth() {
        return date('w', strtotime("{$this->year}-{$this->month}-01"));
    }

    public function getCurrentDate() {
        return date('Y-m-d');
    }

    public function getFirstDayOfWeek() {
        return strtotime($this->year . "W" . str_pad($this->week, 2, '0', STR_PAD_LEFT));
    }
}
?>
