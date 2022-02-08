<?php

namespace Rinordreshaj\Localization\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationValue extends Model
{
    protected $table = 'localization_package_translated_values';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['language_id'];

    public function key()
    {
        return $this->hasMany(TranslationKey::class);
    }

    public function getLanguageIdAttribute()
    {
        return $this->localization_package_languages_id;
    }
}
