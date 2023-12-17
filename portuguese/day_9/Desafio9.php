<?php

class Desafio9
{
    private float $inicio;

    /**
     * @var Sequencia[]
     */
    private array $sequencias;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->sequencias = [];
        foreach ($linhas as $linha) {
            $this->sequencias[] = new Sequencia(explode(' ', $linha));
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function calcularSequencias(): int
    {
        $proximosElementos = [];

        foreach ($this->sequencias as $sequencia) {
//            echo '____' . PHP_EOL;
            $proximosElementos[] = $sequencia->descobrirProximoElemento();
        }

//        print_r($proximosElementos);

        $this->imprimirTempoGasto('calcular sequencias');

        return array_sum($proximosElementos);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }
}