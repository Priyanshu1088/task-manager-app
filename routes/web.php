<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;

// Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Prevent direct GET /login
Route::get('/login', function () {
    return redirect()->route('login');
});
// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Dashboards
Route::get('/manager/dashboard', [ManagerController::class, 'dashboard'])
->middleware('manager')    
->name('manager.dashboard');

Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])
->middleware('employee')    
->name('employee.dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/manager/employees', [ManagerController::class, 'employees'])
    ->name('manager.employees')
    ->middleware('manager');

Route::get('/manager/employees/edit/{id}', [ManagerController::class, 'editEmployee'])
    ->name('manager.employees.edit')
    ->middleware('manager');

Route::put('/manager/employees/{id}', [ManagerController::class, 'updateEmployee'])
    ->name('manager.employees.update')
    ->middleware('manager');

Route::delete('/manager/employees/delete/{id}', [ManagerController::class, 'deleteEmployee'])
    ->name('manager.employees.delete')
    ->middleware('manager');

    // Project List
Route::get('/manager/projects', [ManagerController::class, 'projects'])
    ->name('manager.projects')
    ->middleware('manager');

// Show Create Form
Route::get('/manager/projects/create', [ManagerController::class, 'createProject'])
    ->name('manager.projects.create')
    ->middleware('manager');

// Store Project
Route::post('/manager/projects', [ManagerController::class, 'storeProject'])
    ->name('manager.projects.store')
    ->middleware('manager');

Route::get('/manager/projects/{id}', [ManagerController::class, 'showProject'])
->name('manager.projects.show')
->middleware('manager');

Route::post('/manager/projects/{id}/tasks',
[ManagerController::class, 'storeTask'])
->name('manager.tasks.store')
->middleware('manager');

Route::get('/employee/tasks',
[EmployeeController::class,'projects'])
->name('employee.tasks')
->middleware('employee');

Route::post('/employee/tasks/{id}/status',
[EmployeeController::class,'updateStatus'])
->name('employee.tasks.status')
->middleware('employee');

Route::get('/manager/tasks/{id}/edit',
[ManagerController::class,'editTask'])
->name('manager.tasks.edit')
->middleware('manager');

Route::put('/manager/tasks/{id}',
    [ManagerController::class,'updateTask'])
    ->name('manager.tasks.update')
    ->middleware('manager');

Route::delete('/manager/tasks/{id}',
    [ManagerController::class,'deleteTask'])
    ->name('manager.tasks.delete')
    ->middleware('manager');

Route::get('/manager/employees/{id}',
[ManagerController::class,'showEmployee'])
->name('manager.employees.show')
->middleware('manager');

Route::delete('/manager/projects/{id}/delete',
    [ManagerController::class,'deleteProject'])
    ->name('manager.projects.delete');

Route::put('/manager/projects/{id}',
    [ManagerController::class,'updateProject'])
    ->name('manager.projects.update')
    ->middleware('manager');

    Route::post('/employee/tasks/{id}/start',
    [EmployeeController::class,'startTask'])
    ->name('employee.tasks.start')
    ->middleware('employee');

    Route::get('/employee/project/{id}/tasks', [EmployeeController::class,'projectTasks'])
    ->name('employee.project.tasks');

    Route::get('/employee/project/{id}/tasks', [EmployeeController::class, 'projectTasks'])
    ->name('employee.project.tasks');

    Route::put('/employee/task/{id}/update',[EmployeeController::class,'updateTask'])
    ->name('employee.task.update');

    Route::put('/employee/task/{id}/complete',[EmployeeController::class,'completeTask'])
    ->name('employee.tasks.complete');

    Route::get('/manager/employees/{id}/tasks',[ManagerController::class,'employeeTasks'])
    ->name('manager.employee.tasks');

    Route::get('/manager/employees/search',[ManagerController::class,'searchEmployees'])
    ->name('manager.employees.search');

    Route::get('/employee/project/{id}/task/create',[EmployeeController::class,'createTask'])
    ->name('employee.tasks.create');

    Route::post('/employee/project/{id}/task/store',[EmployeeController::class,'storeTask'])
    ->name('employee.tasks.store');

    Route::get('/notifications/read', [ManagerController::class, 'markRead'])
    ->name('notifications.read');

    Route::get('/manager/tasks', [ManagerController::class, 'allTasks'])
    ->name('manager.tasks');

    Route::post('/employee/check-in', [EmployeeController::class, 'checkIn'])
    ->name('employee.checkin');

    Route::get('/manager/attendance', [ManagerController::class, 'attendance'])
    ->name('manager.attendance');

    Route::post('/employee/checkout', [EmployeeController::class, 'checkOut'])
    ->name('employee.checkout');

    Route::post('/employee/checkout', [EmployeeController::class, 'checkOut'])
    ->name('employee.checkout');

    Route::get('/manager/attendance/history', [AttendanceController::class, 'attendanceHistory']);

    Route::post('/notifications/clear', [ManagerController::class, 'clearNotifications'])
     ->name('notifications.clear');

    Route::get('/notifications/history', [ManagerController::class, 'last7Days'])
    ->name('notifications.history');