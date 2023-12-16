<?php

class Corrida
{
    public function __construct(
        private readonly int $tempo,
        private readonly int $distancia,
    ) {
    }

    public function calcularPossibilidadesDeVitoria(): int
    {
        $primeiraVitoria = 0;
        $ultimaVitoria = 0;

        for ($tempo = 1; $tempo < $this->tempo; $tempo++) {
            if ($this->venceu($tempo, $this->distancia)) {
                $primeiraVitoria = $tempo;
                $ultimaVitoria = $tempo;

                break;
            }
        }

        for ($tempo = $this->tempo - 1; $tempo > $primeiraVitoria; $tempo--) {
            if ($this->venceu($tempo, $this->distancia)) {
                $ultimaVitoria = $tempo;

                break;
            }
        }

        print_r([$this->tempo, $primeiraVitoria, $ultimaVitoria]);

        return $ultimaVitoria - $primeiraVitoria + 1;
    }

    private function venceu(int $tempoAcelerando, int $distanciaRecorde): bool
    {
        $distanciaPercorrida = ($this->tempo - $tempoAcelerando) * $tempoAcelerando;

        return $distanciaPercorrida > $distanciaRecorde;
    }
}