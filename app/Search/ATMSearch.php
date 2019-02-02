<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 10:48 AM
 */

namespace App\Search;


use App\Models\ATM;
use Illuminate\Http\Request;

class ATMSearch
{
    public static function apply(Request $filters)
    {
        $atm = (new ATM)->newQuery();

        // Search for a atm based on their id.
        if ($filters->has('id')){
            $atm->where('id', $filters->get('id'));
        }

        // Search for a atm based on their name.
        if ($filters->has('name')) {
            $atm->where('name', $filters->input('name'));
        }
        return $atm->get();
    }
}