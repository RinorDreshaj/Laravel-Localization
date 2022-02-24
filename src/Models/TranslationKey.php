<?php

namespace Rinordreshaj\Localization\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationKey extends Model
{
    protected $table = 'localization_package_translated_keys';

    protected $guarded = [];

    public function translations()
    {
        return $this->belongsToMany(
                Language::class,
                'localization_package_translated_values',
                'localization_package_translated_keys_id',
                'localization_package_languages_id'
            )->as('translation_value')
            ->withPivot('text');
    }

    public function translation_values()
    {
        return $this->hasMany(TranslationValue::class,'localization_package_translated_keys_id');
    }
}
