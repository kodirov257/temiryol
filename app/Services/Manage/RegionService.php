<?php

namespace App\Services\Manage;

use App\Helpers\LanguageHelper;
use App\Http\Requests\Admin\Regions\CreateRequest;
use App\Http\Requests\Admin\Regions\UpdateRequest;
use App\Models\Region;

class RegionService
{
    public function create(CreateRequest $request): Region
    {
        return Region::create([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'type' => $request->type,
            'parent_id' => $request->get('parent'),
            'slug' => $request->slug,
        ]);
    }

    public function update(int $id, UpdateRequest $request): void
    {
        $region = Region::findOrFail($id);

        $region->update([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'type' => $request->type,
            'slug' => $request->slug,
        ]);
    }

    public static function getDescendantIds(Region $region, array &$ids): void
    {
        foreach ($region->children as $child) {
            self::getDescendantIds($child, $ids);
        }

        $ids[] = $region->id;
    }


    /**
     * @return Region[]
     */
    public static function getRegionsWithDescendants(Region $region = null, bool $includeItself = true): array
    {
        if ($region) {
            $regions = [$region];
        } else {
            $regions = Region::with(['children'])->whereNull('parent_id')
                ->orderByDesc('name_' . LanguageHelper::getCurrentLanguagePrefix())->get();
        }
        $result = [];
        foreach ($regions as $value) {
            if ($includeItself) {
                $value->depth = 0;
                $result[] = $value;
            }
            self::getDescendants($result, $value, $includeItself ? 1 : 0);
        }

        return $result;
    }

    /**
     * @return Region[]
     */
    public static function getDescendants(array &$result, Region $region, int $depth): array
    {
        foreach ($region->children as $child) {
            $child->depth = $depth;
            $result[] = $child;
            self::getDescendants($result, $child, $depth + 1);
        }
        return $result;
    }

    public static function getRegionList(): array
    {
        /* @var $region Region */
        $regions = self::getRegionsWithDescendants();
        $regionIds = [];
        foreach ($regions as $region) {
            $name = str_repeat('— ', $region->depth);
            $regionIds[$region->id] = $name . $region->name;
        }
        return $regionIds;
    }
}
