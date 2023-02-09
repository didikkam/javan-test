<?php

use App\Http\Controllers\Admin\ChildController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
   // return view('welcome');
   return redirect('/admin/dashboard');
})->name('index');


Route::group(['as' => 'auth.'], function () {
   Route::group(['middleware' => 'auth'], function () {
      Route::post('logout', [LoginController::class, 'logout'])->name('logout');
   });

   Route::group(['middleware' => 'guest'], function () {
      // Authentication
      Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
      Route::post('login', [LoginController::class, 'login']);
   });
});

/*------------------------------------------
--------------------------------------------
All Normal Users Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'permission:user.access.index'])->group(function () {
   Route::get('/home', [HomeController::class, 'index'])->name('home');
});
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'permission:admin.access.index'])->group(function () {
   Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
      Route::redirect('/', '/admin/dashboard', 301);

      Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

      Route::group(['prefix' => 'family', 'as' => 'family.', 'middleware' => 'permission:admin.access.family'], function () {
         Route::get('/', [FamilyController::class, 'index'])->name('index');
         Route::get('tree', [FamilyController::class, 'tree'])->name('tree');
         Route::get('selectSearch', [FamilyController::class, 'selectSearch'])->name('selectSearch');
         Route::get('datatable', [FamilyController::class, 'datatable'])->name('datatable');
         Route::get('datatable_view', [FamilyController::class, 'datatable_view'])->name('datatable_view');
         Route::get('create', [FamilyController::class, 'create'])->name('create');
         Route::get('{id}/edit', [FamilyController::class, 'edit'])->name('edit');
         Route::post('store', [FamilyController::class, 'store'])->name('store');
         Route::delete('{id}/destroy', [FamilyController::class, 'destroy'])->name('destroy');
         Route::get('{id}/grandson', [FamilyController::class, 'grandson'])->name('grandson');
         Route::get('{id}/granddaughter', [FamilyController::class, 'granddaughter'])->name('granddaughter');
         Route::get('{id}/aunt', [FamilyController::class, 'aunt'])->name('aunt');
         Route::get('{id}/malecousin', [FamilyController::class, 'malecousin'])->name('malecousin');
         Route::group(['prefix' => '{parent_id}/child', 'as' => 'child.'], function () {
            Route::get('/', [ChildController::class, 'index'])->name('index');
            Route::get('selectSearch', [ChildController::class, 'selectSearch'])->name('selectSearch');
            Route::get('datatable', [ChildController::class, 'datatable'])->name('datatable');
            Route::get('create', [ChildController::class, 'create'])->name('create');
            Route::get('{id}/edit', [ChildController::class, 'edit'])->name('edit');
            Route::post('store', [ChildController::class, 'store'])->name('store');
            Route::delete('{id}/destroy', [ChildController::class, 'destroy'])->name('destroy');
            Route::get('{id}/child', [ChildController::class, 'child'])->name('child');
         });
      });

      Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'permission:admin.access.user'], function () {
         Route::get('/', [UserController::class, 'index'])->name('index');
         Route::get('datatable', [UserController::class, 'datatable'])->name('datatable');
         Route::get('create', [UserController::class, 'create'])->name('create');
         Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
         Route::post('store', [UserController::class, 'store'])->name('store');
         Route::delete('{id}/destroy', [UserController::class, 'destroy'])->name('destroy');
      });

      Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => 'permission:admin.access.profile'], function () {
         Route::get('/', [ProfileController::class, 'index'])->name('index');
         Route::post('store', [ProfileController::class, 'store'])->name('store');
      });
   });
});
