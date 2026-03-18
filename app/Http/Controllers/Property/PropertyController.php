<?php

namespace App\Http\Controllers\Property;

use App\Models\Property\PropertyType;
use App\Models\Reports;
use App\Models\Property\Property;
use App\Models\Location\Location;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\Property\PropertyGallery;
use App\DataTables\Property\PropertyDataTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\User;
use App\Http\Controllers\Controller;
use App\Models\Location\PropertyLocation;
use App\Models\Property\Amenity;
use App\Models\Property\PropertyAmenity;
use App\Models\Property\PropertyFeature;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    public function index(PropertyDataTable $dataTable)
    {
        return $dataTable->render('property.properties.index');
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

        return view('property.properties.details', [
            'property_info'  => $property_info,
            'gallery_images' => $gallery_images,
            'related_list'   => $related_list,
            'latest_list'    => $latest_list,
            'user_id'        => $property_info->user_id,
        ]);
    }

    public function create(Request $request)
    {
        try {
            $page_title=__('global.add_property');
            if($request->isMethod('post')){
                // Validation rules
                $request->validate([
                    'type' => 'required',
                    'title' => 'required',
                ]);

                $main_image = null;
                if ($request->hasFile('main_image')) {
                    $main_image = uploadImage($request->file('main_image'), null, 'images/property/feature');
                }
                
                $floor_plan_image = null;
                if($request->hasFile('floor_plan_image')){
                    $floor_plan_image = uploadImage($request->file('floor_plan_image'), null, 'images/property/floor_plan');
                }

                $property_images = [];
                if ($request->hasFile('gallery_image')) {
                    foreach ($request->file('gallery_image') as $image) {
                        $path = uploadImage($image, null, 'images/property/property_image');
                        $property_images[] = $path;
                    }
                }


                $property_inf = Property::create([
                                'user_id' => Auth::user()->id,
                                'type_id' => $request->type,
                                'title' => addslashes($request->title),
                                'slug' => Str::slug($request->title, '-'),
                                'description' => addslashes($request->description),
                                'phone' => $request->phone,
                                'purpose' => $request->purpose ?? 'sale', // default to 'sale' if null
                                'bedrooms' => $request->bedrooms ?? 0,
                                'bathrooms' => $request->bathrooms ?? 0,
                                'area' => $request->area,
                                'furnishing' => $request->furnishing ?? 'unfurnished',
                                'price' => $request->price,
                                'verified' => $request->verified ?? 0,
                                'featured' => $request->featured ?? 0, // new field in schema
                                'status' => $request->status ?? 1,
                                'main_image' => $main_image ?? 'upload/placeholder_img.jpg',
                                'floor_plan_image' => $floor_plan_image ?? null,
                            ]);
                    if(!empty($property_images)) {
                        foreach($property_images as $property_img){
                            PropertyGallery::create([
                                'property_id' => $property_inf->id,
                                'image' => $property_img
                            ]);
                        }
                    }

                    if($request->country_id != null){
                        $property_location = PropertyLocation::create([
                                                                    'property_id' => $property_inf->id,
                                                                    'country_id' => $request->country_id,
                                                                    'city_id' => $request->city_id,
                                                                    'district_id' => $request->district_id,
                                                                    'commune_id' => $request->commune_id,
                                                                    'address' => $request->address,
                                                                    'latitude' => $request->latitude,
                                                                    'longitude' => $request->longitude,
                                                                ]);
                    }

                    if($request->amenities){
                        foreach($request->amenities as $amenity){
                            PropertyAmenity::create([
                                'property_id' => $property_inf->id,
                                'amenity_id' => $amenity
                            ]);
                        }
                    }
                    
                    if($request->features){
                        foreach($request->features as $feature){
                            PropertyFeature::create([
                                'property_id' => $property_inf->id,
                                'feature_id' => $feature
                            ]);
                        }
                    }
                
                    return response()->json([
                        'status'  => 'success',
                        'message' => __('global.create_property_successfully'),
                        'redirect' => route('property.properties.index'),
                    ]);

            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

        return view('property.properties.create',compact('page_title'));
        
    }

    public function update(Request $request)
    {
        try {
            $page_title = __('global.update_property');
            $property = Property::findOrFail($request->id);

            if ($request->isMethod('post')) {
                // Validation rules
                $request->validate([
                    'id' => 'required|exists:properties,id',
                    'type' => 'required',
                    'title' => 'required',
                ]);

                // Handle main image
                if ($request->hasFile('main_image')) {
                    $main_image = uploadImage($request->file('main_image'), null, 'images/property/feature');
                } else {
                    $main_image = $property->main_image; // keep old image
                }

                // Handle floor plan image
                if ($request->hasFile('floor_plan_image')) {
                    $floor_plan_image = uploadImage($request->file('floor_plan_image'), null, 'images/property/floor_plan');
                } else {
                    $floor_plan_image = $property->floor_plan_image; // keep old image
                }

                // Update property data
                $property->update([
                    'type_id' => $request->type,
                    'title' => addslashes($request->title),
                    'slug' => Str::slug($request->title, '-'),
                    'description' => addslashes($request->description),
                    'phone' => $request->phone,
                    'purpose' => $request->purpose ?? 'sale',
                    'bedrooms' => $request->bedrooms ?? 0,
                    'bathrooms' => $request->bathrooms ?? 0,
                    'area' => $request->area,
                    'furnishing' => $request->furnishing ?? 'unfurnished',
                    'price' => $request->price,
                    'verified' => $request->verified ?? 0,
                    'featured' => $request->featured ?? 0,
                    'status' => $request->status ?? 1,
                    'main_image' => $main_image,
                    'floor_plan_image' => $floor_plan_image,
                ]);

                // Handle gallery images
                if ($request->hasFile('gallery_image')) {
                    // Optionally delete old gallery images
                    PropertyGallery::where('property_id', $property->id)->delete();

                    foreach ($request->file('gallery_image') as $image) {
                        $path = uploadImage($image, null, 'images/property/property_image');
                        PropertyGallery::create([
                            'property_id' => $property->id,
                            'image' => $path
                        ]);
                    }
                }

                // Handle property location
                if ($request->country_id != null) {
                    PropertyLocation::updateOrCreate(
                        ['property_id' => $property->id],
                        [
                            'country_id' => $request->country_id,
                            'city_id' => $request->city_id,
                            'district_id' => $request->district_id,
                            'commune_id' => $request->commune_id,
                            'address' => $request->address,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                        ]
                    );
                }

                // Handle amenities
                if ($request->amenities) {
                    PropertyAmenity::where('property_id', $property->id)->delete();
                    foreach ($request->amenities as $amenity) {
                        PropertyAmenity::create([
                            'property_id' => $property->id,
                            'amenity_id' => $amenity
                        ]);
                    }
                }

                // Handle features
                if ($request->features) {
                    PropertyFeature::where('property_id', $property->id)->delete();
                    foreach ($request->features as $feature) {
                        PropertyFeature::create([
                            'property_id' => $property->id,
                            'feature_id' => $feature
                        ]);
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => __('global.update_property_successfully'),
                    'redirect' => route('property.properties.index'),
                ]);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

        return view('property.properties.edit', compact('page_title', 'property'));
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

        $property_view = view('property.properties.view', compact('property_list'))->render();

        return response()->json([
            'property_view' => $property_view
        ]);
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('property.properties.index')->with('flash_message', __('global.deleted_property_successfully'));
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

        return view('property.properties.owner_list', compact('property_list', 'owner_id', 'latest_list'));
    }
}
