<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::middleware(['guest', 'throttle.custom:10,1'])->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    
    // Login OTP Verify
    Route::get('/login/verify-otp', [AuthController::class, 'showLoginVerify'])->name('login.verify');
    Route::post('/login/verify-otp', [AuthController::class, 'verifyLogin'])->name('login.verify.submit');
    Route::post('/login/resend-otp', [AuthController::class, 'resendLoginOtp'])->name('login.resend-otp');

    // Forgot Password Flow
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');

    // Verify Email OTP (Reset)
    Route::get('/forgot-password/verify-otp', [AuthController::class, 'showVerifyResetOtp'])->name('password.verify-otp');
    Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyResetOtp'])->name('password.verify-otp.submit');

    // Reset Password Form
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset'); // GET pour afficher
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

// Redirection intelligente si tentative d'accès à /dashboard général
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('hr.dashboard');
    } elseif ($user->isManagerRH()) {
        return redirect()->route('hr.dashboard');
    }
    return redirect()->route('hr.dashboard');
})->middleware('auth')->name('dashboard');

// Routes Profil (Accessibles Admin & Employé)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Routes Human Resources (RH)
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    // Dashboard commun
    Route::get('/dashboard', [App\Http\Controllers\HumanResourcesController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des employés (Admin et Manager RH)
    Route::middleware(['role:manager_rh'])->group(function () {
        Route::get('/employees', [App\Http\Controllers\HumanResourcesController::class, 'employeesIndex'])->name('employees.index');
        Route::get('/employees/create', [App\Http\Controllers\HumanResourcesController::class, 'employeesCreate'])->name('employees.create');
        Route::post('/employees', [App\Http\Controllers\HumanResourcesController::class, 'employeesStore'])->name('employees.store');
        Route::get('/employees/{employee}', [App\Http\Controllers\HumanResourcesController::class, 'employeesShow'])->name('employees.show');
        Route::get('/employees/{employee}/edit', [App\Http\Controllers\HumanResourcesController::class, 'employeesEdit'])->name('employees.edit');
        Route::put('/employees/{employee}', [App\Http\Controllers\HumanResourcesController::class, 'employeesUpdate'])->name('employees.update');
        Route::delete('/employees/{employee}', [App\Http\Controllers\HumanResourcesController::class, 'employeesDestroy'])->name('employees.destroy');
        Route::get('/employees/export/excel', [App\Http\Controllers\HumanResourcesController::class, 'exportEmployeesExcel'])->name('employees.export.excel');
        Route::get('/employees/{employee}/export/pdf', [App\Http\Controllers\HumanResourcesController::class, 'exportEmployeePDF'])->name('employees.export.pdf');
    });
    
    // Gestion des présences (Admin et Manager RH)
    Route::middleware(['role:manager_rh'])->group(function () {
        Route::get('/attendance', [App\Http\Controllers\HumanResourcesController::class, 'attendanceIndex'])->name('attendance.index');
        Route::get('/attendance/create', [App\Http\Controllers\HumanResourcesController::class, 'attendanceCreate'])->name('attendance.create');
        Route::post('/attendance', [App\Http\Controllers\HumanResourcesController::class, 'attendanceStore'])->name('attendance.store');
        Route::get('/attendance/{attendance}/edit', [App\Http\Controllers\HumanResourcesController::class, 'attendanceEdit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [App\Http\Controllers\HumanResourcesController::class, 'attendanceUpdate'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [App\Http\Controllers\HumanResourcesController::class, 'attendanceDestroy'])->name('attendance.destroy');
    });
    
    // Gestion des congés
    Route::get('/leaves', [App\Http\Controllers\HumanResourcesController::class, 'leavesIndex'])->name('leaves.index')->middleware('role:manager_rh');
    Route::get('/leaves/create', [App\Http\Controllers\HumanResourcesController::class, 'leavesCreate'])->name('leaves.create');
    Route::post('/leaves', [App\Http\Controllers\HumanResourcesController::class, 'leavesStore'])->name('leaves.store');
    Route::post('/leaves/{leave}/approve', [App\Http\Controllers\HumanResourcesController::class, 'leavesApprove'])->name('leaves.approve')->middleware('role:manager_rh');
    Route::post('/leaves/{leave}/reject', [App\Http\Controllers\HumanResourcesController::class, 'leavesReject'])->name('leaves.reject')->middleware('role:manager_rh');
    Route::get('/leaves/my', [App\Http\Controllers\HumanResourcesController::class, 'myLeaves'])->name('leaves.my');
});

// Routes Admin
Route::middleware(['auth', App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des Administrateurs
    Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
    Route::patch('admins/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleStatus'])->name('admins.toggle-status');
    Route::post('admins/destroy-group', [App\Http\Controllers\Admin\AdminController::class, 'destroyGroup'])->name('admins.destroy-group');
    Route::post('admins/toggle-status-group', [App\Http\Controllers\Admin\AdminController::class, 'toggleStatusGroup'])->name('admins.toggle-status-group');
    Route::post('admins/download-pdf', [App\Http\Controllers\Admin\AdminController::class, 'downloadPdf'])->name('admins.download-pdf');
    Route::post('admins/download-excel', [App\Http\Controllers\Admin\AdminController::class, 'downloadExcel'])->name('admins.download-excel');
    Route::put('admins/{id}/password', [App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('admins.update-password');
    
    // Gestion des Employés
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
    
    // Gestion des Projets
    Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class);
    Route::get('projects/{id}/download-tasks-pdf', [App\Http\Controllers\Admin\ProjectController::class, 'downloadTasksPdf'])->name('projects.download-tasks-pdf');
    
    // Gestion des Tâches
    Route::resource('tasks', App\Http\Controllers\Admin\TaskController::class);
    
    // Audit Global
    Route::get('audits', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audits.index');
    Route::get('employees/{id}/tasks', [App\Http\Controllers\Admin\EmployeeController::class, 'tasks'])->name('employees.tasks');
    Route::patch('employees/{id}/toggle-status', [App\Http\Controllers\Admin\EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::post('employees/destroy-group', [App\Http\Controllers\Admin\EmployeeController::class, 'destroyGroup'])->name('employees.destroy-group');
    Route::post('employees/toggle-status-group', [App\Http\Controllers\Admin\EmployeeController::class, 'toggleStatusGroup'])->name('employees.toggle-status-group');
    Route::post('employees/download-pdf', [App\Http\Controllers\Admin\EmployeeController::class, 'downloadPdf'])->name('employees.download-pdf');
    Route::post('employees/download-excel', [App\Http\Controllers\Admin\EmployeeController::class, 'downloadExcel'])->name('employees.download-excel');
    Route::put('employees/{id}/password', [App\Http\Controllers\Admin\EmployeeController::class, 'updatePassword'])->name('employees.update-password');
    
    // Autres routes...
});

// Routes Employé
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
     Route::get('/dashboard', [App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
     Route::resource('tasks', App\Http\Controllers\Employee\TaskController::class)->only(['index', 'show', 'update']);
     Route::resource('projects', App\Http\Controllers\Employee\ProjectController::class)->only(['index', 'show']);
});
