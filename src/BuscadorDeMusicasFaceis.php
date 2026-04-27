<?php

namespace Konscia\CifraClub;

class BuscadorDeMusicasFaceis
{
    /**
     * @var LocalizadorDeArtistas
     */
    private $localizadorDeArtistas;

    /**
     * @var LocalizadorDeAcordes
     */
    private $localizadorDeAcordes;

    public function __construct(LocalizadorDeArtistas $localizadorDeArtistas, LocalizadorDeAcordes $localizadorDeAcordes)
    {
        $this->localizadorDeArtistas = $localizadorDeArtistas;
        $this->localizadorDeAcordes = $localizadorDeAcordes;
    }

    /**
     * @return array<int, array{musica: Musica, acordes: Acordes}>
     */
    public function buscaPorArtista(Slug $slugArtista, int $numeroMaximoAcordes) : array
    {
        if ($numeroMaximoAcordes < 1) {
            throw new \InvalidArgumentException('O número máximo de acordes deve ser maior que zero.');
        }

        $artista = $this->localizadorDeArtistas->encontraPeloSlug($slugArtista);
        $musicasFiltradas = [];

        foreach ($artista->getMusicas() as $musica) {
            $acordes = $this->localizadorDeAcordes->pegaAcordesDeUmaMusica($musica);
            $totalAcordes = $acordes->totalAcordes();

            if ($totalAcordes > 0 && $totalAcordes <= $numeroMaximoAcordes) {
                $musicasFiltradas[] = [
                    'musica' => $musica,
                    'acordes' => $acordes,
                ];
            }
        }

        usort($musicasFiltradas, function (array $a, array $b) {
            $aTotal = $a['acordes']->totalAcordes();
            $bTotal = $b['acordes']->totalAcordes();

            if ($aTotal === $bTotal) {
                return strcmp($a['musica']->getNome(), $b['musica']->getNome());
            }

            return ($aTotal < $bTotal) ? -1 : 1;
        });

        return $musicasFiltradas;
    }
}
