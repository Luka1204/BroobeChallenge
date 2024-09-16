<?php
namespace App\Services;
use App\Services\broobeTrait;

class CategoryService 
{
    use broobeTrait;
    
    public function getCategories(){
        return $this->getAllCategories();
    }
}

?>