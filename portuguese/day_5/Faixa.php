<?php

class Faixa
{
    public function __construct(
        private readonly int $valorOrigem,
        private readonly int $valorDestino,
        private readonly int $tamanhoFaixa
    ) {
    }

    public function contemValorOrigem(int $valorOrigem): bool
    {
        return ($valorOrigem >= $this->valorOrigem) && ($valorOrigem < $this->valorOrigem + $this->tamanhoFaixa);
    }

    public function getDiferencaOrigemDestino(): int
    {
        return $this->valorDestino - $this->valorOrigem;
    }
}