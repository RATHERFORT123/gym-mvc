<?php

class PlanController extends Controller
{
    public function index()
    {
        Auth::role(['user', 'faculty']);

        $planModel = $this->model('Plan');
        $plan = $planModel->getUserPlan($_SESSION['user_id']);

        $this->view('user/plans', ['plan' => $plan]);
    }
}
