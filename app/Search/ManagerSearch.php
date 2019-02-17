<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 10:48 AM
 */

namespace App\Search;


use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerSearch
{
    public static function apply(Request $filters)
    {
        $manager = (new Manager)->newQuery();

        // Search for a manager based on bank id
        if ($filters->has('bank_id')){
            $manager->where('bank_id', $filters->get('bank_id'));
        }

        // Search for a atm based on their id.
        if ($filters->has('id')){
            $manager->where('id', $filters->get('id'));
        }

        // Search for a atm based on their name.
        if ($filters->has('name')) {
            $manager->where('name', $filters->input('name'));
        }

        // Search for a manager based on their bank id.
        if ($filters->has('bank_id')) {
            $manager->where('bank_id', $filters->input('bank_id'));

        }
        return $manager->paginate(20);
    }
}