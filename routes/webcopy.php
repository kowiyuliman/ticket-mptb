<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::get('/dashboard',[DashboardController::class,'index'])
->middleware(['auth'])->name('dashboard');



Route::middleware(['auth'])->group(function(){

    Route::get('/create-ticket',[TicketController::class,'create']);
    Route::post('/create-ticket',[TicketController::class,'store']);
    Route::get('/my-tickets',[TicketController::class,'index']);
    Route::get('/ticket/{id}',[TicketController::class,'show']);
    
});

Route::middleware(['auth'])->group(function(){

    Route::get('/admin/tickets',[AdminTicketController::class,'index']);

    Route::get('/admin/ticket/show/{id}',[AdminTicketController::class,'show'])->middleware(['auth','admin']);

    Route::get('/admin/ticket/edit/{id}', [AdminTicketController::class,'edit'])->middleware(['auth','admin']);

    // Route::post('/admin/ticket/update/{id}', [AdminTicketController::class,'update'])->middleware(['auth','admin']);

    Route::post('/admin/ticket/update/{id}',[AdminTicketController::class,'update']);

    Route::post('/admin/ticket/comment/{id}', [AdminTicketController::class,'comment'])->middleware('auth');

    Route::delete('/admin/ticket/delete/{id}',[AdminTicketController::class,'destroy'])->middleware('auth');

    Route::get('/admin/report',[ReportController::class,'index'])->middleware('auth');

    Route::get('/admin/report/export',[ReportController::class,'export'])->middleware('auth');

    // semua tiket (open)
    Route::get('/admin/ticket/tickets', [AdminTicketController::class,'index']);

    // my ticket
    Route::get('/admin/ticket/my-ticket', [AdminTicketController::class,'myTicket']);

    // ambil tiket
    Route::get('/admin/ticket/ticket/take/{id}', [AdminTicketController::class,'takeTicket']);


    
    Route::get('/notification/{id}', function($id){
    $notification = auth()->user()->notifications()->findOrFail($id);
    // tandai sudah dibaca
    $notification->markAsRead();

    $notification->delete(); // 🔥 langsung hapus

    // cek apakah ada URL
    $url = $notification->data['url'] ?? '/dashboard';

    // redirect aman
    return redirect($url);
    });

    Route::get('/notifications/read', function(){
    auth()->user()->unreadNotifications->markAsRead();
    return back();
    });
    

    Route::delete('/notifications/clear', function () {
    auth()->user()->notifications()->delete(); // hapus semua
    return back()->with('success', 'Notifikasi berhasil dibersihkan');
    })->middleware('auth');

});


require __DIR__.'/auth.php';
