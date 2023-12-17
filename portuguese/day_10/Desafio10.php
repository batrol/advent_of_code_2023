<?php

class Desafio10
{
    private const MAPA = [
        '|' => [[0, -1], [0, 1]],
        '-' => [[-1, 0], [1, 0]],
        'L' => [[0, -1], [1, 0]],
        'J' => [[0, -1], [-1, 0]],
        '7' => [[-1, 0], [0, 1]],
        'F' => [[1, 0], [0, 1]],
    ];
    private float $inicio;

    /**
     * @var string[][]
     */
    private array $mapa;

    /**
     * @var int[][]
     */
    private array $mapaPercorrido;
    private int $startX;
    private int $startY;
    private int $x;
    private int $y;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $linhas = explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo));

        $this->mapa = [];
        $this->mapaPercorrido = [];
        $this->startX = -1;
        $this->startY = -1;
        foreach ($linhas as $y => $linha) {
            $colunas = str_split($linha);

            foreach ($colunas as $x => $coluna) {
                $this->mapa[$x][$y] = $coluna;
                $this->mapaPercorrido[$x][$y] = null;

                if ($coluna === 'S') {
                    $this->startX = $x;
                    $this->startY = $y;

                    $this->mapaPercorrido[$x][$y] = 0;
                }
            }
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function percorrerMapa(): int
    {
        $this->substituirPecaInicio();

        $this->x = $this->startX;
        $this->y = $this->startY;

        $caminho = 0;

        while (true) {
            $peca = $this->obterPeca($this->x, $this->y);
            $x1 = $this->x + self::MAPA[$peca][0][0];
            $y1 = $this->y + self::MAPA[$peca][0][1];
            $x2 = $this->x + self::MAPA[$peca][1][0];
            $y2 = $this->y + self::MAPA[$peca][1][1];

            if (isset($this->mapaPercorrido[$x1][$y1]) && isset($this->mapaPercorrido[$x2][$y2])) {
                break;
            }


            if (!isset($this->mapaPercorrido[$x1][$y1])) {
                $this->x = $x1;
                $this->y = $y1;
            } else {
                $this->x = $x2;
                $this->y = $y2;
            }

            $caminho++;
            $this->mapaPercorrido[$this->x][$this->y] = $caminho;
        }

        $this->imprimirTempoGasto('percorrer mapa');

        return ceil($caminho / 2);
    }

    private function substituirPecaInicio(): void
    {
        $peca = $this->obterPeca($this->startX, $this->startY - 1);
        $peca2 = $this->obterPeca($this->startX - 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = 'J';
        }
        $peca2 = $this->obterPeca($this->startX + 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = 'L';
        }
        $peca2 = $this->obterPeca($this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = '|';
        }

        $peca = $this->obterPeca($this->startX - 1, $this->startY);
        $peca2 = $this->obterPeca($this->startX + 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = '-';
        }
        $peca2 = $this->obterPeca($this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = '7';
        }

        $peca = $this->obterPeca($this->startX + 1, $this->startY);
        $peca2 = $this->obterPeca($this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $this->mapa[$this->startX][$this->startY] = 'F';
        }
    }

    private function obterPeca(int $x, int $y): ?string
    {
        return $this->mapa[$x][$y] ?? null;
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }
}