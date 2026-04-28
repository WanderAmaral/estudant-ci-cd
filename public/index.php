<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Calculadora;

$calc = new Calculadora();

$resultados = [
    'soma'        => $calc->somar(10, 5),
    'subtracao'   => $calc->subtrair(10, 5),
    'multiplicacao' => $calc->multiplicar(10, 5),
    'divisao'     => $calc->dividir(10, 5),
];

header('Content-Type: application/json');
echo json_encode([
    'status'     => 'ok',
    'mensagem'   => 'CI/CD com PHP funcionando!',
    'resultados' => $resultados,
]);
