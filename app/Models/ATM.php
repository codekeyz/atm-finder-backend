<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 9:40 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ATM extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'atms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lat', 'lng', 'bank_id', 'city', 'status', 'branch_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}