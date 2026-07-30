<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationCollection extends ResourceCollection
{
    public $collects;

    public function __construct($resource, $collects=null) {
        $this->collects = $collects;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'meta' => [
                'total' => $this->total(),
                'limit' => $this->perPage(),
                'page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
            'data' => $this->collection->transform(function ($resource) {
                return $resource;
            }),
        ];
    }
}