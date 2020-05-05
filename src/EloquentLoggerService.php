<?php 

namespace Limanweb\EloquentLogger;

class EloquentLoggerService 
{
    protected static $config;
    
    public static function config($path = null)
    {
        if (is_null(self::$config)) {
            self::$config = config("business.audit.model_audit");
        }
        
        if ($path) {
            return \Arr::get(self::$config, $path);
        }
        
        return self::$config;
    }
    
    /**
     * Инициализация обсерверов на аудируемых моделях
     */
    public static function initObservers()
    {
        foreach (array_keys(self::config()) as $modelName) {
            if (class_exists($modelName)) {
                $modelName::observe(ModelAuditObserver::class);
            }
        }
    }
    
    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $operation
     */
    public static function registerAudit(\Illuminate\Database\Eloquent\Model $model, $operation)
    {
        $modelName = get_class($model);
        $modelConfig = self::config($modelName);
        if (is_null($modelConfig)) {
            return; // @todo Log configuration error
        }
        
        if ($operation == 'deleted') {
            $changed = $model->getAttributes();
        } else {
            $changed = $model->getDirty();
        }
        
        // Не логируем изменения полей меток времени
        $changed = array_diff_key($changed, array_flip(self::$excludeFields));
        if (isset($modelConfig['exclude_fields'])) {
            // Не логируем изменения полей исключенных в конфигурации аудита модели
            $changed = array_diff_key($changed, array_flip($modelConfig['exclude_fields']));
        }
        
        if (!empty($changed)) {
            
            $modelBefore = [];
            $modelAfter = [];
            foreach ($changed as $fieldName => $fieldValue) {
                $modelBefore[$fieldName] = $model->getOriginal($fieldName);
                $modelAfter[$fieldName] = $fieldValue;
            }
            
            $modelAudit = new \App\Audit\Models\ModelAudit();
            $modelAudit->ref = $model;
            $modelAudit->modelBefore = $modelBefore;
            $modelAudit->modelAfter = $modelAfter;
            $modelAudit->operation = $operation;
            $modelAudit->user_id = \Auth::user()->id ?? null;
            
            $modelAudit->save();
        }
    }
}