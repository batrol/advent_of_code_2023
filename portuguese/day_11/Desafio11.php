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

    public function expandirUniverso2(): void
    {
        $this->expandirLinhas2();
        $this->transpor();
        $this->expandirLinhas2();
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

    public function expandirLinhas2(): void
    {
        $novoUniverso = [];
        foreach ($this->universo as $linha) {
            if (in_array('#', $linha)) {
                $novoUniverso[] = $linha;

                continue;
            }

            $novoUniverso[] = str_split(str_replace('.', 'X', implode('', $linha)));
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

        $this->imprimirTempoGasto('calcular menor caminho');

        return array_sum($distancias);
    }

    public function calcularMenorCaminho2(int $tamanhoExpansao): int
    {
        $this->galaxias = [];
        foreach ($this->universo as $y => $linha) {
            foreach ($linha as $x => $coluna) {
                if ($coluna === '#') {
                    $this->galaxias[] = [$y, $x];
                }
            }
        }

        echo sprintf('encontradas %d galaxias', count($this->galaxias)) . PHP_EOL;

        $distancias = [];

        for ($i = 0; $i < count($this->galaxias) - 1; $i++) {
            echo $i . ' ' . PHP_EOL;
            for ($j = $i + 1; $j < count($this->galaxias); $j++) {
                list ($y1, $x1) = $this->galaxias[$i];
                list ($y2, $x2) = $this->galaxias[$j];

                $linha = substr(
                    implode('', $this->universo[$y1]),
                    $x1 < $x2 ? $x1 : $x2,
                    abs($x2 - $x1)
                );
//                echo $linha . PHP_EOL;
                $tamanhoLinha = (substr_count($linha, 'X') * $tamanhoExpansao)
                    + strlen(str_replace('X', '', $linha));

                $this->transpor();

                $coluna = substr(
                    implode('', $this->universo[$x1]),
                    $y1 < $y2 ? $y1 : $y2,
                    abs($y2 - $y1)
                );
//                echo $coluna . PHP_EOL;
                $tamanhoColuna = (substr_count($coluna, 'X') * $tamanhoExpansao)
                    + strlen(str_replace('X', '', $coluna));
                $this->transpor();

                $distancias[] = $tamanhoLinha + $tamanhoColuna;
            }
        }

        $this->imprimirTempoGasto('calcular menor caminho');

        return array_sum($distancias);
    }
}