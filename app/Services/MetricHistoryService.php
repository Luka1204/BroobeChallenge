<?php
namespace App\Services;
use App\Services\broobeTrait;

class MetricHistoryService 
{
    use broobeTrait;
    
    public function getMetricsHistory(){
        return $this->getAllMetricsHistory();
    }

    public function validateUrl($url)
    {
        $pattern = '/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+([\/\w .-]*)*\/?$/';
        if($url == '' || strlen($url) == 0 || !preg_match($pattern,$url)){return false;} 
        return true;
    }

    public function validateCategories($categories)
    {
        if(!is_array($categories) || count($categories) == 0){return false;}
        else
        {
            $response = [];
            $allCategories = $this->getAllCategories();
            foreach($allCategories as $catAux)
            {
                foreach($categories as $cat)
                {
                    $catAux = (object)$catAux;

                    if($cat == $catAux->name)
                    {
                       array_push($response,$cat);
                    }
                }
            }
            return count($response)>0?true:false;
        }
        return true;
    }

    public function validateStrategy($strategy)
    {
        $reg = '/^[a-z A-Z]+$/';
        if($strategy == '' || strlen($strategy) == 0 || !preg_match($reg,$strategy)){return false;}
        if($strategy!= 'MOBILE' && $strategy != 'DESKTOP'){return false;}
        return true; 
    }

    public function validateDataRunMetric($data)
    {
        return isset($data->categories)&& isset($data->strategy)&&isset($data->url);
    }

    
    public function validateDataSaveMetric($data)
    {
        return isset($data->url)&& isset($data->metrics['accessibility'])&&isset($data->metrics['pwa'])&&isset($data->metrics['seo'])&&isset($data->metrics['performance'])&&isset($data->metrics['best_practices']);
    }

    
    public function validateDataUpdateMetric($data)
    {
        return isset($data->categories)&& isset($data->strategy)&&isset($data->url);
    }

    public function validateStrategyId($strategy_id)
    {return !is_int($strategy_id) && $strategy_id > 0 ? false : true;}

    public function validateMetrics($metrics)
    {
        $metrics = (object)$metrics;
        if(!is_numeric($metrics->accessibility['score'])){return false;}
        if(!is_numeric($metrics->performance['score'])){return false;}
        if(!is_numeric($metrics->pwa['score'])){return false;}
        if(!is_numeric($metrics->best_practices['score'])){return false;}
        if(!is_numeric($metrics->seo['score'])){return false;}
        return true;
    }
}

?>