<?php


namespace Rinordreshaj\Localization\Models;


use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'localization_package_languages';

    protected $guarded = [];

    public function translations()
    {
        return $this->belongsToMany(TranslationKey::class, 'localization_package_translated_values', 'localization_package_languages_id', 'localization_package_translated_keys_id' )->withPivot(
            'text'
        );
    }
}
