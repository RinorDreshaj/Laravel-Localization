<?php

namespace Rinordreshaj\Localization\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rinordreshaj\Localization\Models\Language;

class LanguagesController extends MainController
{
    public function index(): JsonResponse
    {
        return $this->success(
            Language::all()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated_keys = $this->validate($request, [
            'name' => 'required|string|min:3|unique:localization_package_languages',
            'abbreviation' => 'required|string|min:2|unique:localization_package_languages',
        ]);

        return $this->success(
            Language::create($validated_keys)
        );
    }

    public function update(Request $request, $language_id): JsonResponse
    {
        $language = Language::findOrFail($language_id);

        $validated_keys = $this->validate($request, [
            'name' => 'required|string|min:3|unique:localization_package_languages,name,' . $language->id,
            'abbreviation' => 'required|string|min:2|unique:localization_package_languages,abbreviation,' . $language->id,
        ]);

        $language->update($validated_keys);

        return $this->success($language);
    }

    public function destroy(Request $request, $language_id): JsonResponse
    {
        Language::findOrFail($language_id)->delete();

        return $this->success([]);
    }
}
