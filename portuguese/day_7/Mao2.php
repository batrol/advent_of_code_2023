<?php

class Mao2
{
    private const MAPA = [
        'A' => 'E',
        'K' => 'D',
        'Q' => 'C',
        'J' => '1',
        'T' => 'B',
    ];

    private int $pontosPorTipo;

    public function __construct(
        private readonly string $cartas,
        private readonly int $bid,
    ) {
        $this->pontosPorTipo = $this->calcularPontosPorTipo();
    }

    public function getPontosPorTipo(): int
    {
        return $this->pontosPorTipo;
    }

    private function calcularPontosPorTipo(): int
    {
        $cartas = str_split($this->cartas);

        $quantidades = [];
        foreach ($cartas as $carta) {
            if (!isset($quantidades[$carta])) {
                $quantidades[$carta] = 1;

                continue;
            }

            $quantidades[$carta]++;
        }

        if (count($quantidades) === 1) {
            return 7; //five of a kind
        }

        $quantidadeCoringas = $quantidades['J'] ?? 0;
        unset($quantidades['J']);

        arsort($quantidades);

        foreach ($quantidades as $carta => $quantidade) {
            $quantidades[$carta] += $quantidadeCoringas;

            break;
        }

        if (count($quantidades) === 5) {
            return 1; //high card
        }

        if (count($quantidades) === 4) {
            return 2; //one pair
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

    public function getCartasParaOrdenacao(): string
    {
        return str_replace(array_keys(self::MAPA), self::MAPA, $this->cartas);
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