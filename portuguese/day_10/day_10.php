<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio10('example_puzzle_input_1.txt');
//$desafio = new Desafio10('example_puzzle_input_2.txt');
$desafio = new Desafio10('puzzle_input.txt');
$desafio->lerArquivo();

$pontoMaisDistante = $desafio->percorrerMapa();
echo sprintf('O ponto mais distante fica a %d passos', $pontoMaisDistante) . PHP_EOL;