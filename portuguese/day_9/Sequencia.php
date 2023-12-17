<?php

class Sequencia
{
    /**
     * @var int[]
     */
    private array $incrementos;

    private int $incremento = 0;

    private ?Sequencia $proximaSequencia;

    /**
     * @param int[] $elementos
     */
    public function __construct(private readonly array $elementos)
    {
        $this->prepararIncrementos();
        $this->prepararProximaSequencia();
    }

    private function prepararIncrementos(): void
    {
        $base = $this->elementos;
        array_pop($base);

        $aux = $this->elementos;
        array_shift($aux);

        $this->incrementos = [];
        while (count($base)) {
            $this->incrementos[] = array_shift($aux) - array_shift($base);
        }
    }

    private function prepararProximaSequencia(): void
    {
        $incrementosUnicos = array_unique($this->incrementos);
        if (count($incrementosUnicos) === 1) {
            $this->proximaSequencia = null;
            $this->incremento = array_shift($incrementosUnicos);

            return;
        }

        $base = $this->elementos;
        array_pop($base);
        $this->proximaSequencia = new Sequencia($this->incrementos);
    }

    public function descobrirProximoElemento(): int
    {
        $elemento = $this->elementos[array_key_last($this->elementos)];
        $incremento = (($this->proximaSequencia === null)
            ? $this->incremento
            : $this->proximaSequencia->descobrirProximoElemento());

//        print_r([implode(' ', $this->elementos), $elemento, $this->incremento, $incremento, $elemento + $incremento]);

        return $elemento + $incremento;
    }
}