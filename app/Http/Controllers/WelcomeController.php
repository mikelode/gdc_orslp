<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 2/07/15
 * Time: 19:51
 */

namespace aidocs\Http\Controllers;


class WelcomeController extends Controller {
    public function index()
    {
        return view('welcome');
    }
} 