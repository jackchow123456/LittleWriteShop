<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Media extends Model
{

    protected $guarded = [];
    protected $connection= 'shop';
    protected $table= 'media';

    protected $casts = [
        'meta' => 'array',
    ];
    protected $appends = [
        'image_uri'
    ];

    public function getImageUriAttribute()
    {
        return url($this->path);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    const IMAGE = 'image';
    const VIDEO = 'video';
    const AUDIO = 'audio';
}
