<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\User;
use App\Models\Favourite;
use App\Models\PostRatings;
use App\Models\Reports;
use App\Models\SubscriptionPlan;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str; 

class UsersController extends Controller
{ 
    public function list()    
    { 
        $page_title = __('global.users');
        $user_list = User::join('roles','users.role_id','=','roles.id')
                        ->join('user_inform','users.id','=','user_inform.user_id')
                        ->select('users.*','roles.name as role_name', 'roles.slug as role_slug', 'user_inform.contact_address as address', 'user_inform.contact_phone as phone_number', 'user_inform.contact_location as location', 'user_inform.image as user_image')
                        ->where("roles.slug", "!=","administrator")
                        ->orderBy('id','DESC')
                        ->get();

        $users_list = view('pages.users.list', compact('user_list'))->render();
        
        return view('pages.users.index',compact('page_title','users_list'));
    } 

    public function create(Request $request)
    {
        $page_title = __('global.add_user');
        if($request->isMethod('post'))
        {
             $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'subscription_plan' => 'nullable|integer|exists:subscription_plan,id',
                'status' => 'required|boolean',
                'user_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // ✅ Handle image upload
            $userImage = null;

            if ($request->hasFile('user_image')) {
                $icon = $request->file('user_image');
                $tmpFilePath = public_path('/upload/');
                if (!file_exists($tmpFilePath)) {
                    mkdir($tmpFilePath, 0755, true);
                }
                $hardPath = Str::slug($request->name, '-') . '-' . md5(time());
                $img = Image::make($icon);
                $img->fit(250, 250)->save($tmpFilePath . $hardPath . '-b.jpg');
                $userImage = $hardPath . '-b.jpg';
            }

            $planInfo = SubscriptionPlan::where('id', $request->subscription_plan)
                ->where('status', '1')->first();

            User::create([
                'username'    => strtolower($request->name),
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => bcrypt($request->password),
                'phone'       => $request->phone,
                'plan_id'     => $planInfo->id,
                'status'      => $request->status,
                'image'  => $userImage,
            ]);

            return redirect()->route('users.index')->with('flash_message', __('global.create_users_successfully'));
        }
        return view('pages.users.create');
    }

    public function update(Request $request, $id)
    {
        $page_title = __('global.add_user');
        $users_list = User::findOrFail($id);
        if($request->isMethod('post'))
        {
            try {$validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6',
                'phone' => 'nullable|string|max:20',
                'subscription_plan' => 'nullable|integer|exists:subscription_plan,id',
                'status' => 'required|boolean',
                'user_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

                // ✅ Handle image upload
                $userImage = $users_list->user_image; // keep old image by default

                if ($request->hasFile('user_image')) {
                    $icon = $request->file('user_image');
                    $tmpFilePath = public_path('/upload/');
                    if (!file_exists($tmpFilePath)) {
                        mkdir($tmpFilePath, 0755, true);
                    }

                    // remove old image if exists
                    if ($users_list->user_image && file_exists($tmpFilePath . $users_list->user_image)) {
                        unlink($tmpFilePath . $users_list->user_image);
                    }

                    $hardPath = Str::slug($request->name, '-') . '-' . md5(time());
                    $img = Image::make($icon);
                    $img->fit(250, 250)->save($tmpFilePath . $hardPath . '-b.jpg');
                    $userImage = $hardPath . '-b.jpg';
                }

                // ✅ Get plan info
                $planInfo = SubscriptionPlan::where('id', $request->subscription_plan)
                    ->where('status', '1')->first();
                $planId = $planInfo->id;


                // ✅ Determine expiration date
                if ($planInfo) {
                    $planDays = $planInfo->plan_days ?? null;
                    $expDate = strtotime(date('m/d/Y', strtotime("+{$planDays} days")));
                } elseif (!empty($request->exp_date)) {
                    $expDate = strtotime($request->exp_date);
                } else {
                    $expDate = $users_list->exp_date;
                    $planId = $users_list->plan_id;
                }

                // ✅ Update user data
                $users_list->name       = $request->name;
                $users_list->email      = $request->email;
                $users_list->password   = $request->filled('password') ? bcrypt($request->password) : $users_list->password;
                $users_list->phone      = $request->phone;
                $users_list->plan_id    = $planId;
                $users_list->exp_date   = $expDate;
                $users_list->image = $userImage;

                $users_list->save();

                return redirect()->route('users.index')->with('flash_message', __('global.update_users_successfully'));
            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                            dd($e->getMessage());
                Session::flash('error_flash_message', $e->getMessage());
            }
        }
        return view('pages.users.edit', compact('users_list'));
    }
     
    
    public function delete($id)
    {
        
        $fav_obj = Favourite::where('user_id',$id)->delete();
        $rep_obj = Reports::where('user_id',$id)->delete();

        $user = User::findOrFail($id);         
		$user->delete();
		
        Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }    

    public function filter_user(Request $request)
    {
        $user_list = User::when($request->search_text, function ($query, $search_text) {
                $query->where('users.name', 'LIKE', "%{$search_text}%")
                      ->orWhere('users.email', 'LIKE', "%{$search_text}%")
                      ->orWhere('users.username', 'LIKE', "%{$search_text}%");
            })
            ->where("usertype", "==","User")
            ->orderBy('users.id', 'DESC')
            ->get();


        $users_list = view('pages.users.list', compact('user_list'))->render();

        return response()->json([
            'users_list' => $users_list
        ]);
    }

    
    //Sub Admin
    public function admin_list()    
    { 
          
        $page_title = trans('global.admin_list');
        $user_list = User::join('roles','users.role_id','=','roles.id')
                        ->select('users.*','roles.name as role_name', 'roles.slug as role_slug')
                        ->where("roles.slug", "=","administrator")
                        ->orderBy('id','DESC')
                        ->get();
        $admin_view = view('pages.admin.list', compact('user_list'));
         
        return view('pages.admin.index',compact('page_title','admin_view'));
    } 

    public function admin_create(Request $request)
    { 
        $page_title=trans('global.add_admin');
        if($request->isMethod('post'))
        {
            try{

            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                            dd($e->getMessage());
                Session::flash('error_flash_message', $e->getMessage());
            }
        }
           
        return view('pages.admin.create',compact('page_title'));
    }
    
    public function admin_update(Request $request, $id)
    { 
        $page_title=trans('global.add_admin');
        $admin = User::findOrFail($id);
        if($request->isMethod('post'))
        {
            try{

            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                            dd($e->getMessage());
                Session::flash('error_flash_message', $e->getMessage());
            }
        }
           
        return view('pages.admin.edit',compact('page_title', 'admin'));
    }

    public function admin_delete($id)
    {
        
        
        if($id!=1)
        {
            $fav_obj = Favourite::where('user_id',$id)->delete();
            $rep_obj = Reports::where('user_id',$id)->delete();

            $user = User::findOrFail($id);         
            $user->delete();
        } 
          
        Session::flash('flash_message', trans('words.deleted'));
        return redirect()->back();

    }     

    public function filter_sub_admin(Request $request)
    {
        $user_list = User::when($request->search_text, function ($query, $search_text) {
                $query->where('users.name', 'LIKE', "%{$search_text}%")
                      ->orWhere('users.email', 'LIKE', "%{$search_text}%")
                      ->orWhere('users.username', 'LIKE', "%{$search_text}%");
            })
            ->where("usertype", "!=","User")
            ->orderBy('users.id', 'DESC')
            ->get();


        $users_list = view('pages.users.list', compact('user_list'))->render();

        return response()->json([
            'users_list' => $users_list
        ]);
    }
    	
}
