<?php

class UserController extends Controller
{
    public function dashboard()
    {
        Auth::role(['user', 'faculty']);
        $this->view('user/dashboard');
    }
}
