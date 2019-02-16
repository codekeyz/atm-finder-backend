<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'city', 'town'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function atms()
    {
        return $this->hasMany(ATM::class);
    }

    public function managers()
    {
        return $this->hasMany(Manager::class);
    }
}
