<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio6('example_puzzle_input_1.txt');
$desafio = new Desafio6('puzzle_input.txt');
$desafio->lerArquivo();

$possibilidadesDeVitoria = $desafio->calcularPossibilidadesDeVitoria();

echo sprintf('O número de possibilidades de vitória é %d', $possibilidadesDeVitoria) . PHP_EOL;