<?php

namespace App\Http\Resources\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentSearchCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*return [
            'departments' => DepartmentSearchResource::collection($this),
        ];*/

        return DepartmentSearchResource::collection($this)->toArray($request);
    }
}
