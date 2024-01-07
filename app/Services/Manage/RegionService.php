<?php

namespace App\Services\Manage;

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
            'type' => !$request->get('parent') ? Region::REGION : $request->type,
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
}
