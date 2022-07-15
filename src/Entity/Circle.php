<?php

declare(strict_types=1);

namespace App\Entity;

class Circle implements GeometryObject
{
    private const TYPE = "circle";
    private float $radius;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param float $radius
     * @return self
     */
    public function setRadius(float $radius): self
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * @return float
     */
    public function getRadius(): float
    {
        return $this->radius;
    }

    /**
     * @return float
     */
    public function getSurface(): float
    {
        return round(M_PI * ($this->radius ** 2), 2);
    }

    /**
     * @return float
     */
    public function getCircumference(): float
    {
        return round(2 * M_PI * $this->radius, 2);
    }
}