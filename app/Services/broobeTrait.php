<?php
namespace App\Services;

use App\Models\Strategy;
use App\Models\Category;
use App\Models\MetricHistoryRun;



trait broobeTrait
{
    public function getAllStrategies()
    {
        return Strategy::all();
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function getAllMetricsHistory()
    {
        $metrics = MetricHistoryRun::all();
        foreach($metrics as $metric)
        {
            $strategy = Strategy::findOrFail($metric->strategy_id);
            $metric->strategy = $strategy->name;
        }
        return $metrics;
    }
}
?>

