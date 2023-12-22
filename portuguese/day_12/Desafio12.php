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

        foreach ($this->fontes as $i => $fonte3) {
            $fontes2 = [$fonte3];
            $tamanhos = $this->tamanhos[$i];

            $modificado = true;
            while ($modificado) {
                $modificado = false;

                foreach ($fontes2 as $j => $fonte) {
                    if (!str_contains($fonte, '?')) {
                        continue;
                    }

                    $pos = strpos($fonte, '?');
                    if ($pos === 0) {
                        $fonte1 = '#' . substr($fonte, 1);
                        $fonte2 = '.' . substr($fonte, 1);
                    } elseif ($pos === strlen($fonte) - 1) {
                        $fonte1 = substr($fonte, 0, $pos) . '#';
                        $fonte2 = substr($fonte, 0, $pos) . '.';
                    } else {
                        $fonte1 = substr($fonte, 0, $pos) . '#' . substr($fonte, $pos + 1);
                        $fonte2 = substr($fonte, 0, $pos) . '.' . substr($fonte, $pos + 1);
                    }

                    unset($fontes2[$j]);
                    $fontes2[] = $fonte1;
                    $fontes2[] = $fonte2;

                    $modificado = true;
                }
            }

            $count += $this->removerInvalidos($fontes2, $tamanhos);
        }

        $this->imprimirTempoGasto('substituir desconhecidos');

        return $count;
    }

    public function removerInvalidos($fontes3, $tamanhos): int
    {
        foreach ($fontes3 as $i => $fontes2) {
            $fontes = array_values(array_filter(explode('.', $fontes2)));

            if (count($fontes) !== count($tamanhos)) {
                unset($fontes3[$i]);

                continue;
            }

            foreach ($fontes as $k => $fonte) {
                if (strlen($fonte) !== $tamanhos[$k]) {
                    unset($fontes3[$i]);

                    break;
                }
            }
        }

        $this->imprimirTempoGasto('remover invalidos');

        return count($fontes3);
    }
}