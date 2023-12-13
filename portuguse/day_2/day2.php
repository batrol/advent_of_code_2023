<?php

//$input = file_get_contents('example_puzzle_input_1.txt');
$input = file_get_contents('puzzle_input.txt');
$games = array_filter(explode(PHP_EOL, $input));

const MAX_CUBES = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$jogadas = [];
foreach ($games as $game) {
    $parts = explode(':', $game, 2);

    $gameID = str_replace('Game ', '', $parts[0]);
    $jogadas[$gameID] = [];

    $conjuntos = explode(';', $parts[1]);
    foreach ($conjuntos as $conjunto) {
        $cubos = explode(',', trim($conjunto));
        array_walk($cubos, function (string &$cubo) {
            $cubo = trim($cubo);
        });

        $jogada = [];
        foreach ($cubos as $cubo) {
            $quantidades = explode(' ', $cubo);

            $jogada[$quantidades[1]] = $quantidades[0];
        }

        $jogadas[$gameID][] = $jogada;
    }
}

//foreach ($jogadas as $gameID => $conjuntos) {
//    foreach ($conjuntos as $conjunto) {
//        foreach (MAX_CUBES as $color => $maxValue) {
//            if (!isset($conjunto[$color])) {
//                continue;
//            }
//
//            if ($conjunto[$color] > $maxValue) {
//                unset($jogadas[$gameID]);
//
//                continue 2;
//            }
//        }
//    }
//}

$forcaDosJogos = [];
foreach ($jogadas as $gameID => $conjuntos) {
    $quantidadesMinimas = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];
    foreach ($conjuntos as $conjunto) {
        foreach ($quantidadesMinimas as $color => $quantidadeMinima) {
            if (!isset($conjunto[$color])) {
                continue;
            }

            if ($conjunto[$color] > $quantidadeMinima) {
                $quantidadesMinimas[$color] = $conjunto[$color];
            }
        }
    }

    $forcaDosJogos[$gameID] = $quantidadesMinimas['red'] * $quantidadesMinimas['green'] * $quantidadesMinimas['blue'];
}

//var_dump(array_sum(array_keys($jogadas)));
echo sprintf('A soma da força de todos os jogos é %d', array_sum($forcaDosJogos));
