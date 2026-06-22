<?php

namespace App\Http\Controllers\API;

use App\Models\Property\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Services\BaseService;

class CategoryController extends Controller
{
    private BaseService $service;
    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return PropertyType::query(); }
        };
    }
    
    public function getPropertyCategories(Request $request)
    {
        $params['filter_by'] = ['status' => 1];
        $categories = $this->service->getAll($params);

        return $this->successResponse('Category list', $categories);
    }
}
