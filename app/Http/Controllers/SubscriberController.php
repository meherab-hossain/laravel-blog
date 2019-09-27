<?php

namespace App\Http\Controllers;

use App\Subscriber;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
            'email'=>'required|email|unique:subscribers'
        ]);
        $subscribers=new Subscriber();
        $subscribers->email=$request->email;
        $subscribers->save();
        Toastr::success('you have successfully subscribed to our subscriber list','Success');
        return redirect()->back();
    }
}
