<?php

class Mapeamento
{
    /**
     * @var Faixa[]
     */
    private array $faixasDePares = [];

    public function __construct(
        private readonly string $origem,
        private readonly string $destino,
    ) {
    }

    public function adicionarFaixa(string $linha): void
    {
        list($valorDestino, $valorOrigem, $tamanhoFaixa) = explode(' ', $linha);

        $this->faixasDePares[] = new Faixa($valorOrigem, $valorDestino, $tamanhoFaixa);
    }

    public function mapear(int $valorOrigem): int
    {
        /**
         * @var Faixa|null $faixa
         */
        $faixa = current(
            array_filter($this->faixasDePares, function (Faixa $faixa) use ($valorOrigem) {
                return $faixa->contemValorOrigem($valorOrigem);
            })
        );

        if (!$faixa) {
            return $valorOrigem;
        }

        return $valorOrigem + $faixa->getDiferencaOrigemDestino();
    }

    public function getDestino(): string
    {
        return $this->destino;
    }

    public function getOrigem(): string
    {
        return $this->origem;
    }

    public function mapearOrigem($valorDestino): int
    {
        /**
         * @var Faixa|null $faixa
         */
        $faixa = current(
            array_filter($this->faixasDePares, function (Faixa $faixa) use ($valorDestino) {
                return $faixa->contemValorDestino($valorDestino);
            })
        );

        if (!$faixa) {
            return $valorDestino;
        }

        return $valorDestino - $faixa->getDiferencaOrigemDestino();
    }
}