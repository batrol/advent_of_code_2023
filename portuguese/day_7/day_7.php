<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio7('example_puzzle_input_1.txt');
//$desafio = new Desafio7('example_puzzle_input_2.txt');
$desafio = new Desafio7('puzzle_input.txt');
$desafio->lerArquivo();

//$ganhos = $desafio->calcularMaos(1);
//
//echo sprintf('O total de ganhos para as mãos apresentadas é %d', $ganhos) . PHP_EOL;
//250057777 (too low)
//250112975 (too high)
//250287918 (too high)
//250080656
//250058342

$ganhos = $desafio->calcularMaos(2);

echo sprintf('O total de ganhos para as mãos apresentadas é %d', $ganhos) . PHP_EOL;
//250414375 (too low)
//251303347 (too high)