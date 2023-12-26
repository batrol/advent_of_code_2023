<?php

class Desafio16
{
    public const DIRECAO_DIREITA = 'D';
    private const DIRECAO_ESQUERDA = 'E';
    private const DIRECAO_CIMA = 'C';
    private const DIRECAO_BAIXO = 'B';
    private const INCREMENTOS_DIRECAO = [
        self::DIRECAO_DIREITA => [1, 0],
        self::DIRECAO_ESQUERDA => [-1, 0],
        self::DIRECAO_CIMA => [0, -1],
        self::DIRECAO_BAIXO => [0, 1],
    ];
    private const PARTE_VAZIA = '.';
    private const PARTE_ESPELHO_BAIXO = '\\';
    private const PARTE_ESPELHO_CIMA = '/';
    private const PARTE_BARRA_VERTICAL = '|';
    private const PARTE_BARRA_HORIZONTAL = '-';

    private float $inicio;

    /**
     * @var string[][]
     */
    private array $mapa;

    /**
     * @var string[][]
     */
    private array $mapaEnergizado;

    private array $passosExecutados = [];

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
     * @param string[][] $mapa
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

        $this->mapaEnergizado = [];

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function mapearPartesEnergizadas(int $x, int $y,string $direcao): int
    {
        $this->percorrerMapa($x, $y, $direcao);

        $this->imprimirTempoGasto('mapear partes energizadas');

        return $this->contarPartesEnergizadas();
    }

    public function mapearPartesEnergizadasComMaiorPotencial(): int{
        $primeiraLinha = 0;
        $primeiraColuna = 0;
        $ultimaLinha = count($this->mapa) - 1;
        $ultimaColuna = count($this->mapa[0]) - 1;

        $cantos = [];
        for ($i = 0; $i <= $ultimaColuna; $i++) {
            $cantos[] = [$i, $primeiraLinha, self::DIRECAO_BAIXO];
            $cantos[] = [$i, $ultimaLinha, self::DIRECAO_CIMA];
        }

        for ($i = 0; $i <= $ultimaLinha; $i++) {
            $cantos[] = [$primeiraColuna, $i, self::DIRECAO_DIREITA];
            $cantos[] = [$ultimaColuna, $i, self::DIRECAO_ESQUERDA];
        }

        $partesEnergizadas = [];
        foreach ($cantos as $canto) {
            $this->mapaEnergizado = [];
            $this->passosExecutados = [];

            list ($x, $y, $direcao) = $canto;
            $partesEnergizadas[] = $this->mapearPartesEnergizadas($x, $y, $direcao);
        }

        $this->imprimirTempoGasto('mapear partes energizadas com maior potencial');

        return max($partesEnergizadas);
    }

    private function percorrerMapa(int $x, int $y, string $direcao): void
    {
        $hash = implode(',', [$x, $y, $direcao]);
        if (isset($this->passosExecutados[$hash])) {
            return;
        }
        $this->passosExecutados[$hash] = 1;

        list ($incrementoX, $incrementoY) = self::INCREMENTOS_DIRECAO[$direcao];

        $novoY = $incrementoY + $y;
        $novoX = $incrementoX + $x;
        $proximaParte = $this->mapa[$novoY][$novoX] ?? null;
        if ($proximaParte === null) {
            return;
        }

//        echo sprintf('(%d,%d,%s) => (%d,%d) %s %s', $x, $y, $direcao, $novoX, $novoY, $proximaParte, PHP_EOL);
//        exit;

        if (!isset($this->mapaEnergizado[$novoY][$novoX])) {
            $this->mapaEnergizado[$novoY][$novoX] = 0;
        }
        $this->mapaEnergizado[$novoY][$novoX]++;

        switch ($proximaParte) {
            case self::PARTE_VAZIA:
                $this->percorrerMapa($novoX, $novoY, $direcao);
                break;
            case self::PARTE_ESPELHO_BAIXO:
                switch ($direcao) {
                    case self::DIRECAO_DIREITA:
                        $novaDirecao = self::DIRECAO_BAIXO;
                        break;
                    case self::DIRECAO_ESQUERDA:
                        $novaDirecao = self::DIRECAO_CIMA;
                        break;
                    case self::DIRECAO_CIMA:
                        $novaDirecao = self::DIRECAO_ESQUERDA;
                        break;
                    case self::DIRECAO_BAIXO:
                        $novaDirecao = self::DIRECAO_DIREITA;
                        break;
                }
                $this->percorrerMapa($novoX, $novoY, $novaDirecao);
                break;
            case self::PARTE_ESPELHO_CIMA:
                switch ($direcao) {
                    case self::DIRECAO_DIREITA:
                        $novaDirecao = self::DIRECAO_CIMA;
                        break;
                    case self::DIRECAO_ESQUERDA:
                        $novaDirecao = self::DIRECAO_BAIXO;
                        break;
                    case self::DIRECAO_CIMA:
                        $novaDirecao = self::DIRECAO_DIREITA;
                        break;
                    case self::DIRECAO_BAIXO:
                        $novaDirecao = self::DIRECAO_ESQUERDA;
                        break;
                }
                $this->percorrerMapa($novoX, $novoY, $novaDirecao);
                break;
            case self::PARTE_BARRA_VERTICAL:
                switch ($direcao) {
                    case self::DIRECAO_DIREITA:
                    case self::DIRECAO_ESQUERDA:
                        $this->percorrerMapa($novoX, $novoY, self::DIRECAO_CIMA);
                        $this->percorrerMapa($novoX, $novoY, self::DIRECAO_BAIXO);
                        break;
                    case self::DIRECAO_CIMA:
                    case self::DIRECAO_BAIXO:
                        $this->percorrerMapa($novoX, $novoY, $direcao);
                        break;
                }
                break;
            case self::PARTE_BARRA_HORIZONTAL:
                switch ($direcao) {
                    case self::DIRECAO_CIMA:
                    case self::DIRECAO_BAIXO:
                        $this->percorrerMapa($novoX, $novoY, self::DIRECAO_ESQUERDA);
                        $this->percorrerMapa($novoX, $novoY, self::DIRECAO_DIREITA);
                        break;
                    case self::DIRECAO_ESQUERDA:
                    case self::DIRECAO_DIREITA:
                        $this->percorrerMapa($novoX, $novoY, $direcao);
                        break;
                }
                break;
        }
    }

    public function contarPartesEnergizadas(): int
    {
        $partesEnergizadas = 0;
        foreach ($this->mapaEnergizado as $linha) {
            $partesEnergizadas += count($linha);
        }

        return $partesEnergizadas;
    }
}