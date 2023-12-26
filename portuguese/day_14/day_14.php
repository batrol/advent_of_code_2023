<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio14('example_puzzle_input_1.txt');
$desafio = new Desafio14('puzzle_input.txt');
$desafio->lerArquivo();

$peso = $desafio->inclinarParaONorte();
echo sprintf('O peso total ao inclinar para o norte é %d', $peso) . PHP_EOL;