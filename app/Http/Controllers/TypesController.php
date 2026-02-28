<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Property;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

 
class TypesController extends Controller
{   
 	  
    public function types()
    {    
        $type_list= Type::where('status',1)->orderby('type_name')->paginate(12);

        return view('pages.types.list',compact('type_list'));              
         
    }

    public function types_property($type_slug, $type_id)
    {
        $type_info = Type::findOrFail($type_id);
        $sort_by = request('sort_by', 'New');

        $sortOptions = [
            'Old' => ['id', 'ASC'],
            'High' => ['price', 'DESC'],
            'Low'  => ['price', 'ASC'],
            'New'  => ['id', 'DESC'],
        ];

        [$column, $direction] = $sortOptions[$sort_by] ?? $sortOptions['New'];

        $property_list = Property::with(['types', 'locations', 'users'])
            ->where(['status' => 1, 'type_id' => $type_id])
            ->orderBy($column, $direction)
            ->paginate(10)
            ->appends(['sort_by' => $sort_by]);

        return view('pages.types.property_list', compact('property_list', 'type_info'));
    }

}
