<?php

class Controller
{
    protected function view($view, $data = [])
    {
        if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
            require_once __DIR__ . '/../models/Attendance.php';

            $attendanceModel = new Attendance();
            $data['attendanceMarkedToday'] =
                $attendanceModel->isMarked(
                    $_SESSION['user_id'],
                    date('Y-m-d')
                );

            // Check if user has an active subscription to allow attendance marking
            require_once __DIR__ . '/../models/Plan.php';
            $planModel = new Plan();
            $currentSub = $planModel->getCurrentSubscription($_SESSION['user_id']);

            $data['attendanceAllowed'] = ($currentSub && !empty($currentSub['end_date']) && strtotime($currentSub['end_date']) >= strtotime(date('Y-m-d')));
        } else {
            $data['attendanceMarkedToday'] = true;
            $data['attendanceAllowed'] = false;
        }

        extract($data);

        $path = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($path)) {
            die("View not found: " . $view);
        }

        require $path;
    }

    protected function model($model)
    {
        $path = __DIR__ . '/../models/' . $model . '.php';

        if (!file_exists($path)) {
            die("Model not found: " . $model);
        }

        require_once $path;
        return new $model;
    }
}
