<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 9:40 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function atms() {
        return $this->hasMany(ATM::class);
    }
}