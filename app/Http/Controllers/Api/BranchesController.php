<?php

namespace App\Http\Controllers\Api;

use App\Models\stores;
use App\Models\branchs;
use App\Models\comments;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\branch;
use App\Http\Resources\onebraches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\branchesCollection;
use App\Http\Resources\branchesCollectionbyuser;
use App\Http\Controllers\Api\Traits\GeneralTrait;
use App\Models\regions;

class BranchesController extends Controller
{
    use GeneralTrait;
// احضار جميع المتاجر (غير مستخدمه )
    public function getbranches()
    {
        return $this->returnData('branches',branch::collection(
        branchs::whereActive(0)->whereAccept(0)->
            whereCityId(auth('api')->user()->city_id)->
            whereRegionId(auth('api')->user()->region_id)->
            latest()->
            orderBy('top', 'DESC')->
            paginate(10))->response()->getData(true),'Done');
    }

// اخر 10 متاجر تم اضافتهم
    public function lastbranch(Request $request)
    {

        return  $this->returnData('branches',branch::collection(
            branchs::whereActive(0)->whereAccept(0)->where(function ($query) use ($request) {
                $reg = regions::whereId($request->region_id)->first() ;
                if( $reg->main != null and $reg->main == true){
                    $query->whereCityId($reg->city_id);
                }else{
                    $query->whereRegionId($request->region_id);
                };
            })->WhereHas('stores', function($q)
            {$q->whereActive(0);})->latest()->take(getSettingsOf('app_new_branch'))->get()));
    }
// احضار الفروع  حسب الاى دى القسم والمنطقه
    public function getbranchesbyid(Request $request)
    {

        $branches = branchs::whereActive(0)->whereAccept(0)->WhereHas('stores', function($q)  use ($request)
        {$q->whereCategoryId($request->category_id)->whereActive(0);})->
            where(function ($query) use ($request) {
                $reg = regions::main($request->region_id) ;
                if( $reg->main != null and $reg->main == true){
                    $query->whereCityId($reg->city_id);
                }else{
                    $query->whereRegionId($request->region_id);
                };
            })->
            orderBy('top', 'DESC')->orderBy('created_at', 'DESC')->paginate(getSettingsOf('app_page_branch'));
            return $this->returnData('branches',new branchesCollection($branches) ,'Done');

    }

// احضار الفروع الخاصه بالمستخدم
    public function getbranchesbyuser(Request $request)
    {
        $branches = branchs::WhereHas('stores', function($q)  use ($request)
        {$q->whereUserId(auth('api')->user()->id); })->
            latest()->
            orderBy('top', 'DESC')->get();
            return $this->returnData('branches',new branchesCollectionbyuser($branches) ,'Done');
    }


// احضار المخزن واحد حسب الاى دى
    public function  getbranchbyid(Request $request)
    {
        DB::table('branchs')->whereId($request->id)->increment('view');
        $branch =   branchs::whereId($request->id)->whereActive(0)->orderBy('top', 'DESC')->get();
        return $this->returnData('branches',onebraches::collection( $branch ),'Done');
    }

// بحث عن طريق المدينه المحافظه المنتج المتجر
    public function  search(Request $request)
    {
        if($request->search != ''){
                   $input= $request->search;

                $ss=  branchs::whereActive(0)->whereAccept(0)->where(function ($query) use ($request) {
                        $reg = regions::main($request->region_id) ;
                        if( $reg->main != null and $reg->main == true){
                            $query->whereCityId($reg->city_id);
                         }else{
                            $query->whereRegionId($request->region_id);
                        };})
                        ->WhereHas('stores', function($q) use ($request) {
                                   $q->where('name', 'LIKE', '%' . $request->search . '%')->whereActive(0);
                                })->orWhere(function ($query) use ($request)
                                {
                                    $query->whereActive(0)->whereAccept(0)->where(function ($query) use ($request) {
                                        $reg = regions::main($request->region_id) ;
                                        if( $reg->main != null and $reg->main == true){
                                            $query->whereCityId($reg->city_id);
                                         }else{
                                            $query->whereRegionId($request->region_id);
                                        };});

                                        $query->whereHas('product', function ($query) use ($request)
                                        {
                                                $query->where('name', 'LIKE', '%'. $request->search . '%');
                                        });

                                })->paginate((int)getSettingsOf('app_pagforsearch_branch'));

        }else{
             $ss = branchs::whereRegionId($request->region_id)->whereActive(55)->paginate(setting('app_pagforsearch_branch'));
        }
        return $this->returnData('branches', new branchesCollection($ss),'Done');
    }

//تعديل متجر
    public function branchedit(Request $request)
    {
        try {

            $branch = branchs::findOrFail($request->branch_id);

                $validatorvbranch = Validator::make($request->all(), [
                    'name'        => 'string',
                    'region_id'   => 'required|string|exists:regions,id',
                    'city_id'     => 'required|string|exists:cities,id',
                    'address'     => 'string',
                    'lat'         => 'string',
                    'lng'         => 'string',
                    'opentime'    => 'string',
                    'closetime'   => 'string',
                    'description' => 'string',
                    'phone'       => 'string',
                    'phone2'      => 'string',
               ]);

            if($validatorvbranch->fails()){
                return $this->returnError('400',$validatorvbranch->errors());
            }

            if( $request->image != null){
                $image = $this->uploadimages('branch', $request->image);
            }else{
                $image = $branch->getAttributes()['image'];
            }
                $branch->update(array_merge(
                        $validatorvbranch->validated(),
                        [
                         'image' => $image
                        ]));
                        $branch->stores->update(['category_id' => $request->category_id]);

                        return $this->returnSuccessMessage('تم تعديل المتجر بنجاح ','E000');

        } catch (\Exception $e) {
            return  $this->returnError('E002','المتجر غير موجود');
        }
    }

//حذف متجر
    public function branchdelete(Request $request)
    {
        try {
            $branch = branchs::findOrFail($request->branch_id);
            if($branch->stores->branch->count() == 1){
            $branch->stores->delete();
            $branch->delete();
            }else{
            $branch->delete();
            }
            return $this->returnSuccessMessage('تم حذف المتجر بنجاح ');
        } catch (\Exception $e) {
            return  $this->returnError('E002','المتجر غير موجود');
        }
    }

    public function branchcheck(){
        $store =  stores::whereUserId(auth('api')->user()->id)->first();
        if( $store ==null){
          return response()->json(['status' => true,'number' => '0']);
         }
        $num_branch =  $store->branch->count();

        if($store->branch_num > $num_branch)
        {
            return response()->json([
                'status' => true,
                'number' => $store->branch_num  . ' / ' . $num_branch
            ]);
        }
        else
        {
            return response()->json(['status' => false,
                'number' =>  $store->branch_num  . ' / ' . $num_branch ,
                'msg' => __('notify.limit_branch')
            ]);
        }
    }

    public function addcomment(Request $request)
    {

       comments::create([
           'user_id' => auth('api')->user()->id,
           'branch_id'=>  $request->branch_id,
           'comment'=>  $request->comment,
           'rating'=> $request->rating ,
       ]);
       return $this->returnSuccessMessage('تم اضافه التقيم ');
    }

}
