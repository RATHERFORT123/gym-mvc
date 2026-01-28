<?php

class AdminController extends Controller
{
    public function dashboard()
    {
        Auth::role(['admin']);
        $this->view('admin/dashboard');
    }

    public function users()
    {
        Auth::role(['admin']);
        $userModel = $this->model('User');
        $users = $userModel->getAllUsers();
        $this->view('admin/users', ['users' => $users]);
    }

    public function deleteUser($id)
    {
        Auth::role(['admin']);
        $this->model('User')->delete($id);
        header("Location: " . BASE_URL . "/admin/users");
        exit;
    }

    public function toggleStatus($id)
    {
        Auth::role(['admin']);
        $status = $_GET['status'] ?? 1;
        $this->model('User')->updateStatus($id, $status);
        header("Location: " . BASE_URL . "/admin/users");
        exit;
    }

    public function assignPlan($userId)
    {
        Auth::role(['admin']);
        $userModel = $this->model('User');
        $user = $userModel->getProfile($userId);
        $this->view('admin/assign_plan', ['user' => $user]);
    }

    public function storePlan()
    {
        Auth::role(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $data = [
                'user_id' => $_POST['user_id'],
                'plan_name' => $_POST['plan_name'],
                'workout_plan' => $_POST['workout_plan'],
                'diet_plan' => $_POST['diet_plan'],
                'assigned_by' => 'Admin',
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date']
             ];

             $this->model('Plan')->createPlan($data);
             header("Location: " . BASE_URL . "/admin/users");
             exit;
        }
    }
}
