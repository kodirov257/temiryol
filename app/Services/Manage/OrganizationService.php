<?php

namespace App\Services\Manage;

use App\Helpers\LanguageHelper;
use App\Http\Requests\Admin\Organizations\CreateRequest;
use App\Http\Requests\Admin\Organizations\UpdateRequest;
use App\Models\Organization;

class OrganizationService
{
    public function create(CreateRequest $request): Organization
    {
        return Organization::create([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'region_id' => $request->region_id,
            'parent_id' => $request->get('parent'),
            'type' => $request->type,
            'slug' => $request->slug,
        ]);
    }

    public function update(int $id, UpdateRequest $request): void
    {
        $organization = Organization::findOrFail($id);

        $organization->update([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'region_id' => $request->region_id,
            'type' => $request->type,
            'slug' => $request->slug,
        ]);
    }

    public static function getDescendantIds(Organization $organization, array &$ids): void
    {
        foreach ($organization->children as $child) {
            self::getDescendantIds($child, $ids);
        }

        $ids[] = $organization->id;
    }


    /**
     * @return Organization[]
     */
    public static function getOrganizationsWithBranches(Organization $organization = null, bool $includeItself = true): array
    {
        if ($organization) {
            $companies = [$organization];
        } else {
            $companies = Organization::with(['children', 'region'])->whereNull('parent_id')
                ->orderByDesc('name_' . LanguageHelper::getCurrentLanguagePrefix())->get();
        }
        $organizations = [];
        foreach ($companies as $company) {
            if ($includeItself) {
                $company->depth = 0;
                $organizations[] = $company;
            }
            self::getDescendants($organizations, $company, $includeItself ? 1 : 0);
        }

        return $organizations;
    }

    /**
     * @return Organization[]
     */
    public static function getDescendants(array &$organizations, Organization $organization, int $depth): array
    {
        foreach ($organization->children as $child) {
            $child->depth = $depth;
            $organizations[] = $child;
            self::getDescendants($organizations, $child, $depth + 1);
        }
        return $organizations;
    }

    public static function getOrganizationList(): array
    {
        /* @var $organization Organization */
        $organizations = self::getOrganizationsWithBranches();
        $organizationIds = [];
        foreach ($organizations as $organization) {
            $name = str_repeat('— ', $organization->depth);
            $organizationIds[$organization->id] = $name . $organization->name;
        }
        return $organizationIds;
    }
}
