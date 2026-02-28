<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use Illuminate\Http\Request;
use App\Models\Admin\Settings;
use App\Models\Admin\ContentPage;
use App\Models\Admin\ContentPageSection;
use App\Models\UserInform;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
 
    public function general_settings()
    { 
        $page_title=trans('global.settings');
        $id = Auth::user()->id;
        $settings = Settings::where('user_id',$id)->first();
        $user_info = UserInform::where('user_id',$id)->first();
        if($settings){
            return view('pages.settings.update',compact('page_title', 'user_info', 'settings' ));
        }

        return view('pages.settings.create',compact('page_title'));
    }

    public function general_setting_create(Request $request)
    {
        if($request->isMethod('post')){
            try {
                // Validation rules
                $request->validate([
                    'web_name' => 'required',
                    'web_logo' => 'required',
                    'favicon' => 'required',
                ]);

                $web_logo = null;
                if ($request->hasFile('web_logo')) {
                        $web_logo = uploadImageV2($request->file('web_logo'), null, 'assets/images/default');
                    }
                
                $favicon = null;
                if ($request->hasFile('favicon')) {
                        $favicon = uploadImageV2($request->file('favicon'), null, 'assets/images/default');
                    }


                $settings = Settings::create([
                    'user_id' => Auth::user()->id,
                    'web_name' => $request->web_name,
                    'web_logo' => $web_logo,
                    'favicon' => $favicon,
                    'web_email' => $request->web_email,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'description' => $request->description,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'created_at' => now(),
                ]);

                $imagePath = null;
                if ($request->hasFile('image')) {
                        $imagePath = uploadImageV2($request->file('image'), null, 'assets/images/users');
                    }
                UserInform::create([
                                    'user_id' => Auth::user()->id,
                                    'plan_id' => $request->plan_id,
                                    'image' => $imagePath,
                                    'contact_address' => $request->contact_address,
                                    'contact_phone' => $request->contact_phone,
                                    'contact_email' => $request->contact_email,
                                    'contact_location' => $request->location,
                                    'facebook' => $request->facebook,
                                    'twitter' => $request->twitter,
                                    'instagram' => $request->instagram,
                                    'youtube' => $request->youtube,
                                    'created_by' => Auth::user()->id,
                                ]);
                
                return redirect()->route('settings.general.index')->with('flash_message', __('global.create_successfully'));

            } catch(\Throwable $e) {
                dd($e->getMessage());
            }
        }
    }

    public function general_setting_update(Request $request)
    {
        if($request->isMethod('post')){
            try {
                // Validation rules
                $request->validate([
                    'web_name' => 'required',
                ]);
                $user_id = Auth::user()->id;

                $setting = Settings::findOrFail($request->id);
                $user_info = UserInform::find($user_id);

                if ($request->hasFile('web_logo')) {
                    $setting->web_logo = uploadImageV2( $request->file('web_logo'), $setting->web_logo, 'assets/images/default');
                }

                if ($request->hasFile('favicon')) {
                    $setting->favicon = uploadImageV2( $request->file('favicon'), $setting->favicon, 'assets/images/default' );
                }


                $setting->user_id           = Auth::user()->id;
                $setting->web_name          = $request->web_name;
                $setting->web_email         = $request->web_email;
                $setting->facebook          = $request->facebook;
                $setting->instagram         = $request->instagram;
                $setting->description       = $request->description;
                $setting->meta_title        = $request->meta_title;
                $setting->meta_description  = $request->meta_description;
                $setting->save();

                if($user_info) {
                    if ($request->hasFile('image')) {
                        $user_info->image = uploadImageV2( $request->file('image'), $user_info->image, 'assets/images/users' );
                    }
                    $user_info->user_id          = Auth::user()->id;
                    $user_info->contact_address  = $request->contact_address;
                    $user_info->contact_phone    = $request->contact_phone;
                    $user_info->contact_email    = $request->contact_email;
                    $user_info->contact_location = $request->location;
                    $user_info->facebook         = $request->facebook;
                    $user_info->twitter          = $request->twitter;
                    $user_info->instagram        = $request->instagram;
                    $user_info->youtube          = $request->youtube;
                    $user_info->updated_by       = Auth::user()->id;
                    $user_info->save();
                } else {
                    $imagePath = null;
                    if ($request->hasFile('image')) {
                            $imagePath = uploadImageV2($request->file('image'), null, 'assets/images/users');
                        }
                    UserInform::create([
                                        'user_id' => Auth::user()->id,
                                        'plan_id' => $request->plan_id,
                                        'image' => $imagePath,
                                        'contact_address' => $request->contact_address,
                                        'contact_phone' => $request->contact_phone,
                                        'contact_email' => $request->contact_email,
                                        'contact_location' => $request->location,
                                        'facebook' => $request->facebook,
                                        'twitter' => $request->twitter,
                                        'instagram' => $request->instagram,
                                        'youtube' => $request->youtube,
                                        'created_by' => Auth::user()->id,
                                    ]);
                }
                
                return redirect()->route('settings.general.index')->with('flash_message', __('global.create_successfully'));

            } catch(\Throwable $e) {
                dd($e->getMessage());
            }
        }
    }

    public function content_page(Request $request)
    {
        $page_title = trans('global.content_page');
        $content_page = ContentPage::where('status',1)->get();

        return view('pages.content_page.index', compact('page_title', 'content_page'));
    }

    public function content_page_create(Request $request)
    {
        $page_title = trans('global.add_content_page');
        if($request->isMethod('post')){
            try {
                // Validation rules
                $request->validate([
                    'title' => 'required',
                ]);
                $slug = \Illuminate\Support\Str::slug($request->title);
                $content_page = ContentPage::create([
                    'title' => $request->title,
                    'slug' => $slug,
                    'content' => $request->content,
                    'status' => $request->status ? 1 : 0,
                    'created_at' => now(),
                ]);

                if($content_page){
                    foreach($request->sections as $section) {
                        $section_image = null; 
                        if($request->hasFile('section_image')) {
                            $section_image = uploadImageV2($request->file('section_image'), null, 'assets/images/content_page');
                        }
                        ContentPageSection::create([
                            'page_id' => $content_page->id,
                            'title' => $section['title'],
                            'image' => $section_image,
                            'content' => $section['content'],
                            'status' => $section['status'] ? 1 : 0,
                            'created_at' => now(),
                        ]);
                    }

                }

                return redirect()->route('settings.content_page.index')->with('flash_message', __('global.create_successfully'));

            } catch(\Throwable $e) {
                dd($e->getMessage());
            }
        }

        return view('pages.content_page.create', compact('page_title'));
    }

    public function content_page_update(Request $request)
    {
        $page_title = trans('global.edit_content_page');
        $content_page = ContentPage::findOrFail($request->id);
        $section = ContentPageSection::where('page_id', $content_page->id)->first();
        if($request->isMethod('post')){
            try {
                // Validation rules
                $request->validate([
                    'title' => 'required',
                    'content' => 'required',
                ]);

                $content_page->title = $request->title;
                $content_page->content = $request->content;
                $content_page->status = $request->status ? 1 : 0;
                $content_page->save();

                if($content_page){
                    $section_image = null; 
                    if($request->hasFile('section_image')) {
                        $section_image = uploadImageV2($request->file('section_image'), $section ? $section->image : null, 'assets/images/content_page');
                    }
                    if($section) {
                        $section->title = $request->section_title;
                        $section->content = $request->section_content;
                        $section->status = $request->section_status ? 1 : 0;
                        if($section_image) {
                            $section->image = $section_image;
                        }
                        $section->save();
                    } else {
                        ContentPageSection::create([
                            'page_id' => $content_page->id,
                            'title' => $request->section_title,
                            'status' => $request->section_status ? 1 : 0,
                            'image' => $section_image,
                            'content' => $request->section_content,
                            'created_at' => now(),
                        ]);
                    }

                }

                return redirect()->route('settings.content_page.index')->with('flash_message', __('global.create_successfully'));

            } catch(\Throwable $e) {
                dd($e->getMessage());
            }
        }

        return view('pages.content_page.edit', compact('page_title', 'content_page', 'section'));
    }
}
