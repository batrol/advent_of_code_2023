<?php

class Mao
{
    private const MAPA = [
        'A' => 'F',
        'K' => 'E',
        'Q' => 'D',
        'J' => 'C',
        'T' => 'B',
    ];
    private const MAPA2 = [
        'A' => 14,
        'K' => 13,
        'Q' => 12,
        'J' => 11,
        'T' => 10,
    ];

    private int $pontosPorTipo;

    public function __construct(
        private readonly string $cartas,
        private readonly int $bid,
    ) {
        $this->pontosPorTipo = $this->calcularPontosPorTipo();
//        echo $this->pontosPorTipo . PHP_EOL;
    }

    public function getPontosPorTipo(): int
    {
        return $this->pontosPorTipo;
    }

    private function calcularPontosPorTipo(): int
    {
//        echo ($this->cartas) . PHP_EOL;
        $cartas = str_split($this->cartas);

        $quantidades = [];
        foreach ($cartas as $carta) {
            if (!isset($quantidades[$carta])) {
                $quantidades[$carta] = 1;

                continue;
            }

            $quantidades[$carta]++;
        }

//        echo (count($quantidades)) . PHP_EOL;

        if (count($quantidades) === 5) {
            return 1; //high card
        }

        if (count($quantidades) === 4) {
            return 2; //one pair
        }

        if (count($quantidades) === 1) {
            return 7; //five of a kind
        }

        if (count(array_filter($quantidades, function ($quantidade) {
                return $quantidade === 4;
            })) > 0) {
            return 6; //four of a kind
        }

        if (count(array_filter($quantidades, function ($quantidade) {
                return $quantidade === 3;
            })) > 0 && count(array_filter($quantidades, function ($quantidade) {
                return $quantidade === 2;
            })) > 0) {
            return 5; //full house
        }

        if (count(array_filter($quantidades, function ($quantidade) {
                return $quantidade === 3;
            })) > 0) {
            return 4; //three of a kind
        }

        return 3; // two pair
    }

    public function getCartasParaOrdenacao(): int
    {
//        $cartas = str_split(str_replace(array_keys(self::MAPA), self::MAPA, $this->cartas));
        $cartas = str_split($this->cartas);

        $pontos = 0;
        foreach ($cartas as $index => $carta) {
            $base = (int)(pow(15, (count($cartas) - $index)));
            if (is_numeric($carta)) {
                $pontos += ((int)$carta) * $base;

                continue;
            }

            $pontos += self::MAPA2[$carta] * $base;
        }

        return $pontos;
    }

    public function getBid(): int
    {
        return $this->bid;
    }

    public function getCartas(): string
    {
        return $this->cartas;
    }
}