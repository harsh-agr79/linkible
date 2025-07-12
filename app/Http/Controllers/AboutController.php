<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\ContactInfo;

class AboutController extends Controller
{
    public function getAboutUs(Request $request){
        $about = About::first();
        $contact = ContactInfo::all();

        // Attach as a new attribute
        $about->contact_info = $contact;

        return response()->json($about, 200);
    }
}
