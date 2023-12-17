<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio9('example_puzzle_input_1.txt');
//$desafio = new Desafio9('example_puzzle_input_2.txt');
$desafio = new Desafio9('puzzle_input.txt');
$desafio->lerArquivo();

$somaSequencias = $desafio->calcularSequencias();
echo sprintf('A soma dos próximos elementos de cada sequência é %d', $somaSequencias) . PHP_EOL;