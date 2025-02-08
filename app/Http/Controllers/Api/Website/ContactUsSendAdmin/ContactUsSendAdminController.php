<?php

namespace App\Http\Controllers\Api\Website\ContactUsSendAdmin;

use Illuminate\Http\Request;
use App\Mail\ContactUsToAdmin;
use Illuminate\Support\Facades\DB;
use App\Enums\ContactUs\SenderType;
use App\Models\ContactUs\ContactUs;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Enum;
use App\Models\ContactUs\ContactUsMessage;
use App\Enums\ContactUs\ContactMessagesStatus;

class ContactUsSendAdminController extends Controller
{
    public function fromWebsiteContactUs(Request $request)
    {
        try {
            DB::beginTransaction();
            $data =$request->validate([
               "name"=>'required|string',
               "phone"=>'required|numeric',
               "email"=>'required|email',
               "subject"=>'required|string',
               "status"=>['required',new Enum(ContactMessagesStatus::class)],
               "message"=>'required|string'
            ]);
            $contactUs=ContactUs::create([
                'name' => $data['name'],
                'subject' => $data['subject'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => ContactMessagesStatus::from($data['status'])->value,
               ]);
               $contactUsMessage =new ContactUsMessage();
               $contactUsMessage->contact_us_Id =$contactUs->id ;
               $contactUsMessage->message = $data['message'];
               $contactUsMessage->save();

               Mail::to(env('MAIL_USERNAME'))->send(new ContactUsToAdmin($contactUsMessage,$contactUs));
              DB::commit();
              return response()->json([
                'message' => __('messages.success.created')
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
