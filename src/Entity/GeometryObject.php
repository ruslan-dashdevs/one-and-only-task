<?php

declare(strict_types=1);

namespace App\Entity;

interface GeometryObject
{
    public function getType(): string;
    public function getSurface(): float;
    public function getCircumference(): float;
}