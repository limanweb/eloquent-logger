<?php

namespace Limanweb\EloquentLogger\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentLogger extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'ref_type' => 'string',
        'ref_uuid_id' => 'string',
        'ref_int_id' => 'integer',
        'operation' => 'string',
        'details' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setRefAttribute(\Illuminate\Database\Eloquent\Model $model)
    {
        $this->ref_type = get_class($model);
        $key = $model->getKey();
        if (is_string($key)) {
            $this->ref_uuid_id = $key;
        } elseif (is_integer($key)) {
            $this->ref_int_id = $key;
        }
    }
}
