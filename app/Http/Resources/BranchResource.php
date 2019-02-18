<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BranchResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'town' => $this->town,
            'total_managers' => $this->managers->count(),
            'total_atms' => $this->atms->count(),
            'created_at' => $this->when($request->user(), $this->created_at->toDateTimeString()),
            'updated_at' => $this->when($request->user(), $this->updated_at->toDateTimeString())
        ];
    }
}
