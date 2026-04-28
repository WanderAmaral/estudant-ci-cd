<?php

namespace App;

class Calculadora
{
    public function somar(int $a, int $b): int
    {
        return $a + $b;
    }

    public function subtrair(int $a, int $b): int
    {
        return $a - $b;
    }

    public function multiplicar(int $a, int $b): int
    {
        return $a * $b;
    }

    public function equacao(int $a, int $b): int
    {
        return ($a + $b) * ($a - $b);
    }

    public function dividir(int $a, int $b): float
    {
        if ($b === 0) {
            throw new \InvalidArgumentException('Divisao por zero nao permitida');
        }

        return $a / $b;
    }
}
