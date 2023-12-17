<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio8('example_puzzle_input_1.txt');
//$desafio = new Desafio8('example_puzzle_input_2.txt');
//$desafio = new Desafio8('example_puzzle_input_3.txt');
$desafio = new Desafio8('puzzle_input.txt');
$desafio->lerArquivo();

//$passos = $desafio->calcularCaminho('AAA', 'ZZZ');
//echo sprintf('A quantidade de passos para chegar ao fim do caminho é %d', $passos) . PHP_EOL;

$passos = $desafio->calcularCaminhoSimultaneamente();
echo sprintf('A quantidade de passos para chegar ao fim simultaneamente é %d', $passos) . PHP_EOL;