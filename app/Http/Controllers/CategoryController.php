<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function list()
    {
        $categories = Category::get();
        makeTree(0, $categories);
    }
}
