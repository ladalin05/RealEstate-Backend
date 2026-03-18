<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UserManagement\AgentDataTable;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index(AgentDataTable $dataTable)
    {
        return $dataTable->render('pages.agents.index');
    }
}
