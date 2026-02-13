<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'lembaga' => 'nullable|string|max:255',
        ]);

        Lead::create($validated);

        return back()->with('success', 'Terima kasih! Template budgeting akan dikirim ke WhatsApp/Email Anda.');
    }
}
