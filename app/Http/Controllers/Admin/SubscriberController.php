<?php

namespace App\Http\Controllers\Admin;

use App\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    public function index(Request $request){
        $subscribers=Subscriber::latest()->get();
        return view('admin.subscriber',compact('subscribers'));
    }

    public function destroy($subscriber){
        $subscribers=Subscriber::findorfail($subscriber);
        $subscribers->delete();
        Toastr::success('Subscriber successfully deleted','Success');
        return redirect()->back();
        //return view('admin.subscriber',compact('subscribers'));
    }

}
