<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//$desafio = new Desafio16('example_puzzle_input_1.txt');
$desafio = new Desafio16('puzzle_input.txt');
$desafio->lerArquivo();

$partesEnergizadas = $desafio->mapearPartesEnergizadas(-1, 0, Desafio16::DIRECAO_DIREITA);
echo sprintf('O número de partes energizadas é é %d', $partesEnergizadas) . PHP_EOL;

$partesEnergizadas = $desafio->mapearPartesEnergizadasComMaiorPotencial();
echo sprintf('O número de partes energizadas a partir do ponto com maior potencial é é %d', $partesEnergizadas) . PHP_EOL;