<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Blog\PostDataTable;
use App\Models\Blog\Post;

class PostController extends Controller
{
    public function index(PostDataTable $dataTable)
    {
        return $dataTable->render('blog.posts.index');
    }
}