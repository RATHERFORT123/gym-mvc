<?php

class AdminController extends Controller
{
    public function dashboard()
    {
        Auth::role(['admin']);
        $this->view('admin/dashboard');
    }
}
