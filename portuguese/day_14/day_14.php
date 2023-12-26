<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio14('example_puzzle_input_1.txt');
$desafio = new Desafio14('puzzle_input.txt');
$desafio->lerArquivo();

$peso = $desafio->inclinarNegativo();
echo sprintf('O peso total ao inclinar para o norte é %d', $peso) . PHP_EOL;

$ciclos = 1000000000;
//for ($ciclos = 1; $ciclos < 100; $ciclos++) {
    echo PHP_EOL;
    echo $ciclos;
    echo PHP_EOL;

    $desafio->lerArquivo();
    $peso = $desafio->inclinarCiclico($ciclos, true);
    echo sprintf('O peso total ao inclinar por %d ciclos é %d', $ciclos, $peso) . PHP_EOL;
//    $desafio->lerArquivo();
//    $peso = $desafio->inclinarCiclico($ciclos, false);
//    echo sprintf('O peso total ao inclinar por %d ciclos é %d', $ciclos, $peso) . PHP_EOL;
//}