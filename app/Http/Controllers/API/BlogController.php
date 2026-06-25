<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BlogService;

class BlogController extends Controller
{

    private BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function getAllBlogs(Request $request)
    {
        $blogs = $this->blogService->getBlogsData();

        return $this->successResponse('Blogs fetched successfully', $blogs);
    }
}