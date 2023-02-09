<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('admin.profile.index')->with([
         'data' => auth()->user()
      ]);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      //
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
         'name' => ['required', 'string', 'max:255'],
      ];
      $validation['email'] = ['required', 'string', 'email', 'max:255'];
      $validation['password'] = ['confirmed'];
      $request->validate($validation);

      $data = [
         'name' => $request->name,
         'email' => $request->email,
      ];
      if($request->password){
         $data['password'] = Hash::make($request->password);
      }
      $item = User::findOrFail(auth()->user()->id);
      $query = $item->update($data);
      if ($query) {
         return redirect()->route('admin.profile.index')->withFlashSuccess(__('Data Saved Successfully'));
      } else {
         return redirect()->route('admin.profile.index')->withFlashDanger(__('Data Failed To Save'));
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
      //
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
      //
   }
}
