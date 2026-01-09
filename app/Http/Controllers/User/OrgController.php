<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ActiveSubscriptions;
use App\Models\Organizations;
use App\Models\Products;

class OrgController extends Controller
{
    const RPP = 20; //results per page
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    //MASTER ORG LIST
    public function orgs(Request $req, $path = 'educators'){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        $oid = 0;
        if($req->org_id)
            $oid = $req->org_id;
        if($oid != 0){
            $org = Organizations::find($oid);
            $org_id = $org->id;
        }else{
            $org_id = 0;
        }
        $page = $req->input('page') || 0;
        $skip = $page * self::RPP;
        auth()->user()->permissions_string();

        // Compute status counts for all organizations to avoid per-row queries in the view
        $organizations = Organizations::orderBy('name', 'ASC')->get();
        $orgIds = $organizations->pluck('id')->toArray();

        $statuses = [];
        if (count($orgIds)) {
            // Get counts grouped by org_id and status
            $counts = ActiveSubscriptions::select('org_id', 'status', \DB::raw('COUNT(*) as cnt'))
                ->whereIn('org_id', $orgIds)
                ->where('category', 1)
                ->groupBy('org_id', 'status')
                ->get();

            // Initialize statuses
            foreach ($orgIds as $id) {
                $statuses[$id] = [
                    'active_count' => 0,
                    'canceled_count' => 0,
                    'reviewing_count' => 0,
                    'reviewed_count' => 0,
                    'label' => ''
                ];
            }

            foreach ($counts as $row) {
                if (!isset($statuses[$row->org_id])) continue;
                if ($row->status == 2) {
                    $statuses[$row->org_id]['active_count'] = $row->cnt;
                } elseif ($row->status == 3) {
                    $statuses[$row->org_id]['canceled_count'] = $row->cnt;
                } elseif ($row->status == 4) {
                    $statuses[$row->org_id]['reviewing_count'] = $row->cnt;
                } elseif ($row->status == 5) {
                    $statuses[$row->org_id]['reviewed_count'] = $row->cnt;
                }
            }

            foreach ($statuses as $id => &$s) {
                if ($s['active_count'] > 0) $s['label'] = 'Active';
                elseif ($s['reviewing_count'] > 0) $s['label'] = 'Reviewing';
            }
            unset($s);
        } else {
            $statuses = [];
        }

        return view('backend.users.master-orgs')->with(
                [
                    'organizations'=> $organizations,
                    'oid'          => $org_id,
                    'statuses'     => $statuses,
                ]
                )->with('section', 'master')
                ->with('sect2', 'master-orgs')
                ->with('path', get_path($path));
    }
    
    //ADD NEW ORG
    public function add_org(Request $req){
        if(!auth()->user()->can('access-master') && !auth()->user()->can('modify-users'))
            abort(503);
        try{
            $req->validate([
                'name' => 'bail|required|min:3',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()]);
        }
        $org = new Organizations();
        $org->name = $req->input('name');
        if( $req->input('address')){
            $org->address = $req->input('address');
        }
        if( $req->input('city')){
            $org->city = $req->input('city');
        }
        if( $req->input('state')){
            $org->state = $req->input('state');
        }
        if( $req->input('zip')){
            $org->zip = $req->input('zip');
        }
        if( $req->input('country')){
            $org->country = $req->input('country');
        }
        if( $req->input('url')){
            $org->url = $req->input('url');
        }
        if( $req->input('email_match')){
            $org->email_match = $req->input('email_match');
        }
        if( $req->input('grades')){
            $org->grades = $req->input('grades');
        }
        if( $req->input('size')){
            $org->size = $req->input('size');
        }
        if( $req->input('pedagogy')){
            $org->pedagogy = $req->input('pedagogy');
        }
        $org->save();
        return response()->json(['success' => true, 'org_id' => $org->id]);
    }
    
    //VIEW ORG PAGE
    public function view_org($path = 'educators', $id){
        $org = Organizations::find($id);
        if(!$org)
            abort(404);
        $ac_subs = ActiveSubscriptions::where('org_id', $org->id)
            ->where('category', '=', '1')
            ->orderBy('product_id', 'desc')
            ->get();
        $ac_trainings = ActiveSubscriptions::where('org_id', $org->id)
            ->where('category', '=', '7')
            ->orderBy('product_id', 'desc')
            ->get();
        $packages = Products::where('category', 1)->where('id', '>', '1')->get();
        $trainings = Products::where('category', 7)->get();
        return view('backend.users.org')
            ->with('org', $org)
            ->with('ac_subs', $ac_subs)
            ->with('ac_trainings', $ac_trainings)
            ->with('packages', $packages)
            ->with('trainings', $trainings)
            ->with('section', 'master')
            ->with('path', get_path($path));
    }

    //UPDATE ORG
    public function org_update(Request $req){
        $org = Organizations::find($req->input('id'));
        if(!$org)
            abort(404);
        //update
        $req->validate([
            'name' => 'bail|required|min:5|max:190'
        ]);
        $org->name = $req->input('name');
        if( $req->input('address')){
            $org->address = $req->input('address');
        }
        if( $req->input('city')){
            $org->city = $req->input('city');
        }
        if( $req->input('state')){
            $org->state = $req->input('state');
        }
        if( $req->input('zip')){
            $org->zip = $req->input('zip');
        }
        if( $req->input('country')){
            $org->country = $req->input('country');
        }
        if( $req->input('url')){
            $org->url = $req->input('url');
        }
        if( $req->input('email_match')){
            $org->email_match = $req->input('email_match');
        }
        if( $req->input('grades')){
            $org->grades = $req->input('grades');
        }
        if( $req->input('size')){
            $org->size = $req->input('size');
        }
        if( $req->input('pedagogy')){
            $org->pedagogy = $req->input('pedagogy');
        }
        $org->save();
        return redirect()->back()->with('success', 'org updated');
    }

    //ADD LICENSES
    public function org_lic(Request $req){
        $org = Organizations::find($req->input('id'));
        if(!$org)
            abort(404);
        $as = ActiveSubscriptions::where('product_id', $req->input('package'))->where('org_id', $org->id)->first();
        if(!$as){
            //enter
            $req->validate([
                'total' => 'required|max:100',
                'status' => 'required|integer'
            ]);

            $as = new ActiveSubscriptions();
            $as->product_id = $req->input('package');
            $as->org_id     = $org->id;
            $as->total      = $req->input('total');
            $as->status     = $req->input('status');
            $as->category       = 1;
            $as->used       = 0;
        } else {
            $as->total  = $as->total + $req->input('total');
            $as->status = $req->input('status');
        }
        $as->save();
        return redirect()->back()->with('success', 'license(s) added');
    }

    //ADD TRAININGS
    public function org_training(Request $req){
        $org = Organizations::find($req->input('id'));
        if(!$org)
            abort(404);
        $as = ActiveSubscriptions::where('product_id', $req->input('package'))->where('org_id', $org->id)->first();
        if(!$as){
            $as = new ActiveSubscriptions();
            $as->product_id = $req->input('package');
            $as->org_id = $org->id;
            $as->total = $req->input('total');
            $as->category = 7;
            $as->used = 0;
        } else {
            $as->total = $as->total + $req->input('total');
        }
        $as->save();
        return redirect()->back()->with('success', 'training(s) added');
    }

    //UPDATE LICENSES
    public function get_lic(Request $req, $path = 'educators'){
        //add validation later
        $id = $req->input('s_id');
        $lic = ActiveSubscriptions::find($id);
        return response()->json(['lic'=>$lic]);
    }
    public function lic_update(Request $req, $path = 'educators'){
        $lic = ActiveSubscriptions::find($req->input('id'));
        if(!$lic)
            abort(404);
        //update
        $req->validate([
            'total' => 'required|max:100',
            'status' => 'required|integer'
        ]);
        $lic->total = $req->input('total');
        $lic->status = $req->input('status');
        $lic->save();
        return redirect()->back()->with('success', 'License updated');
    }

    //UPDATE TRAININGS
    public function get_training(Request $req, $path = 'educators'){
        //add validation later
        $id = $req->input('t_id');
        $training = ActiveSubscriptions::find($id);
        return response()->json(['training'=>$training]);
    }
    public function training_update(Request $req, $path = 'educators'){
        $training = ActiveSubscriptions::find($req->input('id'));
        if(!$training)
            abort(404);
        //update
        $req->validate([
            'total' => 'required|max:100'
        ]);
        $training->total = $req->input('total');
        $training->save();
        return redirect()->back()->with('success', 'Training updated');
    }

    //DELETE LICENSES
    public function lic_delete($path = 'educators', $licenses){
        if(!strlen($licenses))
            abort(403);
        if(strstr($licenses, ",")){
            $licenses = explode(",", $licenses);
        }else{
            $licenses = [$licenses];
        }
        $except = [];
        $deleted = [];
        foreach($licenses as $lid){
            $license = ActiveSubscriptions::find($lid);
            if($license->used == 0){
                $license->delete();
                $deleted[] = $lid;
            }else{
                $except[] = $license;
            }
        }
        $message = !count($except) ? "Licenses Deleted" : "Some licenses still have active users and cannot be deleted!";
        return response()->json(['success' => true, 'reason' => $message, 'deleted'=>$deleted]);
    }

    //DELETE TRAININGS
    public function training_delete($path = 'educators', $trainings){
        if(!strlen($trainings))
            abort(403);
        if(strstr($trainings, ",")){
            $trainings = explode(",", $trainings);
        }else{
            $trainings = [$trainings];
        }
        $except = [];
        $deleted = [];
        foreach($trainings as $tid){
            $training = ActiveSubscriptions::find($tid);
            if($training->used == 0){
                $training->delete();
                $deleted[] = $tid;
            }else{
                $except[] = $training;
            }
        }
        $message = !count($except) ? "Trainings Deleted" : "Some trainings still have active users and cannot be deleted!";
        return response()->json(['success' => true, 'reason' => $message, 'deleted'=>$deleted]);
    }
}
