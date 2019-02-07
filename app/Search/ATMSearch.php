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

        // Search for an atm based on it's id.
        if ($filters->has('id')){
            $atm->where('id', $filters->get('id'));
        }

        // Search for an atm based on it's name.
        if ($filters->has('name')) {
            $atm->where('name', $filters->input('name'));
        }

        // Search for an atm based on it's status
        if ($filters->has('status')) {
            $atm->where('status', $filters->input('status'));
        }
        return $atm->paginate(20);
    }
}