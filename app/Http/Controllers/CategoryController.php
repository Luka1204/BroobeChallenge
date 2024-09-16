<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private $_category;

    public function __construct(CategoryService $category)
    {
        $this->_category = $category;
    }
    public function getCategories()
    {
        return $this->_category->getCategories();
    }
}
