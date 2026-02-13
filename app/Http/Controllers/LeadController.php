<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'email'    => 'required|email|max:255',
            'lembaga'  => 'nullable|string|max:255',
        ]);

        $validated = array_map(function ($value) {
            return is_string($value) ? strip_tags(trim($value)) : $value;
        }, $validated);

        Lead::create($validated);

        return back()->with('success', 'Terima kasih! Template budgeting akan dikirim ke WhatsApp/Email Anda.');
    }
}
