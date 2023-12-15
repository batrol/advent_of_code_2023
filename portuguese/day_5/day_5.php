<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$desafio = new Desafio('example_puzzle_input_1.txt');
//$desafio = new Desafio('puzzle_input.txt');
$desafio->lerArquivo();
$desafio->imprimirTempoGasto();

$localizacaoMaisProxima = $desafio->mapear();
$desafio->imprimirTempoGasto();

echo sprintf('A localização mais próxima é %d', $localizacaoMaisProxima) . PHP_EOL;