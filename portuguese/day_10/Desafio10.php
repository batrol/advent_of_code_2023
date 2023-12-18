<?php

class Desafio10
{
    private const CAMINHOS = [
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
    private array $mapaOriginal;

    /**
     * @var string[][][]
     */
    private array $mapas;

    /**
     * @var int[][]
     */
    private array $mapaPercorridoOriginal;

    /**
     * @var int[][][]
     */
    private array $mapasPercorridos;
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

        $this->mapaOriginal = [];
        $this->mapaPercorridoOriginal = [];
        $this->startX = -1;
        $this->startY = -1;
        foreach ($linhas as $y => $linha) {
            $colunas = str_split($linha);

            foreach ($colunas as $x => $coluna) {
                $this->mapaOriginal[$x][$y] = $coluna;
                $this->mapaPercorridoOriginal[$x][$y] = null;

                if ($coluna === 'S') {
                    $this->startX = $x;
                    $this->startY = $y;

                    $this->mapaPercorridoOriginal[$x][$y] = 0;
                }
            }
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function percorrerMapa(): int
    {
        $this->prepararMapas();

        $caminhos = [];

        foreach ($this->mapas as $i => $mapa) {
//            echo PHP_EOL;
            $this->x = $this->startX;
            $this->y = $this->startY;

            $caminho = 0;

            while (true) {
                $peca = $this->obterPeca($i, $this->x, $this->y);
//                print_r($peca);

                $x1 = $this->x + self::CAMINHOS[$peca][0][0];
                $y1 = $this->y + self::CAMINHOS[$peca][0][1];
                $x2 = $this->x + self::CAMINHOS[$peca][1][0];
                $y2 = $this->y + self::CAMINHOS[$peca][1][1];

                if (isset($this->mapasPercorridos[$i][$x1][$y1]) && isset($this->mapasPercorridos[$i][$x2][$y2])) {
                    break;
                }

                if (!isset($this->mapasPercorridos[$i][$x1][$y1])) {
                    $this->x = $x1;
                    $this->y = $y1;
                } elseif (!isset($this->mapasPercorridos[$i][$x2][$y2])) {
                    $this->x = $x2;
                    $this->y = $y2;
                } else {
                    $caminho = -1;
                    break;
                }

                $caminho++;
                $this->mapasPercorridos[$i][$this->x][$this->y] = $caminho;
            }

            if (($x1 === $this->startX && $y1 === $this->startY) || ($x2 === $this->startX && $y2 === $this->startY)) {
                $caminhos[] = $caminho;
            } else {
                unset($this->mapas[$i]);
                unset($this->mapasPercorridos[$i]);
            }
//            echo PHP_EOL;
        }

        $this->imprimirTempoGasto('percorrer mapa');

        print_r($caminhos);

        return ceil(max($caminhos) / 2);
    }

    public function contarAreaInterna(): int
    {
        $areasInternas = [];
        foreach ($this->mapas as $i => $mapa) {
            $this->imprimirMapa($i);

            $areaInterna = 0;

            for ($y = 0; $y < count($this->mapaOriginal[0]); $y++) {
                for ($x = 0; $x < count($this->mapaOriginal); $x++) {
                    if (in_array($this->mapas[$i][$x][$y], ['7', 'F', 'J', 'L', '-', '|', '#'])) {
                        continue;
                    }

                    $areaInterna++;
                }
            }
//            for ($x = 1; $x < count($this->mapaOriginal) - 1; $x++) {
//                for ($y = 1; $y < count($this->mapaOriginal[$x]) - 1; $y++) {
//                    if (isset($this->mapasPercorridos[$i][$x][$y])) {
//                        continue;
//                    }
//
//                    $aberturasAEsquerda = $this->contarAberturasAEsquerda($i, $x, $y);
//                    $aberturasAcima = $this->contarAberturasAcima($i, $x, $y);
//
//                    if ((($aberturasAEsquerda % 2) === 1) && (($aberturasAcima % 2) === 1)) {
////                        echo implode(' - ', [$i, $x, $y, $aberturasAEsquerda, $aberturasAcima]) . PHP_EOL;
//                        $areaInterna++;
////                    echo $x . ' ' . $y . PHP_EOL;
//                    }
//                }
//            }

            $this->imprimirTempoGasto('percorrer area interna');

            $areasInternas[] = $areaInterna;
        }

        print_r($areasInternas);

        return $areasInternas[0];
    }

    private function prepararMapas(): void
    {
        $pecas = [];

        $peca = $this->obterPeca(null, $this->startX, $this->startY - 1);
        $peca2 = $this->obterPeca(null, $this->startX - 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = 'J';
        }
        $peca2 = $this->obterPeca(null, $this->startX + 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = 'L';
        }
        $peca2 = $this->obterPeca(null, $this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = '|';
        }

        $peca = $this->obterPeca(null, $this->startX - 1, $this->startY);
        $peca2 = $this->obterPeca(null, $this->startX + 1, $this->startY);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = '-';
        }
        $peca2 = $this->obterPeca(null, $this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = '7';
        }

        $peca = $this->obterPeca(null, $this->startX + 1, $this->startY);
        $peca2 = $this->obterPeca(null, $this->startX, $this->startY + 1);
        if ($peca !== null && $peca2 !== null) {
            $pecas[] = 'F';
        }

        $this->mapas = [];
        $this->mapasPercorridos = [];

        foreach ($pecas as $peca) {
            $this->mapaOriginal[$this->startX][$this->startY] = $peca;

            $this->mapas[] = $this->mapaOriginal;
            $this->mapasPercorridos[] = $this->mapaPercorridoOriginal;
        }
    }

    private function obterPeca(?int $indice, int $x, int $y): ?string
    {
        if ($indice !== null) {
            $peca = $this->mapas[$indice][$x][$y] ?? null;
        } else {
            $peca = $this->mapaOriginal[$x][$y] ?? null;
        }

        return $peca === '.' ? null : $peca;
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    private function contarAberturasAEsquerda(int $indice, int $x, int $y): int
    {
//        echo "($x $y)" . PHP_EOL;
        $sequencia = '';
        for ($i = 0; $i < $x; $i++) {
            if (!isset($this->mapasPercorridos[$indice][$i][$y])) {
                continue;
            }

            $peca = $this->mapas[$indice][$i][$y];
            if ($peca === '-') {
                continue;
            }

            $sequencia .= $peca;
        }

        $sequencia = str_replace('FJ', '|', $sequencia);
        $sequencia = str_replace('L7', '|', $sequencia);

//        echo $sequencia . PHP_EOL;

        return strlen($sequencia);
    }

    private function contarAberturasAcima(int $indice, int $x, int $y): int
    {
//        echo "($x $y)" . PHP_EOL;
        $sequencia = '';
        for ($i = 0; $i < $y; $i++) {
            if (!isset($this->mapasPercorridos[$indice][$x][$i])) {
                continue;
            }

            $peca = $this->mapas[$indice][$x][$i];
            if ($peca === '|') {
                continue;
            }

            $sequencia .= $peca;
        }

        $sequencia = str_replace('FJ', '-', $sequencia);
        $sequencia = str_replace('L7', '-', $sequencia);

//        echo $sequencia . PHP_EOL;

        return strlen($sequencia);
    }

    private function imprimirMapa(int $i): void
    {
        echo '  - ';
        for ($x = 0; $x < count($this->mapaOriginal); $x++) {
            echo substr($x, -1) . ' ';
        }

        echo PHP_EOL;
        $substituiu = true;
        while ($substituiu) {
            $substituiu = false;
            for ($y = 0; $y < count($this->mapaOriginal[0]); $y++) {
                for ($x = 0; $x < count($this->mapaOriginal); $x++) {
                    if (isset($this->mapasPercorridos[$i][$x][$y])) {
                        if ($this->substituirCantos($i, $x, $y)) {
                            $y++;
                            $substituiu = true;

                            break;
                        }
                    }
                }
            }
        }

        for ($y = 0; $y < count($this->mapaOriginal[0]); $y++) {
            for ($x = 1; $x < count($this->mapaOriginal); $x++) {
                $this->mapas[$i][$x - 1][$y] = '#';
                $this->mapasPercorridos[$i][$x - 1][$y] = 1;

                if (in_array($this->mapas[$i][$x][$y], ['7', 'F', 'J', 'L', '-', '|'])) {
                    break;
                }
            }
            for ($x = count($this->mapaOriginal) - 2; $x >= 0; $x--) {
                $this->mapas[$i][$x + 1][$y] = '#';
                $this->mapasPercorridos[$i][$x + 1][$y] = 1;

                if (in_array($this->mapas[$i][$x][$y], ['7', 'F', 'J', 'L', '-', '|'])) {
                    break;
                }
            }
        }

        for ($y = 0; $y < count($this->mapaOriginal[0]); $y++) {
            echo $y . ' - ';
            for ($x = 0; $x < count($this->mapaOriginal); $x++) {
                echo (isset($this->mapasPercorridos[$i][$x][$y]) ? $this->mapas[$i][$x][$y] : ' ') . ' ';
//                echo ($this->mapasPercorridos[$i][$x][$y] ?? ' ') . ' ';
//                echo (isset($this->mapasPercorridos[$i][$x][$y]) ? '#' : ' ') . ' ';

//                if (isset($this->mapasPercorridos[$i][$x][$y])) {
//                    $this->mapasPercorridos[$i][$x][$y] = 'X';
//                }
            }
            echo PHP_EOL;
        }

        echo '-----' . PHP_EOL;
    }

    private function substituirCantos(int $i, int $x, int $y): bool
    {
        $peca = $this->obterPeca($i, $x, $y);
        $peca2 = $this->obterPeca($i, $x + 1, $y);
        $peca3 = $this->obterPeca($i, $x, $y + 1);
        $peca4 = $this->obterPeca($i, $x + 1, $y + 1);

        $aux = $peca . $peca2 . $peca3 . $peca4;

        if ($this->substituirString($i, $x, $y, $aux, 'FJL-', ' | L')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'L7-J', '| J ')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, '||LJ', 'LJ  ')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, '|FLJ', 'L-  ')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'FJL7', ' | |')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F-L-', ' F L')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F-L7', ' F |')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F7|L', '  F-')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F7JL', '  --')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, '7|LJ', '-J  ')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F7||', '  F7')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, 'F7J|', '  -7')) {
            return true;
        }
        if ($this->substituirString($i, $x, $y, $aux, '7FLJ', '--  ')) {
            return true;
        }

        return false;
    }

    private function substituirString(
        int $i,
        int $x,
        int $y,
        string $aux,
        string $stringOrigem,
        string $stringDestino
    ): bool {
        if ($aux === $stringOrigem) {
            $incrementos = [
                [0, 0],
                [1, 0],
                [0, 1],
                [1, 1],
            ];

            $novosValores = str_split($stringDestino);
            foreach ($incrementos as $k => $incremento) {
                if ($novosValores[$k] === ' ') {
                    $this->mapas[$i][$x + $incremento[0]][$y + $incremento[1]] = '#';
                    $this->mapasPercorridos[$i][$x + $incremento[0]][$y + $incremento[1]] = 1;

                    continue;
                }

                $this->mapas[$i][$x + $incremento[0]][$y + $incremento[1]] = $novosValores[$k];
                $this->mapasPercorridos[$i][$x + $incremento[0]][$y + $incremento[1]] = 1;
            }

            return true;
        }

        return false;
    }
}