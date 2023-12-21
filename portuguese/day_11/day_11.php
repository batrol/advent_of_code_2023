<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio11('example_puzzle_input_1.txt');
//$desafio = new Desafio11('example_puzzle_input_2.txt');
$desafio = new Desafio11('puzzle_input.txt');
$desafio->lerArquivo();
$desafio->expandirUniverso();
$somaDistancias = $desafio->calcularMenorCaminho();
echo sprintf('A soma das menores distâncias é %d', $somaDistancias) . PHP_EOL;

$desafio->lerArquivo();
$desafio->expandirUniverso2();
$somaDistancias = $desafio->calcularMenorCaminho2(1000000);
echo sprintf('A soma das menores distâncias é %d', $somaDistancias) . PHP_EOL;