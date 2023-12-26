<?php

class Desafio14
{
    private float $inicio;

    /**
     * @var string[][]
     */
    private array $mapa;

    private array $hashes = [];

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    private function transpor(): void
    {
        $novoMapa = [];
        foreach ($this->mapa as $y => $linha) {
            foreach ($linha as $x => $coluna) {
                $novoMapa[$x][$y] = $coluna;
            }
        }

        $this->mapa = $novoMapa;
    }

    /**
     * @param string[][] $mapa
     */
    private function imprimir2D(array $mapa): void
    {
        foreach ($mapa as $linha) {
            echo implode('', $linha) . PHP_EOL;
        }
        echo PHP_EOL;
    }

    /**
     * @param string[][] $mapa
     */
    private function hash(array $mapa): string
    {
        $mapa1D = '';
        foreach ($mapa as $linha) {
            $mapa1D .= implode('', $linha) . PHP_EOL;
        }

        return hash('md5', $mapa1D);
    }

    public function lerArquivo(): void
    {
        $this->mapa = explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo));
        array_walk($this->mapa, function (&$linha) {
            $linha = str_split($linha);
        });

        $this->hashes = [];

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function inclinarCiclico(int $count, bool $pularRepetidos): int
    {
        $primeiroHash = null;
        $indicePrimeiroHash = null;
        for ($i = 1; $i <= $count; $i++) {
            $this->inclinarNegativo();

            $this->transpor();
            $this->inclinarNegativo();

            $this->transpor();
            $this->inclinarPositivo();

            $this->transpor();
            $this->inclinarPositivo();

            $this->transpor();

            if ($pularRepetidos) {
                $hash = $this->hash($this->mapa);
                if (array_key_exists($hash, $this->hashes)) {
                    if ($primeiroHash === null) {
                        $primeiroHash = $hash;
                        $indicePrimeiroHash = $i;
                    } else if($hash === $primeiroHash) {
                        $repeticoes = $i - $indicePrimeiroHash;
                        while ($i + $repeticoes < $count) {
                            $i += $repeticoes;
                        }
                    }
                }

                $this->hashes[$hash] = $i;
            }

//            $this->imprimir2D($this->mapa);

//            echo $i . ' - ' . $this->peso() . PHP_EOL;
        }

        return $this->peso();
    }

    public function inclinarNegativo(): int
    {
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

//                        echo sprintf('trocou (%d, %d) por (%d, %d)', $x, $y, $x, $i) . PHP_EOL;

                        break;
                    }
                }
            }
        }

//        $this->imprimirTempoGasto('encontrar reflexos');

//        $this->imprimir2D($this->mapa);

        return $this->peso();
    }

    //TODO: remover código duplicado
    public function inclinarPositivo(): int
    {
        for ($y = count($this->mapa) - 1; $y > 0; $y--) {
            $linha = $this->mapa[$y];

            foreach ($linha as $x => $coluna) {
                if ($coluna !== '.') {
                    continue;
                }

                for ($i = $y - 1; $i >= 0; $i--) {
                    $coluna2 = $this->mapa[$i][$x];

                    if ($coluna2 === '#') {
                        break;
                    }

                    if ($coluna2 === 'O') {
                        $this->mapa[$y][$x] = 'O';
                        $this->mapa[$i][$x] = '.';

//                        echo sprintf('trocou (%d, %d) por (%d, %d)', $x, $y, $x, $i) . PHP_EOL;

                        break;
                    }
                }
            }
        }

//        $this->imprimirTempoGasto('encontrar reflexos');

//        $this->imprimir2D($this->mapa);

        return $this->peso();
    }

    public function peso(): int
    {
        $peso = 0;
        foreach ($this->mapa as $y => $linha) {
            $peso += substr_count(implode('', $linha), 'O') * (count($this->mapa) - $y);
        }

//        $this->imprimirTempoGasto('pesar');

        return $peso;
    }
}