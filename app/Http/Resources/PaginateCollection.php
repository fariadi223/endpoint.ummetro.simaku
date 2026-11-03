<?php 
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'code'              => 200,
            'messages'          => "Success !",
            'errors'            => null,
            'data'              => $this->collection,
            'recordsTotal'      => $this->total(),
            'recordsFiltered'   => $this->total(),
            'count'             => $this->count(),
            'perPage'           => $this->perPage(),
            'currentPage'       => $this->currentPage(),
            'lastPage'          => $this->lastPage()
        ];
    }
}