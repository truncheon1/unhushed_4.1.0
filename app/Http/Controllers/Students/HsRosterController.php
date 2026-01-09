<?php
namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;
use App\Models\HsClassRegistration;
use Illuminate\Http\Request;

class HsRosterController extends Controller
{
    public function index($path = 'educators'){
        $details = [];
        $student = HsClassRegistration::where('year', '=', '2023-2024')->get();
        foreach($student as $s){
            $id = $s->id;
            $details[] = [
                'id'        => $id,
                'year'      => $s->year,
                'name'      => $s->kid_name,
                'email'     => $s->kid_email,
                'phone'     => $s->kid_phone,
                'age'       => $s->age,
                'grade'     => $s->grade,
                'gender'    => $s->gender,
                'pronouns'  => $s->pronouns,
                'paid'      => $s->paid,
                'legal'     => $s->legal,
                'p1_name'   => $s->parent1_name,
                'p1_phone'  => $this->number($s->parent1_phone),
                'p1_email'  => $s->parent1_email,
                'p2_name'   => $s->parent2_name,
                'p2_phone'  => $this->number($s->parent2_phone),
                'p2_email'  => $s->parent2_email,
            ];
        }
        return view('backend.students.hs-roster', compact('details'))
        ->with('path', get_path($path));
    }

    function number($phone) {
        $numbers_only = preg_replace("/[^\d]/", "", $phone);
        return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $numbers_only);
    }

    public function get_student($path = 'educators', $id){
        $student = HsClassRegistration::find($id);
        if(!$student)
            return response()->json(['error' => true, 'message'=>"Student not found!"]);
        return response()->json(['error' => false, 'student'=>$student]);
    }

    public function update_student(Request $req){
        $error = true;
        $kid_name       =  $req->input('youth_name');
        $age            =  $req->input('age');
        $grade          =  $req->input('grade');
        $gender         =  $req->input('gender_identity');
        $pronouns       =  $req->input('pronouns');
        $parent1_name   =  $req->input('parent1_name');
        $parent1_email  =  $req->input('parent1_email');
        $parent1_phone  =  $req->input('parent1_phone');
        $parent2_name   =  $req->input('parent2_name');
        $parent2_email  =  $req->input('parent2_email');
        $parent2_phone  =  $req->input('parent2_phone');
        if(!$req->input('id') || !$student = HsClassRegistration::find($req->input('id'))){
            return response()->json(['success'=>false, 'messaage'=>'Student not found.']);
        }
        if(!$req->input('youth_name')){
            return response()->json(['error' => true, 'message' => 'The student name is required']);
        }
        if(!$req->input('grade')){
            return response()->json(['error' => true, 'message' => 'A grade is required']);
        }
        if(!$req->input('legal')){
            return response()->json(['error' => true, 'message' => 'A contract status is required']);
        }
        $student->kid_name      = trim($kid_name);
        $student->kid_email     = $req->input('youth_email');
        $student->kid_phone     = $req->input('youth_phone');
        $student->age           = trim($age);
        $student->grade         = trim($grade);
        $student->gender        = trim($gender);
        $student->pronouns      = trim($pronouns);
        $student->paid          = $req->input('paid');
        $student->legal         = $req->input('legal');
        $student->parent1_name  = trim($parent1_name);
        $student->parent1_email = trim($parent1_email);
        $student->parent1_phone = trim($parent1_phone);
        $student->parent2_name  = trim($parent2_name);
        $student->parent2_email = trim($parent2_email);
        $student->parent2_phone = trim($parent2_phone);
        $student->save();
        return response()->json(['success'=>true, 'student'=>$student]);
    }

    public function delete($path, $id){
        //delete product rel
        $student = HsClassRegistration::find($id);
        if(!$student)
            return response()->json(['error' => true, 'message'=>'Student not found!']);
        //delete product from student
        $student->delete();
        return response()->json(['error' => false]);
    }

}