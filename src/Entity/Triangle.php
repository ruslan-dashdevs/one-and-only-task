<?php

declare(strict_types=1);

namespace App\Entity;

class Triangle implements GeometryObject
{
    private const TYPE = "triangle";
    private float $a;
    private float $b;
    private float $c;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param float $a
     * @return self
     */
    public function setA(float $a): self
    {
        $this->a = $a;
        return $this;
    }

    /**
     * @return float
     */
    public function getA(): float
    {
        return $this->a;
    }

    /**
     * @param float $b
     * @return self
     */
    public function setB(float $b): self
    {
        $this->b = $b;
        return $this;
    }

    /**
     * @return float
     */
    public function getB(): float
    {
        return $this->b;
    }

    /**
     * @param float $c
     * @return self
     */
    public function setC(float $c): self
    {
        $this->c = $c;
        return $this;
    }

    /**
     * @return float
     */
    public function getC(): float
    {
        return $this->c;
    }

    /**
     * @return float
     */
    public function getSurface(): float
    {
        $s = ($this->a + $this->b + $this->c) / 2;
        return round(sqrt($s * ($s - $this->a) * ($s - $this->b) * ($s - $this->c)), 2);
    }

    /**
     * @return float
     */
    public function getCircumference(): float
    {
        return $this->a + $this->b + $this->c;
    }

}