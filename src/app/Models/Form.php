<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected $table = 'forms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'slug', 'json','branch_id',
    ];

    protected $casts = [
        'json'  =>  'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($form) {
            $form->slug = str_slug($form->name);

            $latestSlug = static::whereRaw("slug = '$form->slug' or slug LIKE '$form->slug-%'")
                                ->latest('id')
                                ->value('slug');
            if ($latestSlug) {
                $pieces = explode('-', $latestSlug);

                $number = intval(end($pieces));

                $form->slug .= '-'.($number + 1);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany('AidynMakhataev\LaravelSurveyJs\app\Models\FormResult', 'form_id');
    }

    public function branch()
    {
        return $this->belongsTo('AidynMakhataev\LaravelSurveyJs\app\Models\Branch', 'branch_id');
    }
}
