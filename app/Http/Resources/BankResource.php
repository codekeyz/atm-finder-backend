<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BankResource extends Resource
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
            'name' => $this->name,
            'email' => $this->email,
            'slogan' => $this->desc,
            'city' => $this->city,
            'country' => $this->country,
            'total_atms' => $this->atms->count(),
            'total_managers' => $this->managers->count(),
            'subscriptions' => $request->user()->subscriptions()
        ];
    }
}
