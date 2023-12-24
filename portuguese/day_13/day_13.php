<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio13('example_puzzle_input_1.txt');
$desafio = new Desafio13('example_puzzle_input_2.txt');
//$desafio = new Desafio13('puzzle_input.txt');
$desafio->lerArquivo();

$resumo = $desafio->encontrarReflexos();
echo sprintf('O resumo dos reflexos é %d', $resumo) . PHP_EOL;

$resumo = $desafio->encontrarReflexos2();
echo sprintf('O resumo dos reflexo com espelhosCorrigidos é %d', $resumo) . PHP_EOL;
//21896 too low
//37825 too high