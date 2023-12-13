<?php

//$input = file_get_contents('example_puzzle_input_1.txt');
$input = file_get_contents('puzzle_input.txt');
$linhas = explode(PHP_EOL, $input);

$cartoes = [];
$pontuacao = [];
foreach ($linhas as $linha) {
    if (!$linha) {
        continue;
    }

    list($numeroDoCartao, $numeros) = explode(':', $linha);
    list($numerosVencedores, $numerosQueEuTenho) = explode('|', $numeros);

    $numeroDoCartao = (int)str_replace('Card ', '', $numeroDoCartao);

    $numerosVencedores = explode(' ', trim($numerosVencedores));
    $numerosVencedores = array_values(array_filter($numerosVencedores));
    array_walk($numerosVencedores, function (string &$numero) {
        $numero = (int)$numero;
    });

    $numerosQueEuTenho = explode(' ', trim($numerosQueEuTenho));
    $numerosQueEuTenho = array_values(array_filter($numerosQueEuTenho));
    array_walk($numerosQueEuTenho, function (string &$numero) {
        $numero = (int)$numero;
    });

    $acertos = count(array_intersect($numerosVencedores, $numerosQueEuTenho));

    $cartoes[$numeroDoCartao] = [$numerosVencedores, $numerosQueEuTenho, $acertos, 1];

    $pontuacao[$numeroDoCartao] = $acertos === 0 ? 0 : pow(2, $acertos - 1);
}

foreach ($cartoes as $numeroDoCartao => $dados) {
    $acertos = $dados[2];

    for ($i = 1; $i <= $acertos; $i++) {
        $j = $i + $numeroDoCartao;
        $cartoes[$j][3] += $cartoes[$numeroDoCartao][3];
    }
}

//print_r($cartoes);

echo sprintf('A soma da pontuação das raspadinhas é %d', array_sum($pontuacao));
echo PHP_EOL;
echo sprintf('A quantidade final de cartões é %d', array_sum(array_column($cartoes, 3)));