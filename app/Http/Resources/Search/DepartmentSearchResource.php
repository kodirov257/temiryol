<?php

namespace App\Http\Resources\Search;

use App\Models\Department;
use App\Models\Organization;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name_uz
 * @property string $name_uz_cy
 * @property string $name_ru
 * @property string $name_en
 * @property int $organization_id
 * @property int $parent_id
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Organization $organization
 * @property Department $parent
 * @property Department[] $children
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 * @property string $fullName
 * @property string $hierarchy
 */
class DepartmentSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->hierarchy,
        ];
    }
}
