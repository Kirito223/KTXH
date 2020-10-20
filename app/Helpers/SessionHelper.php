<?php

namespace App\Helpers;

use Session;

class SessionHelper
{

    public function getDepartmentId()
    {
        return Session::get('madonvi');
    }
}
