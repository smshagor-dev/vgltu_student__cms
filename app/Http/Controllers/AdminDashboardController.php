<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;


class AdminDashboardController extends Controller
{
    protected function approvedUsers()
    {
        return User::approved();
    }

    protected function studentListQuery(?string $search = null)
    {
        $query = $this->approvedUsers()->orderBy('room_number', 'asc');

        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $like = '%' . $search . '%';

            $builder->where('full_name', 'like', $like)
                ->orWhere('room_number', 'like', $like)
                ->orWhere('mobile_number', 'like', $like)
                ->orWhere('country', 'like', $like)
                ->orWhere('religion', 'like', $like)
                ->orWhere('course_type', 'like', $like)
                ->orWhere('department', 'like', $like)
                ->orWhere('course_language', 'like', $like)
                ->orWhere('medical_status', 'like', $like);
        });
    }

    protected function recentUsersQuery()
    {
        return User::with('studentsData')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest('created_at')
            ->latest('id');
    }

    protected function duplicateUsersCollection()
    {
        $usersForDuplicateCheck = User::with('studentsData')
            ->select('id', 'full_name', 'email', 'photo', 'room_number', 'approved', 'created_at')
            ->get();

        $duplicateNameKeys = $usersForDuplicateCheck
            ->filter(fn ($user) => filled($user->full_name))
            ->groupBy(fn ($user) => mb_strtolower(trim($user->full_name)))
            ->filter(fn ($group) => $group->count() > 1)
            ->keys();

        $duplicatePassportKeys = $usersForDuplicateCheck
            ->filter(fn ($user) => filled(optional($user->studentsData)->passport_number))
            ->groupBy(fn ($user) => mb_strtolower(trim((string) optional($user->studentsData)->passport_number)))
            ->filter(fn ($group) => $group->count() > 1)
            ->keys();

        return $usersForDuplicateCheck
            ->filter(function ($user) use ($duplicateNameKeys, $duplicatePassportKeys) {
                $nameKey = mb_strtolower(trim((string) $user->full_name));
                $passportKey = mb_strtolower(trim((string) optional($user->studentsData)->passport_number));

                return $duplicateNameKeys->contains($nameKey)
                    || $duplicatePassportKeys->contains($passportKey);
            })
            ->map(function ($user) use ($duplicateNameKeys, $duplicatePassportKeys) {
                $matchReasons = [];
                $nameKey = mb_strtolower(trim((string) $user->full_name));
                $passportKey = mb_strtolower(trim((string) optional($user->studentsData)->passport_number));

                if ($duplicateNameKeys->contains($nameKey)) {
                    $matchReasons[] = 'Name';
                }

                if ($duplicatePassportKeys->contains($passportKey)) {
                    $matchReasons[] = 'Passport';
                }

                $user->duplicate_match_reasons = $matchReasons;

                return $user;
            })
            ->sortByDesc('created_at')
            ->values();
    }

    protected function duplicateGroupsCollection()
    {
        $duplicateUsers = $this->duplicateUsersCollection();
        $nameBuckets = $duplicateUsers
            ->filter(fn ($user) => filled($user->full_name))
            ->groupBy(fn ($user) => 'name:' . mb_strtolower(trim((string) $user->full_name)))
            ->filter(fn ($group) => $group->count() > 1);

        $passportBuckets = $duplicateUsers
            ->filter(fn ($user) => filled(optional($user->studentsData)->passport_number))
            ->groupBy(fn ($user) => 'passport:' . mb_strtolower(trim((string) optional($user->studentsData)->passport_number)))
            ->filter(fn ($group) => $group->count() > 1);

        $adjacency = [];

        foreach ($duplicateUsers as $user) {
            $adjacency[$user->id] = $adjacency[$user->id] ?? [];
        }

        foreach ($nameBuckets as $bucket) {
            $ids = $bucket->pluck('id')->unique()->values()->all();

            foreach ($ids as $id) {
                $adjacency[$id] = array_values(array_unique(array_merge($adjacency[$id] ?? [], $ids)));
            }
        }

        foreach ($passportBuckets as $bucket) {
            $ids = $bucket->pluck('id')->unique()->values()->all();

            foreach ($ids as $id) {
                $adjacency[$id] = array_values(array_unique(array_merge($adjacency[$id] ?? [], $ids)));
            }
        }

        $usersById = $duplicateUsers->keyBy('id');
        $visited = [];
        $groups = collect();

        foreach ($usersById as $userId => $user) {
            if (isset($visited[$userId])) {
                continue;
            }

            $queue = [$userId];
            $componentIds = [];

            while (! empty($queue)) {
                $currentId = array_shift($queue);

                if (isset($visited[$currentId])) {
                    continue;
                }

                $visited[$currentId] = true;
                $componentIds[] = $currentId;

                foreach ($adjacency[$currentId] ?? [] as $neighborId) {
                    if (! isset($visited[$neighborId])) {
                        $queue[] = $neighborId;
                    }
                }
            }

            $users = collect($componentIds)
                ->map(fn ($id) => $usersById->get($id))
                ->filter()
                ->sortByDesc('created_at')
                ->values();

            $firstUser = $users->first();
            $reasons = $users
                ->flatMap(fn ($groupUser) => $groupUser->duplicate_match_reasons ?? [])
                ->unique()
                ->values();

            $groupType = $reasons->contains('Passport') ? 'Passport' : 'Name';
            $groupValue = $groupType === 'Passport'
                ? ($firstUser->studentsData->passport_number ?? 'Unknown')
                : ($firstUser->full_name ?: 'Unknown');

            $groups->push((object) [
                'key' => 'group:' . $firstUser->id,
                'group_type' => $groupType,
                'group_value' => $groupValue,
                'reasons' => $reasons,
                'users' => $users,
                'count' => $users->count(),
                'latest_created_at' => optional($firstUser)->created_at,
            ]);
        }

        return $groups
            ->sortByDesc('latest_created_at')
            ->values();
    }

    protected function paginateCollection($items, Request $request, int $perPage = 20)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );
    }
    
    public function showUsersByCountry($country){
        $users = $this->approvedUsers()->where('country', $country)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByReligion($religion){
        $users = $this->approvedUsers()->where('religion', $religion)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByDepartment($department){
        $users = $this->approvedUsers()->where('department', $department)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function showUsersByCourse($course_type){
        $users = $this->approvedUsers()->where('course_type', $course_type)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }
    
    public function showUsersByCourseLanguage($course_language)
    {
        $users = $this->approvedUsers()->where('course_language', $course_language)->orderBy('room_number')->paginate(20);
        return view('admin.user_details', compact('users'));
    }

    public function usersByRoom()
    {
        $rooms = $this->approvedUsers()
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
        $users = $this->approvedUsers()
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
        $approvedUsers = $this->approvedUsers();

        // Fetch data from the database using approved users only
        $totalStudents = (clone $approvedUsers)->count();
        $totalStudentsList = (clone $approvedUsers)->count();
        $totalpendingstudent = User::where('approved', '0')->count();
        $notCompleteCount = (clone $approvedUsers)->where('medical_status', 'Not Complete')->count();
        $totalBangladeshiStudents = (clone $approvedUsers)->where('country', 'Bangladesh')->count();
        $totalIndianStudents = (clone $approvedUsers)->where('country', 'India')->count();
        $totalNepaliStudents = (clone $approvedUsers)->where('country', 'Nepal')->count();
        $maleStudents = (clone $approvedUsers)->where('gender', 'Male')->count();
        $femaleStudents = (clone $approvedUsers)->where('gender', 'Female')->count();

        // Students by religion
        $muslimStudents = (clone $approvedUsers)->where('religion', 'Muslim')->count();
        $hinduStudents = (clone $approvedUsers)->where('religion', 'Hindu')->count();
        $boddhoStudents = (clone $approvedUsers)->where('religion', 'Boddho')->count();
        $cristanStudents = (clone $approvedUsers)->where('religion', 'Cristan')->count();

        // Students by department
        $language = (clone $approvedUsers)->where('department', 'Prepetory Language Course')->count();
        $automobileStudents = (clone $approvedUsers)->where('department', 'Automobile')->count();
        $forestryStudents = (clone $approvedUsers)->where('department', 'Forestry')->count();
        $mechanicalStudents = (clone $approvedUsers)->where('department', 'Mechanical')->count();
        $cstStudents = (clone $approvedUsers)->where('department', 'Computer Science and Technology')->count();
        $economicsStudents = (clone $approvedUsers)->where('department', 'Economics')->count();
        
        // Fetch other departments
        $otherDepartments = $this->approvedUsers()
            ->select('department', DB::raw('COUNT(*) as count'))
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
        $courseLanguages = $this->approvedUsers()
            ->select('course_type', DB::raw('COUNT(*) as count'))
            ->groupBy('course_type')
            ->get();

        // Students fined by course
        $languageStudents = (clone $approvedUsers)->where('course_type', 'Language')->count();
        $bscStudents = (clone $approvedUsers)->where('course_type', 'BSC')->count();
        $mscStudents = (clone $approvedUsers)->where('course_type', 'MSC')->count();
        $phdStudents = (clone $approvedUsers)->where('course_type', 'PHD')->count();
        
        // Students fined by course language
        $englishStudents = (clone $approvedUsers)->where('course_language', 'English')->count();
        $russianStudents = (clone $approvedUsers)->where('course_language', 'Russian')->count();
        
        $pendingComplaints = Complaint::with('user')->where('status', 'pending')->get();
        $pendingComplaintsCount = Complaint::where('status', 'pending')->count();
        $unreadContactMessagesCount = ContactMessage::where('is_read', false)->count();
        $recentUsers = $this->recentUsersQuery()
            ->take(10)
            ->get();
        $recentUsersCount = $this->recentUsersQuery()->count();
        $duplicateUsers = $this->duplicateUsersCollection();
        $duplicateGroups = $this->duplicateGroupsCollection();
        $duplicateUsersCount = $duplicateGroups->count();
        

        // Return the view with the data
        return view('admin.dashboard', compact(
            'totalStudents', 'totalBangladeshiStudents', 'totalIndianStudents', 'totalNepaliStudents', 'maleStudents', 'femaleStudents',
            'muslimStudents', 'hinduStudents', 'boddhoStudents', 'cristanStudents',
            'language', 'automobileStudents', 'forestryStudents', 'mechanicalStudents', 'cstStudents', 'economicsStudents',
            'languageStudents', 'bscStudents', 'mscStudents', 'phdStudents','otherDepartments', 'englishStudents', 'russianStudents','totalStudentsList','notCompleteCount','totalpendingstudent',
            'pendingComplaints','pendingComplaintsCount','unreadContactMessagesCount', 'duplicateUsers', 'duplicateUsersCount', 'duplicateGroups',
            'recentUsers', 'recentUsersCount'
        ));
        
    }

    public function duplicateUsersList(Request $request)
    {
        $groups = $this->paginateCollection($this->duplicateGroupsCollection(), $request, 12);

        return view('admin.users.audit_list', [
            'title' => 'Duplicate Users',
            'description' => 'Users grouped by matching full name or passport number.',
            'emptyMessage' => 'No duplicate users found right now.',
            'groups' => $groups,
            'mode' => 'duplicate-groups',
        ]);
    }

    public function recentUsersList(Request $request)
    {
        $users = $this->recentUsersQuery()->paginate(20);

        return view('admin.users.audit_list', [
            'title' => 'New Users in Last 7 Days',
            'description' => 'Latest user registrations from the past seven days.',
            'emptyMessage' => 'No new users registered in the last 7 days.',
            'users' => $users,
            'mode' => 'recent-users',
            'showDuplicateReasons' => false,
        ]);
    }
    
    public function showByCourseLanguage($course_language)
    {
        $users = $this->approvedUsers()->where('course_language', $course_language)->get();
    
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
    
            $otherDepartments = $this->approvedUsers()
                ->whereNotIn('department', $predefinedDepartments)
                ->whereNotNull('department')
                ->distinct()
                ->pluck('department');
    
            // Return the data as JSON
            return response()->json($otherDepartments);
        }
    
     public function studentList(Request $request)
        {
            $search = $request->string('search')->toString();
            $totalStudentsList = $this->studentListQuery($search)
                ->paginate(20)
                ->withQueryString();
            
            $totalStudentsListmedicalcomplete = $this->approvedUsers()
                                       ->orderByRaw("FIELD(medical_status, 'Not Complete', 'Complete') DESC")
                                       ->orderBy('medical_status')
                                       ->paginate(20);

            if ($request->ajax()) {
                return response()->json([
                    'rows' => view('admin.partials.studentlist_rows', compact('totalStudentsList'))->render(),
                    'pagination' => $totalStudentsList->links()->render(),
                    'count' => $totalStudentsList->total(),
                ]);
            }
    
            return view('admin.studentlist', compact('totalStudentsList', 'totalStudentsListmedicalcomplete', 'search'));
        }

    public function studentListPrint(Request $request)
    {
        $search = $request->string('search')->toString();
        $students = $this->studentListQuery($search)->get();

        return view('admin.studentlist_print', [
            'students' => $students,
            'search' => $search,
        ]);
    }
        
        
    public function studentListmedical(Request $request)
    {
        // Fetch and sort students with "Not Complete" first
        $totalStudentsListmedical = $this->approvedUsers()
            ->where('medical_status', '!=', 'Complete')
            ->orderByRaw("FIELD(medical_status, 'Not Complete', 'Complete')")
            ->paginate(20);
        
         $totalStudentsCount = $this->approvedUsers()->count();
         
        // Count students with "Not Complete" status
        $notCompleteCount = $this->approvedUsers()->where('medical_status', 'Not Complete')->count();
    
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
            
            $medical1NotComplete = $this->approvedUsers()->whereNull('medical1')->count();
            $medical2NotComplete = $this->approvedUsers()->whereNull('medical2')->count();
        
            return response()->json([
                'success' => true,
                'new_status' => $student->medical_status,
                'medical1_not_complete' => $medical1NotComplete,
                'medical2_not_complete' => $medical2NotComplete
            ]);
        }

}
