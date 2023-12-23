<?php

class Desafio12
{
    private float $inicio;

    /**
     * @var string[]
     */
    private array $fontes;

    /**
     * @var int[][]
     */
    private array $tamanhos;

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
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->fontes = [];
        $this->tamanhos = [];
        foreach ($linhas as $linha) {
            list($fontes, $tamanhos) = explode(' ', $linha);
            $this->fontes[] = $fontes;

            $tamanhos = explode(',', $tamanhos);
            array_walk($tamanhos, function (&$tamanho) {
                $tamanho = (int)$tamanho;
            });
            $this->tamanhos[] = $tamanhos;
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function lerArquivo2(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->fontes = [];
        $this->tamanhos = [];
        foreach ($linhas as $linha) {
            list($fontes, $tamanhos) = explode(' ', $linha);
            $this->fontes[] =
                $fontes . '?' .
                $fontes . '?' .
                $fontes . '?' .
                $fontes . '?' .
                $fontes;

            $tamanhos = explode(',', $tamanhos);
            array_walk($tamanhos, function (&$tamanho) {
                $tamanho = (int)$tamanho;
            });
            $this->tamanhos[] = array_merge($tamanhos, $tamanhos, $tamanhos, $tamanhos, $tamanhos);
        }

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function substituirDesconhecidos(): int
    {
        $count = 0;

        foreach ($this->fontes as $i => $fonte) {
            $tamanhos = $this->tamanhos[$i];
            $count += $this->substituir(
                $fonte,
                $tamanhos,
                0,
                array_sum($tamanhos),
                substr_count($fonte, '?'),
                substr_count($fonte, '#')
            );

            $this->imprimirTempoGasto('substituir desconhecidos ' . $i . ' / ' . $count);
        }

        $this->imprimirTempoGasto('substituir desconhecidos');

        return $count;
    }

    private function ehValido(string $fontes2, array $tamanhos): int
    {
        $fontes = array_values(array_filter(explode('.', $fontes2)));

        if (count($fontes) !== count($tamanhos)) {
            return 0;
        }

        foreach ($fontes as $k => $fonte) {
            if (strlen($fonte) !== $tamanhos[$k]) {
                return 0;
            }
        }

        return 1;
    }

    private function ehValido2(string $fontes2, array $tamanhos): bool
    {
        $fontes3 = explode('?', $fontes2)[0];
        $pop = str_ends_with($fontes3, '#');

        $fontes = array_values(array_filter(explode('.', $fontes3)));

        if ($pop) {
            array_pop($fontes);
        }

        foreach ($fontes as $k => $fonte) {
            if (strlen($fonte) !== $tamanhos[$k]) {
                return false;
            }
        }

        return true;
    }

    private function substituir(
        string $fonte,
        array $tamanhos,
        int $count,
        int $qtFontesEsperadas,
        int $qtInterrogacoes,
        int $qtFontes,
    ): int {
        if ($qtInterrogacoes === 0) {
            return $count + $this->ehValido($fonte, $tamanhos);
        }

        if ($qtFontes > $qtFontesEsperadas) {
            return $count;
        }

        if ($qtInterrogacoes + $qtFontes < $qtFontesEsperadas) {
            return $count;
        }

        if (!$this->ehValido2($fonte, $tamanhos)) {
            return $count;
        }

        $fonte1 = preg_replace('/\?/', '#', $fonte, 1);
        $fonte2 = preg_replace('/\?/', '.', $fonte, 1);

        return $count
            + $this->substituir($fonte2, $tamanhos, $count, $qtFontesEsperadas, $qtInterrogacoes - 1, $qtFontes)
            + $this->substituir($fonte1, $tamanhos, $count, $qtFontesEsperadas, $qtInterrogacoes - 1, $qtFontes + 1);
    }
}