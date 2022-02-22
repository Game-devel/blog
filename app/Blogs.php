<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Blogs extends Model
{
    protected $table = 'blogs';
	
	protected $fillable = [
        'name', 'status', 'text', 'image', 'rubric_id'
    ];

	protected static function validator(array $data)
    {
        return Validator::make($data, [
			'name' => 'required|string|max:200',
            'text' => 'nullable',
            'image' => 'nullable',
            'rubric_id' => 'required|integer|exists:rubrics,id',
        ]);
    }

    public function rubric()
    {
        return $this->belongsTo('App\Rubrics', 'rubric_id');
    }
}
