<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function add(Request $request) {
        if(!$request->user()) {
            return redirect()->route('/login');
        }
       
        $request->validate([
            'target' => 'required',
            'message'=> 'required'
        ]);
        
        Announcement::create([
            'message' => $request->message,
            'target' => $request->target,
            'user_id' => $request->user()->id
        ]);


        return redirect()->back()->with('success', 'Announcement has been made successfully.');
    }

    /**
     * Display form to insert announcement
     */

    public function insert() {
        return view('pages.announcement');
    }
}
