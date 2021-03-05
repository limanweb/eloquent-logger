<?php
namespace Limanweb\EloquentLogger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * EloquentLog model
 */
class EloquentLog extends Model
{

    const OP_CREATED = 'created';

    const OP_UPDATED = 'updated';

    const OP_DELETED = 'deleted';

    protected $table = 'eloquent_logs';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ref_type' => 'string',
        'ref_uuid_id' => 'string',
        'ref_int_id' => 'integer',
        'operation' => 'string',
        'details' => 'json',
        'created_at' => 'datetime'
    ];

    /**
     *
     * @param unknown ...$args
     */
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        // Cast for 'user_id' get from configuration.
        // By default is 'integer'.
        $this->casts['user_id'] = config('limanweb.eloquent_logger.user.key_cast', 'integer');
    }

    /**
     * Accessor for 'before' model data
     *
     * @return array|NULL
     */
    public function getModelBeforeAttribute()
    {
        return $this->details['before'] ?? null;
    }

    /**
     * Mutator for 'before' model data
     *
     * @param array|NULL $value
     */
    public function setModelBeforeAttribute($value)
    {
        $details = $this->details;
        $details['before'] = $value;
        $this->details = $details;
    }

    /**
     * Accessor for 'after' model data
     *
     * @return array|NULL
     */
    public function getModelAfterAttribute()
    {
        return $this->details['after'] ?? null;
    }

    /**
     * Mutator for 'after' model data
     *
     * @param array|NULL $value
     */
    public function setModelAfterAttribute($value)
    {
        $details = $this->details;
        $details['after'] = $value;
        $this->details = $details;
    }

    /**
     * Mutator for logged-model ref-fields
     *
     * @param Model $model
     */
    public function setRefAttribute(Model $model)
    {
        $this->ref_type = get_class($model);
        $key = $model->getKey();
        if (is_string($key)) {
            $this->ref_uuid_id = $key;
        } elseif (is_integer($key)) {
            $this->ref_int_id = $key;
        }
    }

    /**
     *
     * @param Builder $query
     * @param Model $model
     * @return mixed|\Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModel(Builder $query, Model $model)
    {
        $key = $model->getKey();

        return $query->where('ref_type', get_class($model))
            ->when(is_string($key), function ($query) {
                $query->where('ref_uuid_id', $key);
            })
            ->when(is_integer($key), function ($query) {
                $query->where('ref_int_id', $key);
            });
    }

    /**
     * User model relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        static $userModel;

        $userModel = $userModel ?? config('limanweb.eloquent_logger.user.model', App\User::class);

        return $this->belongsTo($userModel);
    }

    /**
     * Relation for reference model by UUID-key
     *
     * @return unknown
     */
    public function refUuid()
    {
        return $this->morphTo(null, 'ref_type', 'ref_uuid_id');
    }

    /**
     * Relation for reference model by INT-key
     *
     * @return unknown
     */
    public function refInt()
    {
        return $this->morphTo(null, 'ref_type', 'ref_int_id');
    }

}
