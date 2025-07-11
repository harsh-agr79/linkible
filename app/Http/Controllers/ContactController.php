<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\MetaTag;
use App\Models\ContactInfo;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        Contact::create($validatedData);

        return response()->json(['message' => 'Contact request submitted successfully.'], 201);
    }

    public function contactMeta(){
        return response()->json(['meta_tags' => MetaTag::where('slug', 'contact')->first(), 'contact_info' => ContactInfo::all()], 200);
    }
}
