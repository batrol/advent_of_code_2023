<?php

class Desafio14
{
    private float $inicio;

    /**
     * @var string[][]
     */
    private array $mapa;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    /**
     * @param string[][] $padrao
     */
    private function imprimir2D(array $mapa): void
    {
        foreach ($mapa as $linha) {
            echo implode('', $linha) . PHP_EOL;
        }
        echo PHP_EOL;
    }

    public function lerArquivo(): void
    {
        $this->mapa = explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo));
        array_walk($this->mapa, function (&$linha) {
            $linha = str_split($linha);
        });

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function inclinarParaONorte(): int
    {
        $this->imprimir2D($this->mapa);

        for ($y = 0; $y < count($this->mapa) - 1; $y++) {
            $linha = $this->mapa[$y];

            foreach ($linha as $x => $coluna) {
                if ($coluna !== '.') {
                    continue;
                }

                for ($i = $y + 1; $i < count($this->mapa); $i++) {
                    $coluna2 = $this->mapa[$i][$x];

                    if ($coluna2 === '#') {
                        break;
                    }

                    if ($coluna2 === 'O') {
                        $this->mapa[$y][$x] = 'O';
                        $this->mapa[$i][$x] = '.';

                        echo sprintf('trocou (%d, %d) por (%d, %d)', $x, $y, $x, $i) . PHP_EOL;

                        break;
                    }
                }
            }
        }

        $this->imprimirTempoGasto('encontrar reflexos');

        $this->imprimir2D($this->mapa);

        return $this->peso();
    }

    public function peso(): int
    {
        $peso = 0;
        foreach ($this->mapa as $y => $linha) {
            $peso += substr_count(implode('', $linha), 'O') * (count($this->mapa) - $y);
        }

        $this->imprimirTempoGasto('pesar');

        return $peso;
    }
}