<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

// Temporärer Test-Route
Route::get('/test-login', function() {
    $credentials = [
        'email' => 'reichelt@myparkplatz24.de',
        'password' => 'password'
    ];
    
    $user = User::where('email', $credentials['email'])->first();
    
    dd([
        'credentials' => $credentials,
        'user_exists' => $user ? 'yes' : 'no',
        'stored_password' => $user ? $user->password : 'no user',
        'auth_attempt' => Auth::attempt($credentials),
        'auth_check' => Auth::check(),
        'current_user' => Auth::user(),
        'would_validate' => $user ? $user->validateCredentials($credentials) : 'no user',
    ]);
});

Route::get('/report/{id}/generate-warning-pdf', [App\Http\Controllers\ReportController::class, 'generateWarningPDF'])->name('report.generateWarningPDF');
