<?php

namespace App\Http\Controllers\Interaction;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\Interaction\InquiryDataTable;
use Illuminate\Support\Facades\Storage;

class InquiryController extends Controller
{
    public function index(InquiryDataTable $dataTable)
    {
        return $dataTable->render('pages.inquiries.index');
    }
}
