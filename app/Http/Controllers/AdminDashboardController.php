<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ContactMessage;


class AdminDashboardController extends Controller
{
    
    public function showUsersByCountry($country){
        $users = User::where('country', $country)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByReligion($religion){
        $users = User::where('religion', $religion)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByDepartment($department){
        $users = User::where('department', $department)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByCourse($course_type){
        $users = User::where('course_type', $course_type)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }
    
    public function showUsersByCourseLanguage($course_language)
    {
        $users = User::where('course_language', $course_language)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function usersByRoom()
    {
        $rooms = User::query()
            ->select('room_number', DB::raw('COUNT(*) as total_users'))
            ->whereNotNull('room_number')
            ->where('room_number', '!=', '')
            ->groupBy('room_number')
            ->orderBy('room_number')
            ->paginate(20);

        return view('admin.users.by_room', compact('rooms'));
    }

    public function usersByRoomShow($roomNumber)
    {
        $users = User::query()
            ->where('room_number', $roomNumber)
            ->orderBy('full_name')
            ->paginate(20);

        return view('admin.users.by_room_show', [
            'roomNumber' => $roomNumber,
            'users' => $users,
        ]);
    }


    public function index(Request $request)
    {
        // Fetch data from the database using the User model
        $totalStudents = User::count();
        $totalStudentsList = User::count();
        $totalpendingstudent = User::where('approved', '0')->count();
        $notCompleteCount = User::where('medical_status', 'Not Complete')->count();
        $totalBangladeshiStudents = User::where('country', 'Bangladesh')->count();
        $totalIndianStudents = User::where('country', 'India')->count();
        $totalNepaliStudents = User::where('country', 'Nepal')->count();

        // Students by religion
        $muslimStudents = User::where('religion', 'Muslim')->count();
        $hinduStudents = User::where('religion', 'Hindu')->count();
        $boddhoStudents = User::where('religion', 'Boddho')->count();
        $cristanStudents = User::where('religion', 'Cristan')->count();

        // Students by department
        $language = User::where('department', 'Prepetory Language Course')->count();
        $automobileStudents = User::where('department', 'Automobile')->count();
        $forestryStudents = User::where('department', 'Forestry')->count();
        $mechanicalStudents = User::where('department', 'Mechanical')->count();
        $cstStudents = User::where('department', 'Computer Science and Technology')->count();
        $economicsStudents = User::where('department', 'Economics')->count();
        
        // Fetch other departments
        $otherDepartments = User::select('department', DB::raw('COUNT(*) as count'))
            ->whereNotIn('department', [
                'Prepetory Language Course',
                'Automobile',
                'Forestry',
                'Mechanical',
                'Computer Science and Technology',
                'Economics'
            ])
            ->groupBy('department')
            ->get();
    
        // Fetch course/language data
        $courseLanguages = User::select('course_type', DB::raw('COUNT(*) as count'))
            ->groupBy('course_type')
            ->get();

        // Students fined by course
        $languageStudents = User::where('course_type', 'Language')->count();
        $bscStudents = User::where('course_type', 'BSC')->count();
        $mscStudents = User::where('course_type', 'MSC')->count();
        $phdStudents = User::where('course_type', 'PHD')->count();
        
        // Students fined by course language
        $englishStudents = User::where('course_language', 'English')->count();
        $russianStudents = User::where('course_language', 'Russian')->count();
        
        $pendingComplaints = Complaint::with('user')->where('status', 'pending')->get();
        $pendingComplaintsCount = Complaint::where('status', 'pending')->count();
        $unreadContactMessagesCount = ContactMessage::where('is_read', false)->count();
        

        // Return the view with the data
        return view('admin.dashboard', compact(
            'totalStudents', 'totalBangladeshiStudents', 'totalIndianStudents', 'totalNepaliStudents',
            'muslimStudents', 'hinduStudents', 'boddhoStudents', 'cristanStudents',
            'language', 'automobileStudents', 'forestryStudents', 'mechanicalStudents', 'cstStudents', 'economicsStudents',
            'languageStudents', 'bscStudents', 'mscStudents', 'phdStudents','otherDepartments', 'englishStudents', 'russianStudents','totalStudentsList','notCompleteCount','totalpendingstudent',
            'pendingComplaints','pendingComplaintsCount','unreadContactMessagesCount'
        ));
        
    }
    
    public function showByCourseLanguage($course_language)
    {
        $users = User::where('course_language', $course_language)->get();
    
        // Debug: Check if $users contains data
        if ($users->isEmpty()) {
            return view('admin.users.course_language', [
                'users' => $users,
                'course_language' => $course_language,
                'message' => 'No users found for this course language.'
            ]);
        }
    
        return view('admin.users.course_language', compact('users', 'course_language'));
    }

    
    public function getOtherDepartments()
        {
            // Fetch unique departments where the department is not part of predefined ones
            $predefinedDepartments = [
                'Prepetory Language Course', 
                'Automobile', 
                'Forestry', 
                'Mechanical', 
                'Computer Science and Technology', 
                'Economics'
            ];
    
            $otherDepartments = User::whereNotIn('department', $predefinedDepartments)
                ->whereNotNull('department')
                ->distinct()
                ->pluck('department');
    
            // Return the data as JSON
            return response()->json($otherDepartments);
        }
    
     public function studentList(Request $request)
        {
            $totalStudentsList = User::orderBy('room_number', 'asc')->paginate(20);
            
            $totalStudentsListmedicalcomplete = User::orderByRaw("FIELD(medical_status, 'Not Complete', 'Complete') DESC")
                                       ->orderBy('medical_status')
                                       ->paginate(20);
    
            return view('admin.studentlist', compact('totalStudentsList', 'totalStudentsListmedicalcomplete'));
        }
        
        
    public function studentListmedical(Request $request)
    {
        // Fetch and sort students with "Not Complete" first
        $totalStudentsListmedical = User::where('medical_status', '!=', 'Complete')
            ->orderByRaw("FIELD(medical_status, 'Not Complete', 'Complete')")
            ->paginate(20);
        
         $totalStudentsCount = User::count();
         
        // Count students with "Not Complete" status
        $notCompleteCount = User::where('medical_status', 'Not Complete')->count();
    
        return view('admin.studentlistmedical', compact('totalStudentsListmedical', 'totalStudentsCount', 'notCompleteCount'));
    }

        
        
    public function updateMedicalStatus(Request $request)
        {
            $student = User::find($request->id);
        
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found']);
            }
        
            // Update the specific medical column
            $field = $request->field;
            $student->$field = $request->value;
            
            // Check if both medical1 and medical2 are selected
            if ($student->medical1 === 'Yes' && $student->medical2 === 'Yes') {
                $student->medical_status = 'Complete';
            } else {
                $student->medical_status = 'Not Complete';
            }
        
            $student->save();
            
            $medical1NotComplete = User::whereNull('medical1')->count();
            $medical2NotComplete = User::whereNull('medical2')->count();
        
            return response()->json([
                'success' => true,
                'new_status' => $student->medical_status,
                'medical1_not_complete' => $medical1NotComplete,
                'medical2_not_complete' => $medical2NotComplete
            ]);
        }

}
