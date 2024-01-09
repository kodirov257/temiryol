<?php

namespace App\Services\Manage;

use App\Helpers\LanguageHelper;
use App\Http\Requests\Admin\Departments\CreateRequest;
use App\Http\Requests\Admin\Departments\UpdateRequest;
use App\Models\Department;

class DepartmentService
{
    public function create(CreateRequest $request): Department
    {
        if ($request->get('parent')) {
            $parentDepartment = Department::findOrFail($request->get('parent'));
        }
        return Department::create([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'organization_id' => $parentDepartment->organization_id ?? $request->organization_id,
            'parent_id' => $request->get('parent'),
            'slug' => $request->slug,
        ]);
    }

    public function update(int $id, UpdateRequest $request): void
    {
        $organization = Department::findOrFail($id);

        $organization->update([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'organization_id' => $request->organization_id,
            'parent_id' => $request->parent_id,
            'slug' => $request->slug,
        ]);
    }

    public static function getDescendantIds(Department $department, array &$ids): void
    {
        foreach ($department->children as $child) {
            self::getDescendantIds($child, $ids);
        }

        $ids[] = $department->id;
    }


    /**
     * @return Department[]
     */
    public static function getDepartmentsWithDescendants(Department $department = null, bool $includeItself = true): array
    {
        if ($department) {
            $departments = [$department];
        } else {
            $departments = Department::with(['children', 'organization'])->whereNull('parent_id')
                ->orderByDesc('name_' . LanguageHelper::getCurrentLanguagePrefix())->get();
        }
        $result = [];
        foreach ($departments as $value) {
            if ($includeItself) {
                $value->depth = 0;
                $result[] = $value;
            }
            self::getDescendants($result, $value, $includeItself ? 1 : 0);
        }

        return $result;
    }

    /**
     * @return Department[]
     */
    public static function getDescendants(array &$result, Department $department, int $depth): array
    {
        foreach ($department->children as $child) {
            $child->depth = $depth;
            $result[] = $child;
            self::getDescendants($result, $child, $depth + 1);
        }
        return $result;
    }

    public static function getDepartmentList(int $id = null): array
    {
        /* @var $department Department */
        $departments = self::getDepartmentsWithDescendants();
        $departmentIds = [];
        foreach ($departments as $department) {
            if ($department->id !== $id) {
                $name = str_repeat('— ', $department->depth);
                $departmentIds[$department->id] = $name . $department->name;
            }
        }
        return $departmentIds;
    }
}
