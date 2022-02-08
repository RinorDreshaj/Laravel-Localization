<?php


namespace Rinordreshaj\Localization\Controllers;


use Illuminate\Http\Request;
use Rinordreshaj\Localization\Models\TranslationKey;

class TranslationsController extends MainController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'language_id' => 'required|integer'
        ]);

        $translation_keys = TranslationKey::select('translation_key', 'id')->with('translation_values')->get();

        return $this->success($translation_keys);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'translation_key' => 'required|string|min:2|unique:localization_package_translated_keys',
            'translations.*.language_id' => 'required',
            'translations.*.text' => 'required',
        ]);

        $translation_key = TranslationKey::create($request->only('translation_key'));

        $translation_key->translations()->sync($this->structure_data($request->translations));

        $translation_key->translation_values;

        return $this->success($translation_key, 201);
    }

    public function update(Request $request, $translation_id)
    {
        $translation_key = TranslationKey::findOrFail($translation_id);

        $translation_key->update($request->only('translation_key'));

        if($request->translations)
        {
            $translation_key->translations()->sync($this->structure_data($request->translations));
        }

        $translation_key->translation_values;


        return $this->success($translation_key);
    }

    public function destroy($translation_id)
    {
        $translation_key = TranslationKey::findOrFail($translation_id);

        $translation = $translation_key->delete();

        return $this->success($translation);
    }

    private function structure_data($translations)
    {
        $trans = [];

        foreach ($translations as $t)
        {
            $trans[] = [
                "text" => $t["text"],
                "localization_package_languages_id" => $t["language_id"]
            ];
        }

        return $trans;
    }
}
