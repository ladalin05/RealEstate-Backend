<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UserManagement\AgencyDataTable;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    public function index(AgencyDataTable $dataTable)
    {
        return $dataTable->render('user-management.agency.index');
    }
}
