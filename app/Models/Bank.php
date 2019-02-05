<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 9:40 AM
 */

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model implements Authenticatable
{

    use AuthenticableTrait;

    protected $fillable = ['name', 'email', 'password', 'desc', 'country', 'city', 'town'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public function atms()
    {
        return $this->hasMany(ATM::class);
    }
}