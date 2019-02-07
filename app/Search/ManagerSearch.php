<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 10:48 AM
 */

namespace App\Search;


use App\Models\ATM;
use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerSearch
{
    public static function apply(Request $filters)
    {
        $manager = (new Manager)->newQuery();

        // Search for a atm based on their id.
        if ($filters->has('id')){
            $manager->where('id', $filters->get('id'));
            $manager->with('bank');
        }

        // Search for a atm based on their name.
        if ($filters->has('name')) {
            $manager->where('name', $filters->input('name'));
            $manager->with('bank');
        }
        return $manager->get();
    }
}