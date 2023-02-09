<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FamilyController extends Controller
{

   public function index(Request $request)
   {
      $limit = ($request->limit) ? $request->limit : 5;
      $data = Family::query();
      if (isset($request['id'])) {
         if (isset($request['anak'])) {
            $data = $data->with('child');
         }
         $data = $data->where('id', $request['id']);
         $data = $data->first();
         if (isset($request['cucu'])) {
            $cucu = Family::query();
            $cucu = $cucu->join('families as anak', 'families.id', '=', 'anak.parent_id')
               ->join('families as cucu', 'anak.id', '=', 'cucu.parent_id')->select('cucu.id as id', 'cucu.name as name', 'cucu.sex as sex');
            $cucu = $cucu->where('families.id', $request['id'])->get();
            $data->cucu = $cucu;
         }
         return response([
            'status' => true,
            'message'   => 'Show Data',
            'data'      => $data
         ], Response::HTTP_OK);
      } else {
         if (isset($request['search'])) {
            $data = $data->where('name', 'LIKE', '%' . $request['search'] . '%');
         }
         $data = $data->orderBy('created_at', 'desc');
         $data = $data->paginate($limit);
         return $data;
      }
   }

   public function tree()
   {
      $families = Family::with(['child' => function ($query) {
         $this->getChildren($query, 0);
      }])->whereNull('parent_id')->get();
      return $families;
   }

   function getChildren($query, $depth = 0)
   {
      if ($depth > 10) {
         return;
      }

      $query->with(['child' => function ($query) use ($depth) {
         $this->getChildren($query, $depth + 1);
      }]);
   }

   public function destroy($id)
   {
      $item = Family::where('id', $id)->first();
      if (!$item) {
         return response([
            'status' => false,
            'message'   => trans('messages.data_not_found'),
            'data'      => null
         ], Response::HTTP_BAD_REQUEST);
      }
      $item->delete();

      return response([
         'status' => true,
         'message'   => "successfully_deleted",
         'data'      => $item
      ], Response::HTTP_OK);
   }

   public function store(Request $request)
   {
      $request->validate([
         'name'         => 'required|max:200',
         'sex'        => 'required|in:l,p',
         'parent_id'   => 'exists:families,id',
      ]);
      $data = [
         'name' => $request->name,
         'sex' => $request->sex,
         'parent_id' => $request->parent_id,
      ];
      if (isset($request->id)) {
         $item = Family::findOrFail($request->id);
         $query = $item->update($data);
      } else {
         $query = Family::create($data);
         $item = $query;
      }
      if ($query) {
         return response([
            'status' => true,
            'message'   => "save successfully",
            'data'      => $item
         ], Response::HTTP_OK);
      } else {
         return response([
            'status' => false,
            'message'   => "failed to save",
            'data'      => null
         ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

}
