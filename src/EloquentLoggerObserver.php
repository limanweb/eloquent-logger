<?php 

namespace Limanweb\EloquentLogger;

use Limanweb\EloquentLogger\EloquentLoggerService;

class EloquentLoggerObserver 
{
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function created(\Illuminate\Database\Eloquent\Model $model)
    {
        EloquentLoggerService::registerAudit($model, 'created');
    }
    
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function updated(\Illuminate\Database\Eloquent\Model $model)
    {
        EloquentLoggerService::registerAudit($model, 'updated');
    }
    
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function deleted(\Illuminate\Database\Eloquent\Model $model)
    {
        EloquentLoggerService::registerAudit($model, 'deleted');
    }
    
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function restored(\Illuminate\Database\Eloquent\Model $model)
    {
        EloquentLoggerService::registerAudit($model, 'restored');
    }

    /**
     * Initialization observer for logged model 
     */
    public static function initLogger()
    {
        $loggedModels = array_keys(config('limanweb::eloquent_logger.config.models', []));
        
        foreach ($loggedModels as $modelName) {
            if (class_exists($modelName)) {
                $modelName::observe(self::class);
            } else {
                // @todo ConfigurationException
            }
        }
    }
}