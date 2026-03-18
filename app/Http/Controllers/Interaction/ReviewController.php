<?php

namespace App\Http\Controllers\Interaction;

use App\Models\Property\PropertyType;
use App\Models\Reports;
use App\Models\Property\Property;
use App\Models\Location\Location;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\Property\PropertyGallery;
use App\DataTables\Interaction\ReviewDataTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(ReviewDataTable $dataTable)
    {
        return $dataTable->render('interaction.reviews.index');
    }
}
