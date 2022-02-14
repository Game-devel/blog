<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Rubric extends Model
{
    protected $table = 'rubrics';
	public $timestamps = false;
	protected $fillable = [
        'name'
    ];

	protected static function validator(array $data)
    {
        return Validator::make($data, [
			'name' => 'required|string|max:255',
        ]);
    }
}
