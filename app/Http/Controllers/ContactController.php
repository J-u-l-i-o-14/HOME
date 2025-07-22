<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Envoi de l'email
        Mail::to('sergiodaklu12@gmail.com')->send(new ContactMessage($validated));

        return back()->with('success', 'Votre message a bien été envoyé !');
    }
}
