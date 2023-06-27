<?php

namespace Biigle\Modules\abysses;

use Biigle\Modules\abysses\DataBase\Factories\AbyssesTrainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AbyssesTrain extends Model
{
    
    protected $table = 'abysses_train';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attrs' => 'array',
        'score' => 'float',
    ];

    public function abyssestrainlabel()
    {
        return $this->hasMany(AbyssesTrainLabel::class, 'train_id');
    }
    
    protected static function newFactory()
    {
        return AbyssesTrainFactory::new();
    }
}
