<?php

namespace Biigle\Modules\abysses;

use Biigle\Modules\Abysses\DataBase\Factories\AbyssesTestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AbyssesTest extends Model
{
    
    protected $table = 'abysses_test';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'label' => 'string',
        'is_train' => 'boolean',
    ];

    
    protected static function newFactory()
    {
        return AbyssesTrainLabelFactory::new();
    }
}
