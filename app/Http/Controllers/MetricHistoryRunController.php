<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RunMetricRequest;
use App\Http\Requests\SaveMetricRequest;
use App\Http\Requests\UpdateMetricRequest;


use App\Services\MetricHistoryService;
use App\Services\CategoryService;


use App\Models\MetricHistoryRun;

use Illuminate\Support\Facades\Http;


class MetricHistoryRunController extends Controller
{
    private $_api_url;
    private $_metricHistoryRun;
    public function __construct(MetricHistoryService $metricHistoryRun,CategoryService $category)
    {
        $this->_api_url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?';
        $this->_metricHistoryRun = $metricHistoryRun;
        $this->_category = $category;

    }
    public function index()
    {
        return view('welcome');
    }

    public function getMetricsHistory()
    {
        return $this->_metricHistoryRun->getMetricsHistory();
    }

    public function buildApiUrlRunMetrics($data)
    {
        $this->_api_url .= 'url='.$data->url.'&key=AIzaSyDCrPAzhzWxZbJxPYIEURODTvBFVVRNHbY&';
        $categoryParams = '';
        for($i=0; $i<count($data->categories);$i++){
            $categoryParams .= 'category=';
            if($i < count($data->categories)-1)
            {
                $categoryParams.=$data->categories[$i].'&';
            }
            else
            {
                $categoryParams.=$data->categories[$i];
            }
        } 
        $this->_api_url.=$categoryParams;
        $this->_api_url.='&strategy='.$data->strategy;

        return $this->_api_url;
    }


    public function runMetrics(RunMetricRequest $request)
    {
        try
        {
            $validated = $request->validated();
            if($validated)
            {
                $data = (object)$request->all();
                if($this->_metricHistoryRun->validateDataRunMetric($data)&&$this->_metricHistoryRun->validateStrategy($data->strategy) && $this->_metricHistoryRun->validateCategories($data->categories) && $this->_metricHistoryRun->validateUrl($data->url))
                {
                    $this->_api_url = $this->buildApiUrlRunMetrics($data);
                    $responseApi = Http::timeout(60)->get($this->_api_url);
                    if($responseApi->status() == 200)
                    {
                        $response = (object)$responseApi->json();
                        return response()->json((object)['status'=>$responseApi->status(),'code'=>1,'response'=>$response]);
                    }
        
                    return response()->json((object)['status'=>$responseApi->status(),'code'=>0,'response'=>[]]);
                }
                else
                {
                    return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
                }
                
            }
            return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
        }
        catch(Exception $e)
        {
            throw new Error($e);
        }
        
        
    }

    
    public function saveMetrics(SaveMetricRequest $request)
    {
        try
        {
            $validated = $request->validated();
            if($validated)
            {
                $data = (object)$request->all();
                if($this->_metricHistoryRun->validateDataSaveMetric($data) && $this->_metricHistoryRun->validateUrl($data->url) && $this->_metricHistoryRun->validateMetrics($data->metrics) && $this->_metricHistoryRun->validateStrategyId($data->strategy_id))
                {
                    $newMHR = new MetricHistoryRun();
                    $newMHR->url = $data->url;
                    $newMHR->accessibility_metric = $data->metrics["accessibility"]["score"];
                    $newMHR->pwa_metric = $data->metrics["pwa"]["score"];
                    $newMHR->performance_metric = $data->metrics["performance"]["score"];
                    $newMHR->seo_metric = $data->metrics["seo"]["score"];
                    $newMHR->best_practices_metric = $data->metrics["best_practices"]["score"];
                    $newMHR->created_at = date('Y-m-d H:i:s');
                    $newMHR->strategy_id = $data->strategy_id;
    
                    return $newMHR->save()?response()->json((object)['status'=>200,'code'=>1,'response'=>$this->getMetricsHistory()]):response()->json((object)['status'=>500,'code'=>0,'response'=>'An error occurred while storing the metric']);
                }
                else
                {
                    return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
                }
                
            }
            return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
        }
        catch(Exception $e)
        {
            throw new Error($e);
        }
        
    }

    public function updateMetrics(UpdateMetricRequest $request)
    {
        try
        {
            $validated = $request->validated();
            if($validated)
            {
                $data = (object)$request->all();
                if($this->_metricHistoryRun->validateDataSaveMetric($data) && $this->_metricHistoryRun->validateUrl($data->url) && $this->_metricHistoryRun->validateMetrics($data->metrics) && $this->_metricHistoryRun->validateStrategyId($data->strategy_id))
                {
                    $newMHR = MetricHistoryRun::findOrFail($data->metric_id);
                    $newMHR->url = $data->url;
                    $newMHR->accessibility_metric = $data->metrics["accessibility"]["score"];
                    $newMHR->pwa_metric = $data->metrics["pwa"]["score"];
                    $newMHR->performance_metric = $data->metrics["performance"]["score"];
                    $newMHR->seo_metric = $data->metrics["seo"]["score"];
                    $newMHR->best_practices_metric = $data->metrics["best_practices"]["score"];
                    $newMHR->updated_at = date('Y-m-d H:i:s');
                    $newMHR->strategy_id = $data->strategy_id;

                    return $newMHR->save()?response()->json((object)['status'=>200,'code'=>1,'response'=>$this->getMetricsHistory()]):response()->json((object)['status'=>500,'code'=>0,'response'=>'An error occurred while storing the metric']);
                }
                else
                {
                    return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
                }
                
            }
            return response()->json((object)['status'=>422,'code'=>-1,'response'=>'There are incorrect fields, please check']);
        }
        catch(Exception $e)
        {
            throw new Error($e);
        }
        
    }
}
