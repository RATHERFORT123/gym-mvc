<?php

class HomeController extends Controller
{
    public function index()
    {
        // If user is already logged in, redirect to dashboard? 
        // Or let them see the landing page. Let's let them see the landing page.
        $this->view('home/index');
    }
}
