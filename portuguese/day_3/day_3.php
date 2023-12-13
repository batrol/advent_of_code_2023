<?php

//$input = file_get_contents('example_puzzle_input_1.txt');
$input = file_get_contents('puzzle_input.txt');
$linhas = explode(PHP_EOL, $input);
array_walk($linhas, function (string &$linha) {
    $linha = str_split($linha);
});

function numeroEAdjacenteAUmSimbolo(array $linhas, string $numero, int $linha, int $colunaFinal): bool
{
    $colunaInicial = $colunaFinal - strlen($numero) + 1;
    if (!$numero) {
        return false;
    }

    if (temSimbolo($linhas, $linha, $colunaInicial - 1)) {
        return true;
    }

    if (temSimbolo($linhas, $linha, $colunaFinal + 1)) {
        return true;
    }

    if ($linha > 0) {
        for ($i = $colunaInicial - 1; $i <= $colunaFinal + 1; $i++) {
            if (temSimbolo($linhas, $linha - 1, $i)) {
                return true;
            }
        }
    }

    if ($linha < count($linhas) - 1) {
        for ($i = $colunaInicial - 1; $i <= $colunaFinal + 1; $i++) {
            if (temSimbolo($linhas, $linha + 1, $i)) {
                return true;
            }
        }
    }

    return false;
}

function numerosAdjacentesAUmAsterisco(array $linhas, string $numero, int $linha, int $coluna): array
{
    $numerosAdjacentes = [];
    if (temNumero($linhas, $linha, $coluna - 1)) {
        $numerosAdjacentes[] = obterNumero($linhas, $linha, $coluna - 1);
    }

    if (temNumero($linhas, $linha, $coluna + 1)) {
        $numerosAdjacentes[] = obterNumero($linhas, $linha, $coluna + 1);
    }

    if ($linha > 0) {
        for ($i = $coluna - 1; $i <= $coluna + 1; $i++) {
            if (temNumero($linhas, $linha - 1, $i)) {
                $numerosAdjacentes[] = obterNumero($linhas, $linha - 1, $i);
            }
        }
    }

    if ($linha < count($linhas) - 1) {
        for ($i = $coluna - 1; $i <= $coluna + 1; $i++) {
            if (temNumero($linhas, $linha + 1, $i)) {
                $numerosAdjacentes[] = obterNumero($linhas, $linha + 1, $i);
            }
        }
    }

    print_r(array_unique($numerosAdjacentes));

    return array_values(array_unique($numerosAdjacentes));
}

function temSimbolo($linhas, $linha, $coluna): bool
{
//    echo sprintf('temSimbolo(%d, %d)', $linha, $coluna) . PHP_EOL;

    if (!isset($linhas[$linha][$coluna])) {
        return false;
    }

    return $linhas[$linha][$coluna] !== '.' && !is_numeric($linhas[$linha][$coluna]);
}

function temNumero($linhas, $linha, $coluna): bool
{
    if (!isset($linhas[$linha][$coluna])) {
        return false;
    }

    return is_numeric($linhas[$linha][$coluna]);
}

function obterNumero($linhas, $linha, $coluna): int
{
    $coluna--;
    while (isset($linhas[$linha][$coluna])) {
        if (!is_numeric($linhas[$linha][$coluna])) {
            break;
        }

        $coluna--;
    }

    $numero = '';

    $coluna++;
    while (isset($linhas[$linha][$coluna])) {
        if (!is_numeric($linhas[$linha][$coluna])) {
            break;
        }

        $numero .= $linhas[$linha][$coluna];

        $coluna++;
    }

    return (int)$numero;
}

/**
 * @var array<array> $linhas
 */
$partNumbers = [];
$ratios = [];
foreach ($linhas as $linha => $colunas) {
    $numero = '';
    foreach ($colunas as $coluna => $valor) {
        if (is_numeric($valor)) {
            $numero .= $valor;

            continue;
        }

        if (numeroEAdjacenteAUmSimbolo($linhas, $numero, $linha, $coluna - 1)) {
            $partNumbers[] = $numero;
        }

        if ($valor === '*') {
            $numerosAdjacentes = numerosAdjacentesAUmAsterisco($linhas, $numero, $linha, $coluna);
            if (count($numerosAdjacentes) === 2) {
                $ratios[] = $numerosAdjacentes[0] * $numerosAdjacentes[1];
            }
        }

        $numero = '';
    }

    if (numeroEAdjacenteAUmSimbolo($linhas, $numero, $linha, count($colunas) - 1)) {
        $partNumbers[] = $numero;
    }
}

echo PHP_EOL;
echo sprintf('A soma das partes encontradas é %d', array_sum($partNumbers));
echo PHP_EOL;
echo sprintf('A soma das relações de marcha encontradas é %d', array_sum($ratios));