<?php

class No
{
    public function __construct(
        private readonly string $esquerda,
        private readonly string $direita,
    ) {
    }

    public function getNo(string $esquerdaOuDireita): string
    {
        return $esquerdaOuDireita === 'L' ? $this->esquerda : $this->direita;
    }
}