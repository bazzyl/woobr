<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index($year): Response
    {
        if ($year && $year % 4 === 0) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}