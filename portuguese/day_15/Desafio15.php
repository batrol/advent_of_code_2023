<?php

class Desafio15
{
    private float $inicio;

    /**
     * @var string[]
     */
    private array $sequencia;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    public function lerArquivo(): void
    {
        $this->sequencia = explode(',', file_get_contents($this->caminhoDoArquivo));

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function traduzirSequenciaDeInicializacao(): int
    {
        $resultados = [];

        foreach ($this->sequencia as $passo) {
            $resultados[] = $this->traduzirPasso($passo);
        }

        $this->imprimirTempoGasto('traduzir sequências de inicialização');

        return array_sum($resultados);
    }

    private function traduzirPasso(string $passo): int
    {
        $caracteres = str_split($passo);
        $resultado =0;
        foreach ($caracteres as $caractere) {
            $resultado += ord($caractere);
            $resultado *= 17;
            $resultado = $resultado % 256;
        }

        return $resultado;
    }
}