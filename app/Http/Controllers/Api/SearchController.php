<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LanguageHelper;
use App\Http\Resources\Search\RegionSearchCollection;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function searchRegions(Request $request): JsonResponse
    {
        try {
            if (!empty($value = $request->get('name'))) {
                $regions = Region::where(function ($query) use ($value) {
                    $query->where('name_uz', 'ilike', '%' . $value . '%')
                        ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                        ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                        ->orWhere('name_en', 'ilike', '%' . $value . '%')
                        ->orWhere('slug', 'ilike', '%' . $value . '%');
                })->whereIn('type', array_keys(Region::typeList()))
                    ->orderByDesc('type')
                    ->orderByDesc('name_' . LanguageHelper::getCurrentLanguagePrefix())->paginate(10);
            } else {
                $regions = Region::orderByDesc('type')
                    ->orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())
                    ->whereIn('type', array_keys(Region::typeList()))->paginate(10);
            }

            $totalLength = $regions->total();
            $regionCollection = (new RegionSearchCollection($regions))->toArray($request);

            return $this->sendResponse(['regions' => $regionCollection, 'total' => $totalLength]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }
}
