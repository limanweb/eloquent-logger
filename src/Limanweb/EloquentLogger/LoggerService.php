<?php 

namespace Limanweb\EloquentLogger;

class LoggerService 
{
    protected static $config;

    /**
     * 
     * @param string|null $path
     * @return mixed
     */
    public static function config($path = null, $default = null)
    {
        if (is_null(self::$config)) {
            self::$config = config('limanweb.eloquent_logger');
        }

        if ($path) {
            return \Arr::get(self::$config, $path) ?? $default;
        }

        return self::$config ?? $default;
    }

    /**
     * Initialization of logged model observers
     */
    public static function initLogger()
    {
        foreach (array_keys(self::config('models', [])) as $modelName) {
            if (class_exists($modelName)) {
                $modelName::observe(LoggerObserver::class);
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
        $modelConfig = self::config("models.{$modelName}");
        if (is_null($modelConfig)) {
            return; // @todo Log or exception with 'Model configuration error' message
        }

        if ($operation == EloquentLog::OP_DELETED) {
            $changed = $model->getAttributes();
        } else {
            $changed = $model->getDirty();
        }

        $excludeFields = array_merge(
            self::config('exclude_fields', []),
            $modelConfig['exclude_fields'] ?? []
        );

        if (!empty($excludeFields)) {
            $changed = array_diff_key($changed, array_flip($excludeFields));
        }

        if (!empty($changed)) {

            $modelBefore = [];
            $modelAfter = [];
            foreach ($changed as $fieldName => $fieldValue) {
                $modelBefore[$fieldName] = $model->getOriginal($fieldName);
                $modelAfter[$fieldName] = $fieldValue;
            }

            $modelAudit = new EloquentLog();
            $modelAudit->ref = $model;
            $modelAudit->modelBefore = $modelBefore;
            $modelAudit->modelAfter = $modelAfter;
            $modelAudit->operation = $operation;
            $modelAudit->user_id = \Auth::user()->id ?? null;

            $modelAudit->save();
        }
    }
}