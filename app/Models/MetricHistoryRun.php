<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricHistoryRun extends Model
{
    use HasFactory;
    protected $table = "metric_history_run";
    protected $primaryKey = "id";

    public $timestamps = false;
    protected $fillable = ['url','accesibility_metric','pwa_metric','performance_metric','seo_metric','best_practices_metric','strategy_id','created_at','updated_at'];
}
