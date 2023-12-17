<?php

class Desafio8
{
    private float $inicio;

    /**
     * @var No[]
     */
    private array $mapa;

    /**
     * @var string[]
     */
    private array $instrucoes;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->instrucoes = str_split(array_shift($linhas));

        $this->mapear($linhas);

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function mapear(array $linhas): void
    {
        $this->mapa = [];

        foreach ($linhas as $linha) {
            list($coordenada, $esquerda, $direita) = explode(' ', str_replace([' =', '(', ')', ','], '', $linha));
            $this->mapa[$coordenada] = new No($esquerda, $direita);
        }
    }

    public function calcularCaminho(): int
    {
        $passos = 0;
        $proximoNo = 'AAA';
        while ($proximoNo !== 'ZZZ') {
            foreach ($this->instrucoes as $instrucao) {
                $no = $this->mapa[$proximoNo];

                $proximoNo = $no->getNo($instrucao);
                $passos++;

                if ($proximoNo === 'ZZZ') {
                    break;
                }
            }
        }

        $this->imprimirTempoGasto('calcular caminho');

        return $passos;
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
                return $a->getCartasParaOrdenacao() < $b->getCartasParaOrdenacao() ? -1 : 1;
//                return strcmp($a->getCartasParaOrdenacao(), $b->getCartasParaOrdenacao());
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