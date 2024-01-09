<?php

namespace App\Http\Resources\Search;

use App\Models\Region;
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
 * @property int $parent_id
 * @property string $type
 * @property string $slug
 * @property int $created_by
 * @property int $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Region $parent
 * @property Region[] $children
 * @property Region[] $cities
 * @property Region[] $districts
 * @property Region $center
 * @property Region[] $towns
 * @property User $createdBy
 * @property User $updatedBy
 *
 * @property string $name
 */
class RegionSearchResource extends JsonResource
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
            'name' => $this->getPlace(),
        ];
    }

    private function getPlace(): string
    {
        return $this->name . ($this->parent ? ', ' . $this->parent->getPlace() : '');
    }
}
