<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 12:08 PM
 */

namespace App\Search;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankSearch
{
    public static function apply(Request $filters)
    {
        $bank = (new Bank)->newQuery();
        $bank->with('atms');

        // Search for a bank based on their id.
        if ($filters->has('id')){
            $bank->where('id', $filters->get('id'));
        }

        // Search for a bank based on their name.
        if ($filters->has('name')) {
            $bank->where('name', $filters->input('name'));
        }
        return $bank->get();
    }
}