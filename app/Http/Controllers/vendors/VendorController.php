<?php

namespace App\Http\Controllers\vendors;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Validator;

class VendorController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $msg = 'All vendors are right here';
            $data = Vendor::get();
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'vendor_name'=>'required|regex:/[a-zA-Z\s]+/',
            ]
        );
                if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
      try {
            $vendor = Vendor::firstOrCreate([
                'vendor_name' => $request->vendor_name
            ]);
            
           $msg='vendor is created successfully';
            return $this->successResponse($vendor,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->delete();
            $msg = 'The Vendor is deleted successfully';
            return $this->successResponse($vendor, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}
