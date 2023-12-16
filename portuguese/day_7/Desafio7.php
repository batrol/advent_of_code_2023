<?php

class Desafio7
{
    private float $inicio;

    /**
     * @var Mao[]
     */
    private array $maos;

    /**
     * @var Mao2[]
     */
    private array $maos2;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $maos = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $maos2 = $maos;
        array_walk($maos, function (&$mao) {
            list ($cartas, $bid) = explode(' ', trim($mao));
            $mao = new Mao($cartas, (int)$bid);
        });

        array_walk($maos2, function (&$mao) {
            list ($cartas, $bid) = explode(' ', trim($mao));
            $mao = new Mao2($cartas, (int)$bid);
        });

        $this->maos = $maos;
        $this->maos2 = $maos2;

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function calcularMaos(int $parteDesafio): int
    {
        $this->ordenarMaos($parteDesafio);

        $pontuacoes = $this->calcularPontuacoes($parteDesafio);

        $this->imprimirTempoGasto('calcular maos');

        return array_sum($pontuacoes);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    private function ordenarMaos(int $parteDesafio): void
    {
        usort($this->maos2, function (Mao|Mao2 $a, Mao|Mao2 $b) {
            $pontuacaoA = $a->getPontosPorTipo();
            $pontuacaoB = $b->getPontosPorTipo();

            if ($pontuacaoA === $pontuacaoB) {
                return strcmp($a->getCartasParaOrdenacao(), $b->getCartasParaOrdenacao());
            }

            return $pontuacaoA < $pontuacaoB ? -1 : 1;
        });
    }

    private function calcularPontuacoes(int $parteDesafio): array
    {
        $pontuacoes = [];
        foreach ($parteDesafio === 1 ? $this->maos : $this->maos2 as $index => $mao) {
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