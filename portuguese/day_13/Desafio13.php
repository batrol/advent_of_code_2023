<?php

class Desafio13
{
    private float $inicio;

    /**
     * @var string[][][]
     */
    private array $padroes;

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
        $this->padroes = explode(PHP_EOL . PHP_EOL, file_get_contents($this->caminhoDoArquivo));
        array_walk($this->padroes, function (&$padrao) {
            $padrao = explode(PHP_EOL, $padrao);
            array_walk($padrao, function (&$linha) {
                $linha = str_split($linha);
            });
        });

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function encontrarReflexos(): int
    {
        $reflexos = [];

        foreach ($this->padroes as $padrao) {
            $reflexo = $this->encontrarReflexo($padrao, 100);
            if ($reflexo === 0) {
                $padrao = $this->transpor($padrao);
                $reflexo = $this->encontrarReflexo($padrao, 1);
            }

            $reflexos[] = $reflexo;
        }

        $this->imprimirTempoGasto('encontrar reflexos');

        return array_sum($reflexos) ?? 0;
    }

    /**
     * @param string[][] $padrao
     * @return string[][]
     */
    private function transpor(array $padrao): array
    {
        $novoPadrao = [];
        foreach ($padrao as $y => $linha) {
            foreach ($linha as $x => $coluna) {
                $novoPadrao[$x][$y] = $coluna;
            }
        }

        return $novoPadrao;
    }

    /**
     * @param string[][] $padrao
     * @return string[][]
     */
    private function espelhar(array $padrao): array
    {
        return array_reverse($padrao);
    }

    /**
     * @param string[][] $padrao
     */
    private function encontrarReflexo(array $padrao, int $peso): int
    {
        for ($i = 0; $i < count($padrao) -1; $i++) {
            $inicio1 = 0;
            $fim1 = $i;
            $inicio2 = $i + 1;
            $fim2 = ($i * 2) + 1;
            if ($fim2 >= count($padrao)) {
                $fim2 = count($padrao) - 1;
            }
            $diferenca1 = $fim1 - $inicio1;
            $diferenca2 = $fim2 - $inicio2;

            if ($diferenca1 !== $diferenca2) {
                $inicio1 = $i - $diferenca2;
            }

            $parte1 = array_slice($padrao, $inicio1, $diferenca2 + 1);
            $parte2 = $this->espelhar(array_slice($padrao, $inicio2, $diferenca2 + 1));

//            print_r([$inicio1, $fim1, $inicio2, $fim2]);
//            print_r($parte1);
//            echo PHP_EOL;
//            echo PHP_EOL;
//            echo PHP_EOL;
//            print_r($parte2);
//            exit;

            if ($parte1 === $parte2) {
                return ($i + 1) * $peso;
            }
        }

        return 0;
    }
}