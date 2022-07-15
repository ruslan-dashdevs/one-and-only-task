<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GeometryObject;

class GeometryCalculatorService
{
    public function getSumOfSurface(GeometryObject $a, GeometryObject $b): float
    {
        return $a->getSurface() + $b->getSurface();
    }

    public function getSumOfCircumference(GeometryObject $a, GeometryObject $b): float
    {
        return $a->getCircumference() + $b->getCircumference();
    }
}