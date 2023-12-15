<?php

class FaixaDeSemente
{
    public function __construct(
        private readonly int $semente,
        private readonly int $tamanhoFaixa
    ) {
    }

    public function contemValorSemente(int $semente): bool
    {
        return ($semente >= $this->semente) && ($semente < $this->semente + $this->tamanhoFaixa);
    }
}