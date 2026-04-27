<?php

use Konscia\CifraClub\AcordesFactory;
use Konscia\CifraClub\ArtistaNaoEncontradoException;
use Konscia\CifraClub\ArtistaFactory;
use Konscia\CifraClub\BuscadorDeMusicasFaceis;
use Konscia\CifraClub\Cache;
use Konscia\CifraClub\CifraClubProxyImpl;
use Konscia\CifraClub\LocalizadorDeAcordes;
use Konscia\CifraClub\LocalizadorDeArtistas;
use Konscia\CifraClub\Slug;

include_once __DIR__ . '/vendor/autoload.php';

if ($argc !== 4 || $argv[1] !== 'buscar-musicas-faceis') {
    echo "Uso:\n";
    echo "  php cifra-cli.php buscar-musicas-faceis [slug-artista] [numero-maximo-acordes]\n";
    exit(1);
}

$slugArtista = $argv[2];
$numeroMaximoAcordes = (int) $argv[3];

try {
    $proxy = new CifraClubProxyImpl();
    $cache = new Cache();

    $localizadorDeArtistas = new LocalizadorDeArtistas($proxy, new ArtistaFactory(), $cache);
    $localizadorDeAcordes = new LocalizadorDeAcordes($proxy, new AcordesFactory(), $cache);
    $buscador = new BuscadorDeMusicasFaceis($localizadorDeArtistas, $localizadorDeAcordes);

    $musicas = $buscador->buscaPorArtista(new Slug($slugArtista), $numeroMaximoAcordes);

    echo "Artista: {$slugArtista}\n";
    echo "Máximo de acordes: {$numeroMaximoAcordes}\n\n";

    if (empty($musicas)) {
        echo "Nenhuma música encontrada com esse critério.\n";
        exit(0);
    }

    foreach ($musicas as $item) {
        $musica = $item['musica'];
        $acordes = $item['acordes'];

        echo $musica->getNome() . " (" . $acordes->totalAcordes() . " acordes)\n";
        echo "  " . $acordes . "\n\n";
    }
} catch (ArtistaNaoEncontradoException $e) {
    echo $e->getMessage() . "\n";
    exit(1);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage() . "\n";
    exit(1);
} catch (Throwable $e) {
    echo "Erro inesperado: " . $e->getMessage() . "\n";
    exit(1);
}
