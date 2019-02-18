<?php
/**
 * Created by PhpStorm.
 * User: Hover Software Soln
 * Date: 2/2/2019
 * Time: 10:48 AM
 */

namespace App\Search;


use App\Models\Branch;
use Illuminate\Http\Request;

class BranchSearch
{
    public static function apply(Request $filters)
    {
        $branch = (new Branch)->newQuery();

        // Return branch for a bank
        if ($filters->has('bank_id')) {
            $branch->where('bank_id', $filters->get('bank_id'));
        }

        // Search for a branch based on it's id.
        if ($filters->has('id')){
            $branch->where('id', $filters->get('id'));
        }

        // Search for a branch based on it's name.
        if ($filters->has('name')) {
            $branch->where('name', $filters->input('name'));
        }

        if ($filters->has('paginate') or $filters->has('page')){
            $perPage = (int)$filters->get('paginate');
            return $perPage <= 20 ? $branch->paginate($perPage) : $branch->paginate(20);
        }

        return $branch->get();
    }
}