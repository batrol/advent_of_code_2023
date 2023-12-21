<?php

class Desafio11
{
    private float $inicio;

    /**
     * @var string[][]
     */
    private array $universo;

    private array $galaxias;

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
        $this->universo = explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo));

        array_walk($this->universo, function (&$linha) {
            $linha = str_split($linha);
        });

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function expandirUniverso(): void
    {
        $this->expandirLinhas();
        $this->transpor();
        $this->expandirLinhas();
        $this->transpor();

        $this->imprimirTempoGasto('expandir universo');
    }

    public function expandirLinhas(): void
    {
        $novoUniverso = [];
        foreach ($this->universo as $linha) {
            $novoUniverso[] = $linha;
            if (in_array('#', $linha)) {
                continue;
            }

            $novoUniverso[] = $linha;
        }

        $this->universo = $novoUniverso;
    }

    private function transpor(): void
    {
        $novoUniverso = [];
        foreach ($this->universo as $y => $linha) {
            foreach ($linha as $x => $coluna) {
                $novoUniverso[$x][$y] = $coluna;
            }
        }

        $this->universo = $novoUniverso;
    }

    public function calcularMenorCaminho(): int
    {
        foreach ($this->universo as $y => $linha) {
            foreach ($linha as $x => $coluna) {
                if ($coluna === '#') {
                    $this->galaxias[] = [$y, $x];
                }
            }
        }

        $distancias = [];

        for ($i = 0; $i < count($this->galaxias) - 1; $i++) {
            for ($j = $i + 1; $j < count($this->galaxias); $j++) {
                $distancias[] = abs($this->galaxias[$i][0] - $this->galaxias[$j][0])
                    + abs($this->galaxias[$i][1] - $this->galaxias[$j][1]);
            }
        }

        return array_sum($distancias);
    }
}