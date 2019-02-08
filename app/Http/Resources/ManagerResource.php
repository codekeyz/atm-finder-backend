<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ManagerResource extends Resource
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
            'email' => $this->email,
            'created_at' => $this->when($request->user(), $this->created_at->toDateTimeString()),
            'updated_at' => $this->when($request->user(), $this->updated_at->toDateTimeString())
        ];
    }
}
