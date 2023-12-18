<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio10('example_puzzle_input_1.txt');
//$desafio = new Desafio10('example_puzzle_input_2.txt');
//$desafio = new Desafio10('example_puzzle_input_3.txt');
//$desafio = new Desafio10('example_puzzle_input_4.txt');
$desafio = new Desafio10('puzzle_input.txt');
$desafio->lerArquivo();

$pontoMaisDistante = $desafio->percorrerMapa();
echo sprintf('O ponto mais distante fica a %d passos', $pontoMaisDistante) . PHP_EOL;

$areaInterna = $desafio->contarAreaInterna();
echo sprintf('A area interna do mapa tem %d blocos', $areaInterna) . PHP_EOL;
//504 (too high)
//138 (too low)
//494 (too high)