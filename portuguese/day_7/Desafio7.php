<?php

class Desafio7
{
    private float $inicio;

    /**
     * @var Mao[]
     */
    private array $maos;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $maos = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        array_walk($maos, function (&$mao) {
            list ($cartas, $bid) = explode(' ', trim($mao));
            $mao = new Mao($cartas, (int)$bid);
        });

        $this->maos = $maos;

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function calcularMaos(): int
    {
        $this->ordenarMaos();

        $pontuacoes = $this->calcularPontuacoes();

        $this->imprimirTempoGasto('calcular maos');

        return array_sum($pontuacoes);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    private function ordenarMaos(): void
    {
        usort($this->maos, function (Mao $a, Mao $b) {
            $pontuacaoA = $a->getPontosPorTipo();
            $pontuacaoB = $b->getPontosPorTipo();

            if ($pontuacaoA === $pontuacaoB) {
                //deveria ter usado strcmp()
                return ($a->getCartasParaOrdenacao() < $b->getCartasParaOrdenacao()) ? -1 : 1;
            }

            return $pontuacaoA < $pontuacaoB ? -1 : 1;
        });
    }

    private function calcularPontuacoes(): array
    {
        $pontuacoes = [];
        foreach ($this->maos as $index => $mao) {
            echo sprintf(
                    'Mao %s %s - %d - %d - %d',
                    $mao->getCartas(),
                    $mao->getCartasParaOrdenacao(),
                    $mao->getBid(),
                    $index + 1,
                    $mao->getPontosPorTipo()
                ) . PHP_EOL;
            $pontuacoes[] = $mao->getBid() * ($index + 1);
        }

//        print_r($pontuacoes);

        return $pontuacoes;
    }
}