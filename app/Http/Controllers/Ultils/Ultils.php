<?php

namespace App\Http\Controllers\Ultils;

class Ultils
{
    public function ConverId($id)
    {
        if ($id < 10) {
            return "0" . $id;
        }
        return $id;
    }
}
