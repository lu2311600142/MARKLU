<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    /**
     * index() - Display all announcements
     * 
     * Protected by AuthFilter (all logged-in users can access)
     */
    public function index()
    {
        $model = new AnnouncementModel();
        
        $data = [
            'title' => 'Announcements',
            'announcements' => $model->orderBy('created_at', 'DESC')->findAll()
        ];
        
        return view('announcements', $data);
    }
}