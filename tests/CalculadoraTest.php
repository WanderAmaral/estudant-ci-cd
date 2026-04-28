<?php

namespace Tests;

use App\Calculadora;
use PHPUnit\Framework\TestCase;

class CalculadoraTest extends TestCase
{
    private Calculadora $calc;

    protected function setUp(): void
    {
        $this->calc = new Calculadora();
    }

    public function test_soma_dois_numeros(): void
    {
        $resultado = $this->calc->somar(2, 3);
        $this->assertSame(99, $resultado);
    }

    public function test_subtrai_dois_numeros(): void
    {
        $resultado = $this->calc->subtrair(10, 4);
        $this->assertSame(6, $resultado);
    }

    public function test_multiplica_dois_numeros(): void
    {
        $resultado = $this->calc->multiplicar(3, 4);
        $this->assertSame(12, $resultado);
    }

    public function test_divide_dois_numeros(): void
    {
        $resultado = $this->calc->dividir(10, 2);
        $this->assertSame(5.0, $resultado);
    }

    public function test_divisao_por_zero_lanca_excecao(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Divisao por zero nao permitida');
        $this->calc->dividir(10, 0);
    }
}
