<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\AboutMember;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $about = About::all()->first();
        $members = AboutMember::latest()->get();
        $contact = ContactUs::all()->first();
        $faq = Faq::all()->first();
        $term = Term::all()->first();
        return response()->json(compact('about','members','contact','faq','term'));
    }

    public function getAbout()
    {
        $about = About::all()->first();
        $members = AboutMember::all();
        return response()->json(compact('about','members'));
    }

    public function about(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'message'=>'required',
        ]);

        $exist = About::all()->first();
        if (isset($exist)){
            $about = $exist;
            $about->title = $request->title;
            $about->sub_title = $request->sub_title;
            $about->message = $request->message;
            $about->save();
        }else{
            $about = new About();
            $about->title = $request->title;
            $about->sub_title = $request->sub_title;
            $about->message = $request->message;
            $about->save();
        }

    }

    public function aboutMember(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name'=>'required',
            'designation'=>'required',
            'image'=>'required',
        ]);

        $image = $request->image;
        $slug = Str::slug($request->name);
        $path = 'images/member/';
        $name = $path.$slug.time().'.'.explode('/',explode(':',substr($image, 0, strpos($image,';')))[1])[1];

        if (!File::exists($path)){
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        Image::make($image)->save($name);

        $member = new AboutMember();
        $member->about_id = About::latest()->first('id')->id;
        $member->name = $request->name;
        $member->designation = $request->designation;
        $member->image = $name;
        $member->save();

        $members = AboutMember::latest()->get();
        return response()->json($members);
    }

    public function aboutMemberDelete(AboutMember $aboutMember)
    {
        $this->authorize('admin');
        if (File::exists($aboutMember->image)) {
            unlink($aboutMember->image);
        }
        $aboutMember->delete();

        $members = AboutMember::latest()->get();
        return response()->json($members);
    }

    public function getContactUs()
    {
        $contact = ContactUs::all()->first();
        return response()->json($contact);
    }

    public function contactUs(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'map' => 'required',
            'info' => 'required'
        ]);

        $exist = ContactUs::all()->first();

        $contact = isset($exist) ? $exist : new ContactUs();
        $contact->map = $request->map;
        $contact->info = json_encode($request->info);
        $contact->save();
    }

    public function getFaq()
    {
        $faq = Faq::all()->first();
        return response()->json($faq);
    }

    public function faq(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
           'faq' => 'required'
        ]);

        $exist = Faq::all()->first();

        $faq = isset($exist) ? $exist : new Faq();
        $faq->faq = json_encode($request->faq);
        $faq->save();
    }

    public function getTerms()
    {
        $terms = Term::all()->first();
        return response()->json($terms);
    }

    public function terms(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
           'terms' => 'required'
        ]);

        $exist = Term::all()->first();

        $term = isset($exist) ? $exist : new Term();
        $term->term = json_encode($request->terms);
        $term->save();
    }
}
