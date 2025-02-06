<?php

namespace App\Http\Controllers\Api\Website\SendCareerToAdmin;

use Illuminate\Http\Request;
use App\Mail\sendCareerToAdmin;
use App\Models\Career\Candidate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\Upload\UploadService;


class sendCareerToAdminController extends Controller
{
    protected $uploadService;
    public function __construct( UploadService $uploadService)
    {
       $this->uploadService= $uploadService;
    }
    public function SendCareerToAdmin(Request $request)
    {

    try {
        DB::beginTransaction();
        $data=$request->all();
        $path = null;
        if(isset($data['cv']) && $data['cv'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($data['cv'], 'careers');
        }
        $candidate = Candidate::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'cv' => $path,
            'cover_letter' => $data['coverLetter']??null,
            'career_id' => $data['careerId'],
        ]);
        Mail::to(env('MAIL_USERNAME'))->send(new sendCareerToAdmin($candidate));
        DB::commit();
    } catch (\Throwable $th) {
        DB::rollBack();
        throw $th;
    }
        //send email to admin
        //return respone
    }
}
