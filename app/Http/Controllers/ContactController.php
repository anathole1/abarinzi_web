<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // For sending email
use App\Mail\ContactFormMail; // You'll create this Mailable
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous() . '#contact') // Redirect back to contact section
                ->withErrors($validator)
                ->withInput()
                ->with('contact_error', 'Please check the errors in the form.'); // General error
        }

        // If validation passes:
        $validatedData = $validator->validated();

        try {
            // Option 1: Send an email (Most common)
            // Make sure your .env mail settings are configured
            // Mail::to(config('mail.from.address'))->send(new ContactFormMail($validatedData));

            // Option 2: Store in database (Create a ContactMessage model and migration)
            // \App\Models\ContactMessage::create($validatedData);

            // For this example, we'll just simulate success
            // In a real app, uncomment and implement one of the options above

            return redirect(url()->previous() . '#contact')
                ->with('contact_success', 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.');

        } catch (\Exception $e) {
            // Log the error
            logger()->error('Contact form submission error: ' . $e->getMessage());

            return redirect(url()->previous() . '#contact')
                ->withInput()
                ->with('contact_error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}