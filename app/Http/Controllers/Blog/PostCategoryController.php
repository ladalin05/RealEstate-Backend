<?php

namespace App\Http\Controllers\Blog;

use App\DataTables\Blog\PostCategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog\Post;

class PostCategoryController extends Controller
{
    public function index(PostCategoryDataTable $dataTable)
    {
        return $dataTable->render('blog.category.index');
    }
}