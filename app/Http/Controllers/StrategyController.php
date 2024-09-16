<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StrategyService;

class StrategyController extends Controller
{
    private $_strategy;

    public function __construct(StrategyService $strategy)
    {
        $this->_strategy = $strategy;
    }
    public function getStrategies()
    {
        return $this->_strategy->getStrategies();
    }
}
