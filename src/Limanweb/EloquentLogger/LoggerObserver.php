<?php 

namespace Limanweb\EloquentLogger;

use Illuminate\Database\Eloquent\Model;
use Limanweb\EloquentLogger\LoggerService;

class LoggerObserver 
{
    /**
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        LoggerService::registerAudit($model, 'created');
    }

    /**
     *
     * @param Model $model
     */
    public function updated(Model $model)
    {
        LoggerService::registerAudit($model, 'updated');
    }

    /**
     *
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        LoggerService::registerAudit($model, 'deleted');
    }
}