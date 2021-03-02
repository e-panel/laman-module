<?php

namespace Modules\Laman\Entities;

use Modules\Core\Entities\Base as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Laman extends Model
{
    use SoftDeletes, Sluggable;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laman';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'label', 
		'slug', 
		'content', 
		'user_id', 
		'active'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'label'
            ]
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() 
    {
      return $this->belongsTo('Modules\Pengguna\Entities\Pengguna', 'user_id');
    }
}