<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio12('example_puzzle_input_1.txt');
//$desafio = new Desafio12('example_puzzle_input_2.txt');
$desafio = new Desafio12('puzzle_input.txt');

$desafio->lerArquivo();
$arranjos = $desafio->substituirDesconhecidos();
echo sprintf('A quantidade de arranjos possíveis é %d', $arranjos) . PHP_EOL;

$desafio->lerArquivo2();
$arranjos = $desafio->substituirDesconhecidos();
echo sprintf('A quantidade de arranjos possíveis é %d', $arranjos) . PHP_EOL;
//7163 too low