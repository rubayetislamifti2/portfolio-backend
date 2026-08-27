<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalInfoController extends Controller
{
    public function index(){
        try {
            $info = PersonalInfo::find(1);

            return $this->success($info,'Data fetch Successfully',200);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),'Something went wrong',500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required|array',
                'phone.*.primary' => 'required|string',
                'phone.*.secondary' => 'required|string',
                'address' => 'required|array',
                'address.*.present' => 'required|string',
                'address.*.permanent' => 'required|string',
                'academic_qualification' => 'required|array',
                'academic_qualification.*.degree' => 'required|string',
                'academic_qualification.*.school' => 'required|string',
                'academic_qualification.*.year' => 'required|string',
                'experience' => 'required|array',
                'experience.*.company' => 'required|string',
                'experience.*.designation' => 'required|string',
                'experience.*.duration' => 'required|string',
                'skills' => 'required|array',
                'skills.*' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 'Validation Error', 422);
            }

            $data = $validator->validated();

            $info = PersonalInfo::updateOrCreate(['id'=>1],$data);

            return $this->success($info, 'Personal Info Created Successfully', 200);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 'Something went wrong', 500);
        }
    }
}
