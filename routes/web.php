<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DescriptionController;
//use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AdminCustomFieldController;
use App\Http\Controllers\UserFieldDataController;
use App\Http\Controllers\AdminFieldDataController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\StudentsDataController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\CustomFieldOptionController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\StudentByReligionController;
use App\Http\Controllers\StudyDestinationController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\HeaderMenuController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\HomepageCourseController;
use App\Http\Controllers\Admin\HomepagePageController;
use App\Http\Controllers\Admin\SmtpSettingController;
use App\Http\Controllers\Admin\StudyDestinationController as AdminStudyDestinationController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\CampaignSubmissionController as AdminCampaignSubmissionController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminEmailNotificationController;
use App\Http\Controllers\Admin\UserEditPermissionController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CoursePageController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ClassRoutineController;
use App\Http\Controllers\VgltuLoginPanelController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::redirect('/vgltu-home', '/');
Route::get('/all-students', [WelcomeController::class, 'allStudents'])->name('students.all');
Route::get('/all-passed-students', [WelcomeController::class, 'allPassedStudents'])->name('passed-students.all');
Route::post('/alumni-network/submit', [StudentController::class, 'submitAlumni'])->name('alumni-network.submit');
Route::get('/all-photo-galleries', [WelcomeController::class, 'allPhotoCategories'])->name('photo-galleries.all');
Route::get('/all-video-galleries', [WelcomeController::class, 'allVideoCategories'])->name('video-galleries.all');
Route::get('/study-destinations/{slug}', [StudyDestinationController::class, 'show'])->name('study-destinations.show');
Route::view('/about-university', 'about-university')->name('about-university');
Route::get('/department', [CoursePageController::class, 'index'])->name('department.page');
Route::get('/department/{course:slug}', [CoursePageController::class, 'show'])->name('department.show');
Route::view('/courses', 'course-page')->name('courses.page');
Route::view('/contact-us', 'contact')->name('contact.page');
Route::post('/contact-messages', [ContactMessageController::class, 'store'])->name('contact-messages.store');

// Photo
Route::get('/category/{id}/photos/{subCategoryId?}', [WelcomeController::class, 'showPhotos'])->name('category.photos');
Route::get('/category/{category_id}/subcategory/{subcategory_id}', [WelcomeController::class, 'showSubCategoryPhotos'])
    ->name('subcategory.photos');
Route::get('/category/photos/{id}', [WelcomeController::class, 'showPhotos']);

//video
Route::get('/category/videos/{id}', [WelcomeController::class, 'showVideos'])->name('category.videos');
Route::get('/category/{id}/videos/{subCategoryId?}', [WelcomeController::class, 'showVideos'])
    ->name('category.videos.with-subcategory');
Route::get('/category/{category_id}/subcategory/{subcategory_id}/videos', [WelcomeController::class, 'showSubCategoryVideos'])
    ->name('subcategory.videos');




Auth::routes();

//User Profile

Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/dashboard');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');

Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::put('/user/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/medical-status', [HomeController::class, 'view'])->name('user.medicalStatus'); 
    Route::get('/medical-status/get', [HomeController::class, 'getMedicalStatus'])->name('user.getMedicalStatus');
    Route::post('/medical-status/update', [HomeController::class, 'updateMedicalStatus'])->name('user.updateMedicalStatus');
    Route::get('/student-card/download', [HomeController::class, 'downloadStudentCard'])->name('user.studentCard.download');
});



// User Routes for submit and view
Route::get('user/custom-fields', [UserFieldDataController::class, 'create'])->name('user.custom-fields.create')->middleware('auth');
Route::post('user/custom-fields', [UserFieldDataController::class, 'store'])->name('user.custom-fields.store')->middleware('auth');
Route::post('/user-fields/store', [UserFieldDataController::class, 'store'])->name('user-fields.store')->middleware('auth');
Route::post('/user-field-data', [UserFieldDataController::class, 'store'])->name('user-field-data.store')->middleware('auth');
Route::get('/user-field-data/{id}/edit', [UserFieldDataController::class, 'edit'])->name('user-field-data.edit')->middleware('auth');
Route::post('user-field-data/update/{id}', [UserFieldDataController::class, 'update'])->name('user-field-data.update')->middleware('auth');
    
Route::get('user/custom-fields/data', [UserFieldDataController::class, 'index'])->name('user.custom-fields.index')->middleware('auth');

Route::get('user/custom-fields/existing', [UserFieldDataController::class, 'existingCreate'])->middleware('auth');
Route::post('user/custom-fields/existing', [UserFieldDataController::class, 'existingStore'])->middleware('auth')->name('user-fields.existing-store');





// Student Data Collect

Route::get('/students-data', [StudentsDataController::class, 'index'])->name('students_data.index')->middleware('auth');
Route::get('/students-data/create', [StudentsDataController::class, 'create'])->name('students_data.create')->middleware('auth');
Route::post('/students-data/store', [StudentsDataController::class, 'store'])->name('students_data.store')->middleware('auth');
Route::get('/students-data/{id}/edit', [StudentsDataController::class, 'edit'])->name('students_data.edit')->middleware('auth');
Route::put('/students-data/{id}/update', [StudentsDataController::class, 'update'])->name('students_data.update')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{campaign}', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/notifications/{notification}/open', [UserNotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/{notification}/read', [UserNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/browser-preference', [UserNotificationController::class, 'browserPreference'])->name('notifications.browser-preference');
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');
    Route::post('/notifications/push-subscriptions', [PushSubscriptionController::class, 'subscribe'])->name('notifications.push-subscriptions.store');
    Route::delete('/notifications/push-subscriptions', [PushSubscriptionController::class, 'unsubscribe'])->name('notifications.push-subscriptions.destroy');
    Route::get('/notifications/feed', [UserNotificationController::class, 'feed'])->name('notifications.feed');
});

//IFrame for Loding university class Routine and University Profile
Route::get('/class_routine', [ClassRoutineController::class, 'show'])->name('class-routine.show');
Route::match(['GET', 'POST'], '/class_routine/proxy', [ClassRoutineController::class, 'proxy'])->name('class-routine.proxy-root');
Route::match(['GET', 'POST'], '/class_routine/proxy/{path}', [ClassRoutineController::class, 'proxy'])
    ->where('path', '.*')
    ->name('class-routine.proxy');
Route::get('/university-student-profile', [VgltuLoginPanelController::class, 'show'])->name('university-student-profile.show');
Route::get('/university-student-profile/reset', [VgltuLoginPanelController::class, 'reset'])->name('university-student-profile.reset');
Route::match(['GET', 'POST'], '/university-student-profile/proxy', [VgltuLoginPanelController::class, 'proxy'])->name('university-student-profile.proxy-root');
Route::match(['GET', 'POST'], '/university-student-profile/proxy/{path}', [VgltuLoginPanelController::class, 'proxy'])
    ->where('path', '.*')
    ->name('university-student-profile.proxy');


//Admin Section

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('two-factor/challenge', [AdminTwoFactorController::class, 'showChallenge'])->name('admin.two-factor.challenge');
    Route::post('two-factor/challenge', [AdminTwoFactorController::class, 'verifyChallenge'])->name('admin.two-factor.verify');
    Route::get('two-factor/setup', [AdminTwoFactorController::class, 'showSetup'])->name('admin.two-factor.setup');
    Route::post('two-factor/setup', [AdminTwoFactorController::class, 'confirmSetup'])->name('admin.two-factor.confirm');
    Route::post('two-factor/cancel', [AdminTwoFactorController::class, 'cancel'])->name('admin.two-factor.cancel');

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware('auth:admin');
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware('admin')->group(function () {


// Route to display the list of users based on selected category (nationality, religion, department, etc.)
Route::get('admin/users/list', [AdminUserController::class, 'index'])->name('admin.users.index');

// Student List
Route::get('/admin/studentlist', [AdminDashboardController::class, 'studentList'])->name('admin.studentlist');
Route::get('/admin/studentlist/print', [AdminDashboardController::class, 'studentListPrint'])->name('admin.studentlist.print');

Route::get('/admin/studentlistmedical', [AdminDashboardController::class, 'studentListmedical'])->name('admin.studentlistmedical');
Route::post('/admin/update-medical-status', [AdminDashboardController::class, 'updateMedicalStatus'])
    ->name('admin.updateMedicalStatus');


// Route to view a single user's details
Route::get('admin/users/view/{id}', [AdminUserController::class, 'view'])->name('admin.users.view');
Route::get('/admin/users/country/{country}', [AdminDashboardController::class, 'showUsersByCountry']);
// Route to edit a user's details
Route::get('admin/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');

// Route to update a user's details
Route::post('admin/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');

// Route to delete a user
Route::delete('admin/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');

//Check pending
Route::get('admin/pending-users', [AdminController::class, 'pendingUsers'])->name('admin.viewPendingUsers');
Route::post('admin/approve-user/{id}', [AdminController::class, 'approveUser'])->name('admin.approveUser');
Route::post('admin/reject-user/{id}', [AdminController::class, 'rejectUser'])->name('admin.rejectUser');


//Routes for download pdf
Route::get('/admin/users/{id}/download-pdf', [AdminUserController::class, 'downloadPDF'])->name('admin.users.pdf');

// Route to display the search
Route::get('admin/users/search', [SearchController::class, 'showSearchForm'])->name('search.form');
Route::get('/admin/users/search/results', [SearchController::class, 'search'])->name('search');

Route::get('/admin/user-rooms', [AdminDashboardController::class, 'usersByRoom'])->name('admin.users.by-room');
Route::get('/admin/user-rooms/{roomNumber}', [AdminDashboardController::class, 'usersByRoomShow'])->name('admin.users.by-room.show');
Route::get('/admin/audit/duplicate-users', [AdminDashboardController::class, 'duplicateUsersList'])->name('admin.audit.duplicate-users');
Route::get('/admin/audit/recent-users', [AdminDashboardController::class, 'recentUsersList'])->name('admin.audit.recent-users');

// Routes for users by category
Route::get('admin/users/{category}/{value?}', [UserController::class, 'listByCategory'])->name('admin.users.list');

Route::get('/get-other-departments', [UserController::class, 'getOtherDepartments']);


//Find User

Route::get('/admin/users/religion/{religion}', [AdminDashboardController::class, 'showUsersByReligion']);
Route::get('/admin/users/department/{department}', [AdminDashboardController::class, 'showUsersByDepartment']);
Route::get('/admin/dashboard/course_type/{course_type}', [AdminDashboardController::class, 'showUsersByCourse'])->name("admin.users.crouse_type_list");
Route::get('admin/dashboard/course_language/{course_language}', [AdminDashboardController::class, 'showUsersByCourseLanguage'])->name('admin.users.course_language');

Route::post('/admin/forget-password/{id}', [AdminUserController::class, 'forgetPassword'])
    ->name('admin.forgetPassword');


Route::resource('admin/sliders', SliderController::class);

Route::prefix('admin/homepage')->name('admin.homepage.')->group(function () {
    Route::get('/settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [WebsiteSettingController::class, 'update'])->name('settings.update');
    Route::get('/about-university', [HomepagePageController::class, 'editAboutUniversity'])->name('pages.about-university.edit');
    Route::put('/about-university', [HomepagePageController::class, 'updateAboutUniversity'])->name('pages.about-university.update');
    Route::get('/courses', [HomepagePageController::class, 'editCourses'])->name('pages.courses.edit');
    Route::put('/courses', [HomepagePageController::class, 'updateCourses'])->name('pages.courses.update');
    Route::get('/courses/create-item', [HomepageCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses/create-item', [HomepageCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit-item', [HomepageCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}/edit-item', [HomepageCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [HomepageCourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/hero', [HeroSectionController::class, 'edit'])->name('hero.edit');
    Route::put('/hero', [HeroSectionController::class, 'update'])->name('hero.update');
    Route::resource('menus', HeaderMenuController::class)->except(['show']);
    Route::resource('destinations', AdminStudyDestinationController::class)->except(['show']);
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/user-edit-permissions', [UserEditPermissionController::class, 'edit'])->name('user-edit-permissions.edit');
    Route::put('/user-edit-permissions', [UserEditPermissionController::class, 'update'])->name('user-edit-permissions.update');
});

Route::middleware(['admin'])->prefix('admin/settings')->name('admin.smtp.')->group(function () {
    Route::get('/smtp', [SmtpSettingController::class, 'edit'])->name('edit');
    Route::put('/smtp', [SmtpSettingController::class, 'update'])->name('update');
});

//Students Upload
Route::prefix('admin')->group(function () {
    Route::post('students/{student}/approve', [StudentController::class, 'approve'])->name('students.approve');
    Route::post('students/{student}/reject', [StudentController::class, 'reject'])->name('students.reject');
    Route::resource('students', StudentController::class);
});

Route::prefix('admin/description')->group(function () {
    Route::get('/', [DescriptionController::class, 'index'])->name('description.index');
    Route::get('/create', [DescriptionController::class, 'create'])->name('description.create');
    Route::post('/store', [DescriptionController::class, 'store'])->name('description.store');
    Route::get('/edit/{id}', [DescriptionController::class, 'edit'])->name('description.edit');
    Route::post('/update/{id}', [DescriptionController::class, 'update'])->name('description.update');
    Route::delete('/delete/{id}', [DescriptionController::class, 'destroy'])->name('description.delete');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/upload', [UploadController::class, 'create'])->name('upload.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::get('/upload/view', [UploadController::class, 'index'])->name('upload.index');
    Route::get('/upload/edit/{id}', [UploadController::class, 'edit'])->name('upload.edit');
    Route::put('/upload/update/{id}', [UploadController::class, 'update'])->name('upload.update');
    Route::delete('/upload/{id}', [UploadController::class, 'destroy'])->name('upload.destroy');
   

});

Route::get('get-categories/{typeId}', [CategoryController::class, 'getCategories']);
Route::get('/get-subcategories/{categoryId}', [CategoryController::class, 'getSubCategories']); 

// Admin Routes for create fields
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('custom-fields', AdminCustomFieldController::class);
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/user-custom-data', [AdminFieldDataController::class, 'index'])->name('admin.user-custom-data.index');
    Route::delete('/user-custom-data/{userId}/{fieldId}', [AdminFieldDataController::class, 'destroyFieldData'])->name('admin.user-custom-data.destroy-field-data');
    Route::post('/user-custom-data/status/{userId}', [AdminFieldDataController::class, 'changeStatus'])->name('admin.user-custom-data.change-status');
    Route::post('/user-custom-data/{userId}/{fieldId}/status', [AdminFieldDataController::class, 'updateStatus'])->name('admin.user-custom-data.update-status');
    Route::get('/user-custom-data', [AdminFieldDataController::class, 'index'])->name('admin.user-custom-data.index');
    Route::get('/user-custom-data/solved', [AdminFieldDataController::class, 'solvedData'])->name('admin.user-custom-data.solved');
    


});



Route::prefix('admin/custom-fields/options')->middleware('admin')->group(function () {
    Route::get('{option}/edit', [CustomFieldOptionController::class, 'edit'])->name('admin.custom-fields.options.edit');
    Route::post('{option}', [CustomFieldOptionController::class, 'update'])->name('admin.custom-fields.options.update');
    Route::delete('{option}', [CustomFieldOptionController::class, 'destroy'])->name('admin.custom-fields.options.destroy');
});

// In routes/web.php
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function() {
    Route::get('form-submissions', [FormSubmissionController::class, 'index'])
         ->name('form-submissions.index');

// Route to show users who submitted data for a specific field
Route::get('form-submissions/view-users/{fieldId}', [FormSubmissionController::class, 'viewUsers'])
    ->name('form-submissions.view-users');

});

// In routes/web.php
Route::get('admin/form-submissions/view-option/{field_id}/{option}', [FormSubmissionController::class, 'viewOptionUsers'])
    ->middleware('admin')
    ->name('admin.form-submissions.view-option');

Route::get('/admin/form-submissions/view-users/{field_id}', [FormSubmissionController::class, 'viewUsers'])
    ->middleware('admin')
    ->name('admin.form-submissions.view-users-legacy');

Route::post('/user-custom-data/{userId}/{valueId}/status', [FormSubmissionController::class, 'changeStatus'])
    ->middleware('admin')
    ->name('admin.user-from-submission-data.update-value-status-legacy');
Route::delete('/user-custom-data/{userId}/{valueId}/delete', [FormSubmissionController::class, 'destroyFieldData'])
    ->middleware('admin')
    ->name('admin.user-from-submission-data.delete-value-data');

Route::post('/admin/user-from-submission-data/update-value-status/{userId}/{valueId}', [FormSubmissionController::class, 'changeStatus'])
    ->middleware('admin')
    ->name('admin.user-from-submission-data.update-value-status');








Route::middleware(['admin'])->group(function () {
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/reset-password/{id}', [AdminProfileController::class, 'resetPassword'])->name('admin.profile.reset_password');
    Route::post('/admin/profile/two-factor/enable', [AdminTwoFactorController::class, 'enableFromProfile'])->name('admin.profile.two-factor.enable');
    Route::post('/admin/profile/two-factor/disable', [AdminTwoFactorController::class, 'disable'])->name('admin.profile.two-factor.disable');
    Route::post('/admin/profile/two-factor/recovery-codes', [AdminTwoFactorController::class, 'regenerateRecoveryCodes'])->name('admin.profile.two-factor.recovery-codes');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});


Route::prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);

    // Route for creating subcategories
    Route::post('subcategories', [CategoryController::class, 'storeSubCategory'])->name('subcategories.store');

    // Route for deleting subcategories
    Route::delete('subcategories/{subCategory}', [CategoryController::class, 'destroySubCategory'])->name('subcategories.custom-destroy');

    Route::resource('subcategories', CategoryController::class);
    
});

Route::post('/upload/media', [UploadController::class, 'storeMedia'])->name('upload.media');
Route::post('/admin/upload/storeMedia', [UploadController::class, 'storeMedia'])->middleware('admin')->name('admin.upload.storeMedia');
// Inside routes/web.php

// Inside routes/web.php
Route::post('/media/upload', [UploadController::class, 'storeMedia'])->name('media.upload');


Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/studentsdata', [AdminStudentController::class, 'index'])->name('studentsdata.index');
    Route::get('/studentsdata/{id}/edit', [AdminStudentController::class, 'edit'])->name('studentsdata.edit');
    Route::put('/studentsdata/{id}', [AdminStudentController::class, 'update'])->name('studentsdata.update');
    Route::delete('/studentsdata/{id}', [AdminStudentController::class, 'destroy'])->name('studentsdata.delete');
});

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/complaints', [ComplaintController::class, 'adminIndex'])->name('complaints.index');
    Route::get('/complaints/in-progress', [ComplaintController::class, 'inProgress'])->name('complaints.inProgress');
    Route::get('/complaints/solved', [ComplaintController::class, 'solved'])->name('complaints.solved');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'adminShow'])->name('complaints.show');
    Route::post('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::get('/complaints/in-progress', [ComplaintController::class, 'inProgress'])->name('complaints.inProgress');
    Route::get('/complaints/solved', [ComplaintController::class, 'solved'])->name('complaints.solved');
    Route::resource('/campaigns', AdminCampaignController::class)->except(['show']);
    Route::get('/campaigns/{campaign}/submissions', [AdminCampaignSubmissionController::class, 'index'])->name('campaigns.submissions');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::get('/email-notifications', [AdminEmailNotificationController::class, 'index'])->name('email-notifications.index');
    Route::get('/email-notifications/create', [AdminEmailNotificationController::class, 'create'])->name('email-notifications.create');
    Route::post('/email-notifications', [AdminEmailNotificationController::class, 'store'])->name('email-notifications.store');
    Route::get('/contact-messages', [ContactMessageController::class, 'adminIndex'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'adminShow'])->name('contact-messages.show');
    Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
});

Route::get('admin/dashboard/students-by-floor', [StudentByReligionController::class, 'index'])->middleware('admin')->name('students.by.religion');
Route::get('admin/dashboard/students-by-floor/pdf', [StudentByReligionController::class, 'downloadPdf'])->middleware('admin')->name('students.by.religion.pdf');
Route::get('admin/dashboard/students-by-block/{block}', [StudentByReligionController::class, 'showBlock'])->middleware('admin')->name('students.by.block');

});
