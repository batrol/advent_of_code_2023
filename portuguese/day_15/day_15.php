<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio15('example_puzzle_input_1.txt');
$desafio = new Desafio15('puzzle_input.txt');
$desafio->lerArquivo();

$somaDosResultados = $desafio->traduzirSequenciaDeInicializacao();
echo sprintf('A soma dos resultados da sequência de inicialização é %d', $somaDosResultados) . PHP_EOL;

$foco = $desafio->substituirLentes();
echo sprintf('O foco total das lentes é %d', $foco) . PHP_EOL;