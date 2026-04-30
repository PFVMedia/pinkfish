<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendContactRequest;
use App\Mail\ContactFormMail;
use App\Models\ContentBlock;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Site/Contact', [
            'page' => Page::findBySlug('contact'),
            'content' => ContentBlock::allKeyed(),
        ]);
    }

    public function send(SendContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $contactEmail = ContentBlock::get('contact_email') ?: config('mail.from.address');

        Mail::to($contactEmail)->send(new ContactFormMail($validated));

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
