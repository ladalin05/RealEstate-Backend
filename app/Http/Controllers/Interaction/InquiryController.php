<?php

namespace App\Http\Controllers\Interaction;

use App\Models\UserManagement\User;
use App\Models\Admin\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\Interaction\InquiryDataTable;
use Illuminate\Support\Facades\Storage;
use App\Services\BaseService;

class InquiryController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Agent::query(); }
        };
    }

    public function index(InquiryDataTable $dataTable)
    {
        return $dataTable->render('interaction.inquiries.index');
    }
}
