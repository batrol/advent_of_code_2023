<?php

class Desafio9
{
    private float $inicio;

    /**
     * @var Sequencia[]
     */
    private array $sequencias;

    /**
     * @var Sequencia[]
     */
    private array $sequenciasReversas;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->sequencias = [];
        foreach ($linhas as $linha) {
            $elementos = explode(' ', $linha);
            $this->sequencias[] = new Sequencia($elementos);
            $this->sequenciasReversas[] = new Sequencia(array_reverse($elementos));
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function calcularSequencias(): int
    {
        $proximosElementos = [];

        foreach ($this->sequencias as $sequencia) {
            $proximosElementos[] = $sequencia->descobrirProximoElemento();
        }

        $this->imprimirTempoGasto('calcular sequencias');

        return array_sum($proximosElementos);
    }

    public function calcularSequenciasAnteriores(): int
    {
        $elementosAnteriores = [];

        foreach ($this->sequenciasReversas as $sequencia) {
            $elementosAnteriores[] = $sequencia->descobrirProximoElemento();
        }

        $this->imprimirTempoGasto('calcular sequencias anteriores');

        return array_sum($elementosAnteriores);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }
}