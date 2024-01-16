<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LanguageHelper;
use App\Http\Resources\Search\DepartmentSearchCollection;
use App\Http\Resources\Search\RegionSearchCollection;
use App\Http\Resources\Search\UserSearchCollection;
use App\Models\Department;
use App\Models\Region;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
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

    public function searchUsers(Request $request): JsonResponse
    {
        try {
            if (!empty($value = $request->get('name'))) {
                $regions = User::select(['users.*', 'p.*'])
                    ->leftJoin('profiles as p', 'users.id', '=', 'p.user_id')
                    ->where('status', User::STATUS_ACTIVE)
                    ->where(function (Builder $query) use ($value) {
                        $query->where('name', 'ilike', '%' . $value . '%')
                            ->orWhere('email', 'ilike', '%' . $value . '%')
                            ->orWhere('first_name', 'ilike', '%' . $value . '%')
                            ->orWhere('last_name', 'ilike', '%' . $value . '%')
                            ->orWhere('middle_name', 'ilike', '%' . $value . '%')
                            ->orWhereRaw("concat(first_name, ' ', last_name, ' ', coalesce(middle_name, '')) ilike '%{$value}%'");
                    })
                    ->paginate(10);
            } else {
                $regions = User::select(['users.*', 'p.*'])
                    ->leftJoin('profiles as p', 'users.id', '=', 'p.user_id')
                    ->orderBy('p.last_name')
                    ->orderBy('p.first_name')
                    ->orderBy('name')->paginate(10);
            }

            $totalLength = $regions->total();
            $regionCollection = (new UserSearchCollection($regions))->toArray($request);

            return $this->sendResponse(['users' => $regionCollection, 'total' => $totalLength]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }
    public function searchDepartments(Request $request): JsonResponse
    {
        try {
            if (!empty($value = $request->get('name'))) {
                $departments = Department::orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())
                    ->where(function (Builder $query) use ($value) {
                        $query->where('name_uz', 'ilike', '%' . $value . '%')
                            ->orWhere('name_uz_cy', 'ilike', '%' . $value . '%')
                            ->orWhere('name_ru', 'ilike', '%' . $value . '%')
                            ->orWhere('name_en', 'ilike', '%' . $value . '%')
                            ->orWhere('slug', 'ilike', '%' . $value . '%');
                    })
                    ->paginate(10);
            } else {
                $departments = Department::orderBy('name_' . LanguageHelper::getCurrentLanguagePrefix())->paginate(10);
            }

            $totalLength = $departments->total();
            $departmentCollection = (new DepartmentSearchCollection($departments))->toArray($request);

            return $this->sendResponse(['departments' => $departmentCollection, 'total' => $totalLength]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }
}
