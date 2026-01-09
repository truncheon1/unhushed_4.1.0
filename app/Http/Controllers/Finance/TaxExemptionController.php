<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Organizations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaxExemptionController extends Controller
{
    public $section = 'backend';

    /**
     * Display list of tax exemption requests
     */
    public function index($path = 'educators')
    {
        $pending = Organizations::where('tax_exempt', true)
            ->whereNull('tax_exempt_verified_at')
            ->with('head')
            ->orderBy('created_at', 'desc')
            ->get();

        $approved = Organizations::where('tax_exempt', true)
            ->whereNotNull('tax_exempt_verified_at')
            ->with('head')
            ->orderBy('tax_exempt_verified_at', 'desc')
            ->limit(50)
            ->get();

        return view('backend.finance.tax-exemptions')
            ->with('pending', $pending)
            ->with('approved', $approved)
            ->with('section', 'fulfillment')
            ->with('sect2', 'fulfillment')
            ->with('path', get_path($path));
    }

    /**
     * Show details of a specific organization's tax exemption
     */
    public function show($path, $orgId)
    {
        $org = Organizations::with('head')->findOrFail($orgId);

        return view('backend.finance.tax-exemptions.show')
            ->with('org', $org)
            ->with('section', $this->section)
            ->with('path', get_path($path));
    }

    /**
     * Upload tax exemption certificate
     */
    public function uploadCertificate(Request $request, $path, $orgId)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'tax_exempt_id' => 'required|string|max:50',
            'tax_exempt_type' => 'required|string|in:nonprofit,government,educational,religious',
            'tax_exempt_expiry' => 'nullable|date|after:today',
        ]);

        $org = Organizations::findOrFail($orgId);

        // Store the certificate
        $file = $request->file('certificate');
        $filename = 'tax-exemption-' . $orgId . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tax-exemptions', $filename, 'private');

        // Update organization
        $org->update([
            'tax_exempt' => true,
            'tax_exempt_id' => $request->tax_exempt_id,
            'tax_exempt_type' => $request->tax_exempt_type,
            'tax_exempt_certificate' => $path,
            'tax_exempt_expiry' => $request->tax_exempt_expiry,
        ]);

        return redirect()->back()->with('success', 'Tax exemption certificate uploaded successfully. Awaiting admin approval.');
    }

    /**
     * Approve tax exemption
     */
    public function approve(Request $request, $path, $orgId)
    {
        $org = Organizations::findOrFail($orgId);

        $org->update([
            'tax_exempt_verified_at' => now(),
            'tax_exempt_verified_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Tax exemption approved successfully.');
    }

    /**
     * Reject tax exemption
     */
    public function reject(Request $request, $path, $orgId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $org = Organizations::findOrFail($orgId);

        // Delete certificate if exists
        if ($org->tax_exempt_certificate) {
            Storage::disk('private')->delete($org->tax_exempt_certificate);
        }

        // Reset tax exemption fields
        $org->update([
            'tax_exempt' => false,
            'tax_exempt_id' => null,
            'tax_exempt_type' => null,
            'tax_exempt_certificate' => null,
            'tax_exempt_expiry' => null,
            'tax_exempt_verified_at' => null,
            'tax_exempt_verified_by' => null,
        ]);

        // Could send email notification here with reason
        // Mail::to($org->head->email)->send(new TaxExemptionRejected($org, $request->reason));

        return redirect()->back()->with('success', 'Tax exemption rejected.');
    }

    /**
     * Download certificate
     */
    public function downloadCertificate($path, $orgId)
    {
        $org = Organizations::findOrFail($orgId);

        if (!$org->tax_exempt_certificate) {
            abort(404, 'Certificate not found');
        }

        return Storage::disk('private')->download($org->tax_exempt_certificate);
    }

    /**
     * Manage individual user exemption override
     */
    public function updateUserExemption(Request $request, $path, $userId)
    {
        $request->validate([
            'tax_exempt_override' => 'required|boolean',
            'tax_exempt_notes' => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($userId);

        $user->update([
            'tax_exempt_override' => $request->tax_exempt_override,
            'tax_exempt_notes' => $request->tax_exempt_notes,
        ]);

        return redirect()->back()->with('success', 'User tax exemption updated successfully.');
    }
}
