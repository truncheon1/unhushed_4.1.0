<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Models\UserDonation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonationsController extends Controller
{
    /**
     * Display the donations management page
     */
    public function index($path)
    {
        return view('backend.finance.fulfillment.donations')
        ->with('section', 'billing')
        ->with('sect2', 'my-donations')
        ->with('path', get_path($path));
    }
    
    /**
     * Get donations data for DataTables
     */
    public function getData(Request $request, $path)
    {
        $query = UserDonation::with('user')->withTrashed();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('recurring')) {
            $query->where('recurring', $request->recurring == '1');
        }
        
        if ($request->filled('donor')) {
            if ($request->donor === '0') {
                // Anonymous donations
                $query->where('user_id', 0);
            } elseif ($request->donor === 'named') {
                // Named donors only
                $query->where('user_id', '>', 0);
            }
        }
        
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }
        
        // DataTables search
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('payment_id', 'LIKE', "%{$search}%")
                  ->orWhere('subscription_id', 'LIKE', "%{$search}%")
                  ->orWhere('amount', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Total records
        $totalRecords = UserDonation::count();
        $filteredRecords = $query->count();
        
        // Ordering
        $orderColumn = $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'desc');
        $columns = ['id', 'created_at', 'user_id', 'amount', 'recurring', 'status', 'payment_id', 'receipt_sent'];
        
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        }
        
        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $donations = $query->skip($start)->take($length)->get();
        
        // Format data for DataTables
        $data = $donations->map(function($donation) use ($path) {
            return [
                'id' => $donation->id,
                'created_at' => $donation->created_at->format('M d, Y H:i'),
                'donor_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'recurring' => $donation->recurring,
                'status' => $donation->status,
                'payment_id' => $donation->payment_id,
                'receipt_sent' => $donation->receipt_sent,
                'actions' => view('backend.finance.fulfillment.partials.donation-actions', [
                    'donation' => $donation,
                    'path' => $path
                ])->render()
            ];
        });
        
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
    
    /**
     * Get summary statistics
     */
    public function getStats(Request $request, $path)
    {
        $query = UserDonation::completed();
        
        // Apply same filters as getData
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('recurring')) {
            $query->where('recurring', $request->recurring == '1');
        }
        
        if ($request->filled('donor')) {
            if ($request->donor === '0') {
                $query->where('user_id', 0);
            } elseif ($request->donor === 'named') {
                $query->where('user_id', '>', 0);
            }
        }
        
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }
        
        $total = $query->sum('amount');
        $onetime = (clone $query)->where('recurring', false)->sum('amount');
        $recurring = (clone $query)->where('recurring', true)->sum('amount');
        $donorCount = (clone $query)->where('user_id', '>', 0)->distinct('user_id')->count('user_id');
        
        return response()->json([
            'total' => $total,
            'onetime' => $onetime,
            'recurring' => $recurring,
            'donor_count' => $donorCount
        ]);
    }
    
    /**
     * Get a single donation for editing
     */
    public function show($path, $id)
    {
        $donation = UserDonation::with('user')->withTrashed()->findOrFail($id);
        
        return response()->json([
            'id' => $donation->id,
            'donor_name' => $donation->donor_name,
            'amount' => $donation->amount,
            'status' => $donation->status,
            'recurring' => $donation->recurring,
            'payment_method_id' => $donation->payment_method_id,
            'payment_type' => $donation->payment_type,
            'message' => $donation->message,
            'payment_id' => $donation->payment_id,
            'subscription_id' => $donation->subscription_id,
            'receipt_sent' => $donation->receipt_sent,
            'receipt_sent_at' => $donation->receipt_sent_at?->format('M d, Y H:i'),
            'created_at' => $donation->created_at->format('M d, Y H:i')
        ]);
    }
    
    /**
     * Update a donation
     */
    public function update(Request $request, $path, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,completed,failed,refunded',
            'receipt_sent' => 'boolean'
        ]);
        
        $donation = UserDonation::withTrashed()->findOrFail($id);
        
        $donation->amount = $request->amount;
        $donation->status = $request->status;
        
        // Handle receipt sent status
        if ($request->receipt_sent && !$donation->receipt_sent) {
            $donation->markReceiptSent();
        } elseif (!$request->receipt_sent && $donation->receipt_sent) {
            $donation->receipt_sent = false;
            $donation->receipt_sent_at = null;
        }
        
        // Update completed_at if status changed to completed
        if ($request->status === UserDonation::STATUS_COMPLETED && !$donation->completed_at) {
            $donation->markCompleted();
        }
        
        $donation->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Donation updated successfully'
        ]);
    }
    
    /**
     * Soft delete a donation
     */
    public function destroy($path, $id)
    {
        $donation = UserDonation::findOrFail($id);
        $donation->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Donation deleted successfully'
        ]);
    }
    
    /**
     * Export donations to CSV
     */
    public function export(Request $request, $path)
    {
        $query = UserDonation::with('user')->withTrashed();
        
        // Apply same filters as getData
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('recurring')) {
            $query->where('recurring', $request->recurring == '1');
        }
        
        if ($request->filled('donor')) {
            if ($request->donor === '0') {
                $query->where('user_id', 0);
            } elseif ($request->donor === 'named') {
                $query->where('user_id', '>', 0);
            }
        }
        
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse($dates[0])->startOfDay(),
                    Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }
        
        $donations = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'donations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Date',
                'Donor Name',
                'Donor Email',
                'Amount',
                'Type',
                'Status',
                'Payment ID',
                'Subscription ID',
                'Message',
                'Receipt Sent',
                'Receipt Sent At'
            ]);
            
            // CSV rows
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id,
                    $donation->created_at->format('Y-m-d H:i:s'),
                    $donation->donor_name,
                    $donation->user ? $donation->user->email : 'Anonymous',
                    $donation->amount,
                    $donation->recurring ? 'Recurring' : 'One-Time',
                    ucfirst($donation->status),
                    $donation->payment_id ?? '',
                    $donation->subscription_id ?? '',
                    $donation->message ?? '',
                    $donation->receipt_sent ? 'Yes' : 'No',
                    $donation->receipt_sent_at ? $donation->receipt_sent_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Generate tax receipt PDF
     */
    public function generateReceipt($path, $id)
    {
        $donation = UserDonation::with('user')->findOrFail($id);
        
        // Only generate receipts for completed, non-anonymous donations
        if ($donation->isAnonymous() || $donation->status !== UserDonation::STATUS_COMPLETED) {
            abort(403, 'Receipt cannot be generated for this donation');
        }
        
        // TODO: Implement PDF generation using DomPDF or similar
        // For now, return a simple HTML receipt that can be printed
        return view('backend.finance.fulfillment.partials.receipt', [
            'donation' => $donation,
            'path' => $path
        ]);
    }
}
