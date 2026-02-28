<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Reports;
use App\Models\Property;
use App\Models\Location;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\PropertyGallery;
use App\DataTables\PropertyDataTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    public function index(PropertyDataTable $dataTable)
    {
        return $dataTable->render('pages.properties.index');
    }

    /**
     * Display property details.
     */
    public function property_details($slug, $id)
    {
        $property_info = Property::where('status', 1)->find($id);

        if (!$property_info) {
            Session::flash('error_flash_message', trans('words.access_denied'));
            return redirect()->back();
        }

        $gallery_images = PropertyGallery::where('post_id', $property_info->id)
            ->orderBy('id')
            ->get();

        $latest_list = Property::where('status', 1)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        $related_list = Property::where('status', 1)
            ->where('type_id', $property_info->type_id)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        post_views_save($id, 'Property');

        return view('pages.property.details', [
            'property_info'  => $property_info,
            'gallery_images' => $gallery_images,
            'related_list'   => $related_list,
            'latest_list'    => $latest_list,
            'user_id'        => $property_info->user_id,
        ]);
    }

    public function create(Request $request)
    {
        $page_title=__('global.add_property');
        if($request->isMethod('post')){
            try {
                // Validation rules
                $request->validate([
                    'type' => 'required',
                    'title' => 'required',
                    'location' => 'required',
                ]);

                $featured_image = null;
                if ($request->hasFile('image')) {
                    $featured_image = uploadImage($request->file('image'), null, 'images/property/feature');
                }
                
                $floor_plan_image = null;
                if($request->floor_plan_image != null){
                        $featured_image = uploadImage($request->file('floor_plan_image'), null, 'images/property/floor_plan');
                    }

                $property_inf = Property::create([
                                'type_id' => $request->type,
                                'user_id' => Auth::user()->id,
                                'title' => addslashes($request->title),
                                'slug' => Str::slug($request->title, '-'),
                                'description' => addslashes($request->description),
                                'phone' => $request->phone,
                                'location_id' => $request->location,
                                'address' => $request->address,
                                'latitude' => $request->latitude,
                                'longitude' => $request->longitude,
                                'purpose' => $request->purpose,
                                'bedrooms' => $request->bedrooms,
                                'bathrooms' => $request->bathrooms,
                                'area' => $request->area,
                                'furnishing' => $request->furnishing,
                                'amenities' => addslashes($request->amenities),
                                'price' => $request->price,
                                'verified' => $request->verified,
                                'image' => $featured_image != null ? $featured_image : 'upload/placeholder_img.jpg',
                                'floor_plan_image' => $floor_plan_image,
                                'status' => $request->status,
                            ]);
                
                    return response()->json([
                        'status'  => 'success',
                        'message' => __('global.create_property_successfully'),
                        'redirect' => route('property.index'),
                    ]);

            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return view('pages.properties.create',compact('page_title'));
        
    }

    public function edit(Request $request, $id)
    {
        $page_title = __('global.edit_property');
        $property_info = Property::findOrFail($id);
        $gallery_images = PropertyGallery::where('post_id',$property_info->id)->orderBy('id')->get();
        $type_list = Type::orderBy('type_name')->get();  
        $location_list = Location::orderBy('name')->get();
        if ($request->isMethod('post')) {
            try {
                // Validation rules
                $request->validate([
                    'type' => 'required',
                    'title' => 'required',
                    'location' => 'required',
                ]);
                $featured_image = updateImage($request->file('feature_image'), $property_info->image, 'images/property/feature');
                $floor_plan_image = updateImage($request->file('floor_plan_image'), $property_info->floor_plan_image, 'images/property/floor_plan');

                $property_info->type_id = $request->type;
                $property_info->user_id = Auth::user()->id;
                $property_info->title = addslashes($request->title);
                $property_info->slug = Str::slug($request->title, '-');
                $property_info->description = addslashes($request->description);
                $property_info->phone = $request->phone;
                $property_info->location_id = $request->location;
                $property_info->address = $request->address;
                $property_info->latitude = $request->latitude;
                $property_info->longitude = $request->longitude;
                $property_info->purpose = $request->purpose;
                $property_info->bedrooms = $request->bedrooms;
                $property_info->bathrooms = $request->bathrooms;
                $property_info->area = $request->area;
                $property_info->furnishing = $request->furnishing;
                $property_info->amenities = addslashes($request->amenities);
                $property_info->price = $request->price;
                $property_info->verified = $request->verified;
                $property_info->image = $featured_image != null ? $featured_image : 'upload/placeholder_img.jpg';
                $property_info->floor_plan_image = $floor_plan_image;
                $property_info->status = $request->status;
                $property_info->save();

                if ($request->hasFile('gallery_image')) {
                    PropertyGallery::where('post_id', $property_info->id)->delete();
                    foreach ($request->file('gallery_image') as $file) {
                        $gallary_images = updateImage($request->file('feature_image'), $property_info->image, 'images/property/gallery');
                        PropertyGallery::create([
                            'post_id' => $property_info->id,
                            'image'   => $gallary_images
                        ]);
                        
                    }
                }

                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.updated_property_successfully'),
                    'redirect' => route('property.index'),
                ]);

            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return view('pages.properties.edit', compact('page_title', 'property_info', 'gallery_images', 'type_list', 'location_list'));
    }

    /**
     * Search properties based on filters.
     */
    public function property_filter(Request $request)
    {
        $property_list = Property::join('type', 'property.type_id', '=', 'type.id')
            ->join('locations', 'property.location_id', '=', 'locations.id')
            ->join('users', 'property.user_id', '=', 'users.id')
            ->when($request->type_id, function ($query, $type_id) {
                $query->where('property.type_id', $type_id);
            })
            ->when($request->location_id, function ($query, $location_id) {
                $query->where('property.location_id', $location_id);
            })
            ->when($request->search_text, function ($query, $search_text) {
                $query->where('property.title', 'LIKE', "%{$search_text}%")
                      ->orWhere('property.address', 'LIKE', "%{$search_text}%");
            })
            ->where('property.status', 1)
            ->orderBy('property.id', 'DESC')
            ->select(
                'property.*',
                'type.type_name',
                'locations.name as location_name',
                'users.name as user_name'
            )
            ->get();

        $total_property = $property_list->count();

        $property_view = view('pages.properties.view', compact('property_list'))->render();

        return response()->json([
            'property_view' => $property_view
        ]);
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('property.index')->with('flash_message', __('global.deleted_property_successfully'));
    }

    /**
     * Handle contact form submission for property owners.
     */
    public function properties_contact(Request $request)
    {
        $data = $request->except('_token');

        $rules = [
            'name'  => 'required',
            'email' => 'required|email|max:100',
        ];

        if (getcong('recaptcha_on_contact_us')) {
            $rules['g-recaptcha-response'] = 'required';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (getcong('recaptcha_on_contact_us')) {
            $recaptcha_response = $request->input('g-recaptcha-response');

            $resp = json_decode(
                file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" .
                getcong('recaptcha_secret_key') . "&response=" . $recaptcha_response . "&remoteip=" . $_SERVER['REMOTE_ADDR'])
            );

            if (empty($resp->success)) {
                Session::flash('error_flash_message', 'Captcha timeout or duplicate');
                return redirect()->back();
            }
        }

        $user_info = User::find($request->property_owner_id);

        if (!$user_info) {
            Session::flash('error_flash_message', 'Property owner not found.');
            return redirect()->back();
        }

        $emailData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'user_message' => $request->message,
        ];

        try {
            Mail::send('emails.property_contact', $emailData, function ($message) use ($user_info, $request) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'))
                    ->to($user_info->email, $user_info->name)
                    ->subject($request->property_title . ' - Contact');
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            Session::flash('error_flash_message', $e->getMessage());
            return redirect()->back();
        }

        Session::flash('flash_message', trans('words.contact_msg'));
        return redirect()->back();
    }

    /**
     * Report a property.
     */
    public function properties_report(Request $request)
    {
        $request->validate(['report_text' => 'required']);

        Reports::create([
            'post_type' => 'Property',
            'post_id'   => $request->property_id,
            'user_id'   => Auth::id(),
            'message'   => $request->report_text,
            'date'      => now()->timestamp,
        ]);

        Session::flash('flash_message', trans('words.reports_success'));
        return redirect()->back();
    }

    /**
     * Show all properties from a specific owner.
     */
    public function properties_owner($owner_id)
    {
        $user_info = User::findOrFail($owner_id);

        if ($user_info->usertype === "User" &&
            ($user_info->plan_id == 0 || strtotime(date('m/d/Y')) >= $user_info->exp_date)) {
            Session::flash('error_flash_message', trans('words.access_denied'));
            return redirect()->back();
        }

        $property_list = $user_info->userproperty()
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $latest_list = Property::with(['types', 'locations', 'users'])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        return view('pages.property.owner_list', compact('property_list', 'owner_id', 'latest_list'));
    }
}
