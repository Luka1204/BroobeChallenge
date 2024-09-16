<?php
namespace App\Services;
use App\Services\broobeTrait;

class StrategyService 
{
    use broobeTrait;
    
    public function getStrategies(){
        return $this->getAllStrategies();
    }
    
}

?>