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
        } else {
            $data['attendanceMarkedToday'] = true;
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
