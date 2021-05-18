<?php

namespace App\Http\Controllers;

use App\Models\City;

class CityController extends Controller
{
    /**
     * Return districts
     *
     * @param int $id
     * @return array
     */
    public function districts($id)
    {
        return City::find($id)->districts ?? null;
    }
}
