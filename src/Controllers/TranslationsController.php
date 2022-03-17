<?php

namespace Rinordreshaj\Localization\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rinordreshaj\Localization\Models\Language;
use Rinordreshaj\Localization\Models\TranslationKey;
use Rinordreshaj\Localization\Models\TranslationValue;

class TranslationsController extends MainController
{
    public function all(Request $request)
    {
        $translationKeys = TranslationKey::query()->with('translations');

        if ($request->search) {
            $translationKeys->whereLikeRelation([
                'translation_key',
                'translation_values.text',
            ], $request->search ?? null);
        }

        return $this->success(
            $translationKeys->paginate(20)
        );
    }

    public function index(Request $request): JsonResponse
    {
        $this->validate($request, [
            'language_id' => 'required|integer|exists:localization_package_languages,id',
        ]);

        $translationKeys = TranslationKey::query()
            ->select('id', 'translation_key')
            ->with('translation_values', function ($query) use ($request) {
                $query->where('localization_package_languages_id', $request->language_id);
            });

        if ($request->search) {
            $translationKeys->whereLikeRelation([
                'translation_key',
                'translation_values.text',
            ], $request->search ?? null);
        }

        return $this->success($translationKeys->get());
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'translation_key' => 'required|string|min:2|unique:localization_package_translated_keys',
            'translations.*.language_id' => 'required|exists:localization_package_languages,id',
            'translations.*.text' => 'required|max:500',
        ]);

        $translation_key = TranslationKey::create($request->only('translation_key'));
        $translation_key->translations()->sync($this->structure_data($request->translations));
        $translation_key->translation_values;

        return $this->success($translation_key, 201);
    }

    public function update(Request $request, $translation_id): JsonResponse
    {
        $this->validate($request, [
            'translation_key' => 'required|string|min:2|unique:localization_package_translated_keys',
            'translations.*.language_id' => 'required|exists:localization_package_languages,id',
            'translations.*.text' => 'required|max:500',
        ]);

        $translation_key = TranslationKey::findOrFail($translation_id);
        $translation_key->updateOrCreate($request->only('translation_key'));

        if ($request->translations) {
            $translation_key->translations()->detach();
            $translation_key->translations()->sync($this->structure_data($request->translations));
        }

        $translation_key->translation_values;

        return $this->success($translation_key);
    }

    public function destroy($translation_id): JsonResponse
    {
        TranslationKey::findOrFail($translation_id)->delete();
        return $this->success([]);
    }

    public function storeKeys(Request $request)
    {
        $request->validate([
            'keys' => 'required|array',
        ]);
        $keys = collect($request->keys);
        $keys->each(function ($key) {
            if (TranslationKey::where('translation_key', $key)->doesntExist()) {
                $translation_key = TranslationKey::create([
                    'translation_key' => $key,
                ]);
                $languages = Language::get();
                foreach ($languages as $language) {
                    TranslationValue::updateOrCreate([
                        'localization_package_translated_keys_id' => $translation_key->id,
                        'localization_package_languages_id' => $language->id,
                    ]);
                }
            }
        });
        return $this->success([]);
    }

    private function structure_data($translations): array
    {
        $trans = [];

        foreach ($translations as $t) {
            $trans[] = [
                "text" => $t["text"] ?? null,
                "localization_package_languages_id" => $t["language_id"],
            ];
        }

        return array_unique($trans, SORT_REGULAR);
    }
}

