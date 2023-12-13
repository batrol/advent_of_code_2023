<?php

const NUMEROS_POR_EXTENSO = [
    'one' => '1',
    'two' => '2',
    'three' => '3',
    'four' => '4',
    'five' => '5',
    'six' => '6',
    'seven' => '7',
    'eight' => '8',
    'nine' => '9',
];

$input = file_get_contents('puzzle_input.txt');
$lines = explode(PHP_EOL, $input);

function substituirNumeroPorExtenso(string &$line, int $i): bool
{
    $substituido = false;
    $valorJaProcessado = $i > 0 ? substr($line, 0, $i) : '';
    $valoresSubstituidos = substr($line, $i);
    foreach (NUMEROS_POR_EXTENSO as $numeroPorExtenso => $numero) {
        if (str_starts_with($valoresSubstituidos, $numeroPorExtenso)) {
            $substituido = true;
            $valoresSubstituidos = str_replace($numeroPorExtenso, $numero, $valoresSubstituidos);

            break;
        }
    }

    $line = $valorJaProcessado . $valoresSubstituidos;

    return $substituido;
}

function substituirNumeroPorExtensoAPartirDoFim(string &$line, int $i): bool
{
    var_dump($line);
    $substituido = false;
    $valorJaProcessado = $i + 1 === strlen($line) ? '' : substr($line, $i + 1);
    var_dump($valorJaProcessado);

    $valoresSubstituidos = substr($line, 0, $i + 1);
    var_dump($valoresSubstituidos);

    foreach (NUMEROS_POR_EXTENSO as $numeroPorExtenso => $numero) {
        if (str_ends_with($valoresSubstituidos, $numeroPorExtenso)) {
            $substituido = true;
            $valoresSubstituidos = str_replace($numeroPorExtenso, $numero, $valoresSubstituidos);

            break;
        }
    }

    var_dump($valoresSubstituidos . $valorJaProcessado);

    $line = $valoresSubstituidos . $valorJaProcessado;

    return $substituido;
}

/**
 * @var array<array|string> $lines
 */
array_walk($lines, function (&$line) {
    for ($i = 0; $i < strlen($line); $i++) {
        if (is_numeric($line[$i])) {
            break;
        }

        if (substituirNumeroPorExtenso($line, $i)) {
            break;
        }
    }
    for ($i = strlen($line) - 1; $i >= 0; $i--) {
        if (is_numeric($line[$i])) {
            break;
        }

        if (substituirNumeroPorExtensoAPartirDoFim($line, $i)) {
            break;
        }
    }

    $line = array_values(
        array_filter(
            str_split($line),
            function (string $caracter) {
                return is_numeric($caracter);
            }
        )
    );
});

var_dump($lines);

$soma = 0;

foreach ($lines as $line) {
    $primeiroNumero = $line[0];

    $ultimoIndice = count($line) - 1;
    $ultimoNumero = $line[$ultimoIndice];

    $soma += intval($primeiroNumero . $ultimoNumero);
}

echo sprintf("a soma dos valores do documento de calibragem é %d", $soma);