<?php


namespace Rinordreshaj\Localization\Controllers;


use Illuminate\Http\Request;
use Rinordreshaj\Localization\Models\Language;

class LanguagesController extends MainController
{
    public function index()
    {
        $languages = Language::all();

        return $this->success($languages);
    }

    public function store(Request $request)
    {
        $validated_keys = $this->validate($request, [
            'name' => 'required|string|min:3|unique:localization_package_languages'
        ]);

        $language = Language::create($validated_keys);

        return $this->success($language);
    }

    public function update(Request $request, $language_id)
    {
        $language = Language::findOrFail($language_id);

        $language->update($request->all());

        return $this->success($language);
    }

    public function destroy(Request $request, $language_id)
    {
        $language = Language::findOrFail($language_id);

        $language = $language->delete();

        return $this->success($language);
    }
}
