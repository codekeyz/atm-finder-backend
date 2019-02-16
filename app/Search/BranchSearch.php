<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 10:48 AM
 */

namespace App\Search;


use App\Models\ATM;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchSearch
{
    public static function apply(Request $filters)
    {
        $atm = (new Branch)->newQuery();

        // Return atms for a bank
        if ($filters->user()) {
            $atm->where('bank_id', $filters->user()->id);
        }

        // Search for an branch based on it's id.
        if ($filters->has('id')){
            $atm->where('id', $filters->get('id'));
        }

        // Search for a branch based on it's name.
        if ($filters->has('name')) {
            $atm->where('name', $filters->input('name'));
        }

        return $atm->paginate(20);
    }
}