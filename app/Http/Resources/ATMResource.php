<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ATMResource extends JsonResource
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
            'status' => $this->status,
            'coordinate' => [
                'lat' => $this->lat,
                'lng' => $this->lng
            ],
            'bank' => $this->when(!$request->user(), [
                'name' => $this->bank->name,
                'desc' => $this->bank->desc,
                'country' => $this->bank->country,
                'city' => $this->bank->city,
                'town' => $this->bank->town
            ]),
            'created_at' => $this->when($request->user(), $this->created_at->toDateTimeString()),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
