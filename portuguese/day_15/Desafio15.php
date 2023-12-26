<?php

class Desafio15
{
    private float $inicio;

    /**
     * @var string[]
     */
    private array $sequencia;

    /**
     * @var string[][]
     */
    private array $caixas;

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
        $resultado = 0;
        foreach ($caracteres as $caractere) {
            $resultado += ord($caractere);
            $resultado *= 17;
            $resultado = $resultado % 256;
        }

        return $resultado;
    }

    public function substituirLentes(): int
    {
        for ($i = 0; $i < 256; $i++) {
            $this->caixas[$i] = [];
        }

        foreach ($this->sequencia as $passo) {
            if (str_contains($passo, '-')) {
                $this->removerLente($passo);
            } else {
                $this->adicionarLente($passo);
            }
        }

        $caixas = array_filter($this->caixas);

        foreach ($caixas as $caixa => $lentes){
            $i = 0;
            while ($lente = array_shift($lentes)) {
                $i++;

                $resultado[] = ($caixa + 1) * $i * $lente;
            }
        }

        return array_sum($resultado);
    }

    private function adicionarLente(string $passo): void
    {
        list($lente, $foco) = explode('=', $passo);
        $caixa = $this->traduzirPasso($lente);

        $this->caixas[$caixa][$lente] = $foco;
    }

    private function removerLente(string $passo): void
    {

        list($lente, $foco) = explode('-', $passo);
        $caixa = $this->traduzirPasso($lente);

        unset($this->caixas[$caixa][$lente]);
    }
}