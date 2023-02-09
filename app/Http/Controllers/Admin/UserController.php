<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('admin.user.index');
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      $roles = Role::all();
      return view('admin.user.create')->with([
         'roles' => $roles
      ]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $validation = [
         'role' => ['required', 'exists:roles,name'],
         'name' => ['required', 'string', 'max:255'],
         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
         'password' => ['required', 'string', 'min:8', 'confirmed']
      ];
      if ($request->id) {
         $validation['email'] = ['required', 'string', 'email', 'max:255'];
         $validation['password'] = ['confirmed'];
      }
      $request->validate($validation);

      $data = [
         'name' => $request->name,
         'email' => $request->email,
      ];
      if($request->password){
         $data['password'] = Hash::make($request->password);
      }
      if (isset($request->id)) {
         $item = User::findOrFail($request->id);
         $query = $item->update($data);
         $item->syncRoles([$request->role]);
      } else {
         $query = User::create($data);
         $item = $query;
         $item->assignRole($request->role);
      }
      if ($query) {
         return redirect()->route('admin.user.index')->withFlashSuccess(__('Data Saved Successfully'));
      } else {
         return redirect()->route('admin.user.index')->withFlashDanger(__('Data Failed To Save'));
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $roles = Role::all();
      $data = User::with('roles')->findOrFail($id);
      return view('admin.user.edit')->with([
         'data' => $data,
         'roles' => $roles,
      ]);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      $item = User::findOrFail($id);
      $item->delete();
      return redirect()->route('admin.user.index')->withFlashSuccess(__('Data Deleted Successfully'));
   }

   public function datatable(Request $request)
   {
      $data = User::query()->with('roles');
      return DataTables::of($data)
         ->make(true);
   }
}
