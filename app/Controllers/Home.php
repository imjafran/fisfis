<?php

namespace App\Controllers;

class Home extends BaseController
{
    function __construct(){
        $this->db = db_connect();
    }

    public function index()
    {
        return view('home/landing');
    } 

    public function help()
    {
        return view('home/help');
    } 

    public function terms()
    {
        return view('home/terms');
    } 

    public function send_message($slug = null)
    {
        $question = $this->db->table('questions')->getWhere(['slug' => $slug])->getRow();
        
        if(!$question) die('No question found');
        if(!$question->enabled) die('Messages are disabled');

        $user = $this->db->table('users')->getWhere(['id' => $question->user_id])->getRow();

        return view('home/send_message', ['question' => $question, 'user' => $user]);
    } 
}
