<?php
namespace App\Http\Controllers\Finance;
use App\Http\Controllers\Controller;
use App\Models\Organizations;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\PurchaseCart;
use App\Models\PurchaseFulfillment;
use App\Models\PurchaseItem;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FulfillmentController extends Controller
{
    public $section = 'fulfillment';

    // Was Orders to be shipped now all orders that have been completed
    public function index($path = 'educators'){
        $details = [];
        //get all completed orders
        $orders = PurchaseCart::where('completed', PurchaseCart::CART_COMPLETE)->orderBy('completed_at', 'DESC')->get();
        foreach($orders as $order){
            //PurchaseCart info
            $user_id    = $order->user_id;
            $tax        = $order->tax;
            $shipping   = $order->shipping;
            $total      = $order->total;
            $user_info = [];
            $user_info = User::where('id', '=', $user_id)->get();
            //get ordering users's name and organization
            $by = User::where('id', '=', $user_id)->first('name');
                if($by)
                $by = $by->name;
            $org_id = User::where('id', '=', $user_id)->first('org_id');
                if($org_id)
                $org_id = $org_id->org_id;
            $org = Organizations::where('id', '=', $org_id)->first('name');
                if($org)
                $org = $org->name;
            //item info
            $items = PurchaseItem::where('cart_id', $order->id)->get();
            $ci = [];
            foreach($items as $item){
                //set initial item info
                $proName = 'Unknown Item';
                $varName = '';
                $itemFound = false;
                
                //get product and variant info
                $p = Products::find($item->product_id);
                $v = ProductVar::find($item->var_id);
                if($p) {
                    $proName = $p->name;
                    $varName = $v ? ' - ' . $v->name : '';
                    $itemFound = true;
                }
                
                // Add item to array (even if not found, to show there was an item)
                $ci[] = [
                    'name'  => $proName . $varName . ($itemFound ? '' : ' (ID: ' . $item->product_id . ')'),
                    'qty'   => $item->quantity,
                    'cost'  => $item->price, // Always use historical cost from purchase_items
                ];
            }
            //get delivery info
            $f = PurchaseFulfillment::where('cart_id', '=', $order->id)->first();
            
            // Get status from fulfillment record (not cart)
            $fulfillmentStatus = $f ? $f->status : 0;
            
            $sa = UserAddress::where('id', '=', $f->address_id)->first();
            if($sa){
                $name = $sa->name;
                $address = $sa->street;
                $city= $sa->city;
                $state = $sa->state_province;
                $country = $sa->country;
                $zip = $sa->zip;
                $shipped = \Carbon\Carbon::parse($f->shipped)->format('m/d/Y');
                $tracking = $f->tracking;
            }else{
                $name = '';
                $address = 'digital';
                $city= '';
                $state = '';
                $zip = '';
                $shipped = \Carbon\Carbon::parse($order->updated_at)->format('m/d/Y');
                $tracking = 'digital';
            }
            $details[] = [
                'order'     => $order,
                'id'        => $user_id,
                'by'        => $by,
                'org'       => $org,
                'name'      => $name,
                'address'   => $address,
                'city'      => $city,
                'state'     => $state,
                'zip'       => $zip,
                'tax'       => $tax,
                'shipping'  => $shipping,
                'total'     => $total,
                'status'    => $this->get_status($fulfillmentStatus),
                'shipped'   => $shipped,
                'tracking'  => $tracking,
                'items'     => $ci
            ];
        }
         return view('backend.finance.fulfillment.fulfillment', compact('details'))
         ->with('section', $this->section)
         ->with('path', get_path($path));
    }

     public function update(Request $req){
        //update fulfillment record
        $fulfillment = PurchaseFulfillment::where('cart_id', $req->input('cart_id'))->first();
        
        if(!$fulfillment) {
            return redirect()->back()->with('error', 'Fulfillment record not found');
        }
        
        $fulfillment->status = $req->input('status');
        $fulfillment->shipped = $req->input('shipped');
        $fulfillment->tracking = $req->input('tracking');
        $fulfillment->updated_at = now();
        $fulfillment->save();
        
        //active campaign cron job runs daily CartShipped
        //redirect
        return redirect()->back()->with('success', 'Order updated successfully');
    }

    private function get_status($status){
        $s = "ORDERED";
        if($status == 1)
            $s = "SENT";
        if($status == 2)
            $s = "CANCELED";
        if($status == 3)
            $s = "BACKORDER";
        return $s;
    }

    //My subscriptions page
    public function subscriptions($path = 'educators'){
        $orders = Cart::where('completed', Cart::CART_COMPLETE)->orderBy('created_at', 'DESC')->get();
        $details = [];
        foreach($orders as $order){
            $id = $order->user_id;
            $sa = UserAddress::where('cart_id', '=', $order->id)->first();
            $items = CartItem::where('cart_id', $order->id)->get();
            $ci = [];
            foreach($items as $item){
                // Direct lookup using products_id
                $p = Products::find($item->products_id);
                if(!$p)
                    continue;
                    
                $ci[] = [
                    "name"=>$p->name,
                    "qty"=>$item->qty,
                    "cost"=>$p->price,
                ];
            }
            if($sa){
                $name = $sa->name;
                $address1 = $sa->api_address1;
                $address2 = $sa->api_address2;
                $city= $sa->api_city;
                $state = $sa->api_state;
                $zip = $sa->api_zip5;
                $shipped = $order->shipped;
                $tracking = $order->tracking;
            }else{
                $name = User::where('id', '=', $id)->first('name');
                if($name)
                $name = $name->name;
                $address1 = 'digital';
                $address2 = '';
                $city= '';
                $state = '';
                $zip = '';
                $shipped = $order->created_at;
                $tracking = 'digital';
            }
            $total = $order->total;
            $status = $order->status;
            $details[] = [
                'order'     => $order,
                'id'        => $id,
                'name'      => $name,
                'address1'  => $address1,
                'address2'  => $address2,
                'city'      => $city,
                'state'     => $state,
                'zip'       => $zip,
                'total'     => $total,
                'status'    => $this->get_status($status),
                'shipped'   => $shipped,
                'tracking'  => $tracking,
                'items'     => $ci
            ];
        }
       return view('backend.finance.subs', compact('details'))
       ->with('section', $this->section)
       ->with('path', get_path($path));
    }
    
    /**
     * Display donations fulfillment page for manual donation entry
     */
    public function donations($path = 'educators'){
        $donations = \App\Models\UserDonation::with('user')
            ->orderBy('created_at', 'DESC')
            ->get();
        
        return view('backend.finance.fulfillment.donations', compact('donations'))
            ->with('section', 'fulfillment')
            ->with('sect2', 'donations-fulfillment')
            ->with('path', get_path($path));
    }
    
    /**
     * Store a new manual donation entry
     */
    public function storeDonation(Request $request, $path = 'educators'){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,check,paypal,stripe,wire,other',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $donation = \App\Models\UserDonation::create([
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'status' => 'completed',
            'recurring' => false,
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'receipt_sent' => false,
            'created_at' => $validated['donation_date'],
        ]);
        
        // Tag donor in ActiveCampaign
        try {
            $user = \App\Models\User::find($validated['user_id']);
            if ($user && $user->email) {
                $acService = new \App\Services\ActiveCampaignService();
                $acService->tagDonor($user);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to tag donor in ActiveCampaign', [
                'user_id' => $validated['user_id'],
                'error' => $e->getMessage()
            ]);
        }
        
        return redirect(url(get_path($path).'/backend/donations-fulfillment'))
            ->with('success', 'Donation added successfully!');
    }
    
    /**
     * Get donation for editing (API endpoint)
     */
    public function editDonation($path = 'educators', $id){
        $donation = \App\Models\UserDonation::with('user')->findOrFail($id);
        return response()->json($donation);
    }
    
    /**
     * Update an existing donation
     */
    public function updateDonation(Request $request, $path = 'educators', $id){
        $donation = \App\Models\UserDonation::findOrFail($id);
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,check,paypal,stripe,wire,other',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt_sent' => 'boolean',
        ]);
        
        $donation->update([
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'receipt_sent' => $request->has('receipt_sent'),
            'created_at' => $validated['donation_date'],
        ]);
        
        return redirect(url(get_path($path).'/backend/donations-fulfillment'))
            ->with('success', 'Donation updated successfully!');
    }
    
    /**
     * Delete a donation
     */
    public function deleteDonation($path = 'educators', $id){
        $donation = \App\Models\UserDonation::findOrFail($id);
        
        // Only allow deletion of manual entries (no Stripe IDs)
        if($donation->payment_intent_id || $donation->subscription_id){
            return redirect(url(get_path($path).'/backend/donations-fulfillment'))
                ->with('error', 'Cannot delete Stripe donations. Please use Stripe dashboard.');
        }
        
        $donation->delete();
        
        return redirect(url(get_path($path).'/backend/donations-fulfillment'))
            ->with('success', 'Donation deleted successfully!');
    }
    
    /**
     * Search users for donor selection (API endpoint)
     */
    public function searchUsers(Request $request, $path = 'educators'){
        $query = $request->input('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $users = \App\Models\User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();
            
        return response()->json($users);
    }
    
    /**
     * Create a new user for donation entry (API endpoint)
     */
    public function createDonorUser(Request $request, $path = 'educators'){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        
        // Generate temporary password
        $tempPassword = 'Temp' . rand(1000, 9999) . '!';
        
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($tempPassword),
            'email_verified_at' => now(), // Auto-verify
        ]);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'temp_password' => $tempPassword,
        ]);
    }
}
