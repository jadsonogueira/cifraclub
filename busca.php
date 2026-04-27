<?php

use Konscia\CifraClub\AcordesFactory;
use Konscia\CifraClub\ArtistaFactory;
use Konscia\CifraClub\BuscadorDeMusicasFaceis;
use Konscia\CifraClub\Cache;
use Konscia\CifraClub\CifraClubProxyImpl;
use Konscia\CifraClub\LocalizadorDeAcordes;
use Konscia\CifraClub\LocalizadorDeArtistas;
use Konscia\CifraClub\Slug;

include_once __DIR__ . '/vendor/autoload.php';

$slugArtista = isset($_GET['artista']) ? trim((string) $_GET['artista']) : '';
$numeroMaximoAcordes = isset($_GET['max_acordes']) ? (int) $_GET['max_acordes'] : 5;
$erro = null;
$resultados = [];

if ($slugArtista !== '') {
    try {
        $proxy = new CifraClubProxyImpl();
        $cache = new Cache();

        $localizadorDeArtistas = new LocalizadorDeArtistas($proxy, new ArtistaFactory(), $cache);
        $localizadorDeAcordes = new LocalizadorDeAcordes($proxy, new AcordesFactory(), $cache);
        $buscador = new BuscadorDeMusicasFaceis($localizadorDeArtistas, $localizadorDeAcordes);

        $resultados = $buscador->buscaPorArtista(new Slug($slugArtista), $numeroMaximoAcordes);
    } catch (Throwable $e) {
        $erro = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Busca de Músicas Fáceis - Cifra Club</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        form { display: flex; gap: .75rem; margin-bottom: 1rem; flex-wrap: wrap; }
        input, button { padding: .5rem; font-size: 1rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: .5rem; text-align: left; }
        .erro { color: #a00; margin: .5rem 0 1rem; }
        .vazio { color: #666; }
    </style>
</head>
<body>
    <h1>Busca de músicas fáceis</h1>

    <form method="get">
        <label>
            Slug do artista
            <input type="text" name="artista" value="<?php echo htmlspecialchars($slugArtista, ENT_QUOTES, 'UTF-8'); ?>" placeholder="ex: legiao-urbana" required>
        </label>

        <label>
            Máximo de acordes
            <input type="number" name="max_acordes" min="1" value="<?php echo htmlspecialchars((string) $numeroMaximoAcordes, ENT_QUOTES, 'UTF-8'); ?>" required>
        </label>

        <button type="submit">Buscar</button>
    </form>

    <?php if ($erro !== null): ?>
        <p class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if ($slugArtista !== '' && $erro === null): ?>
        <?php if (count($resultados) === 0): ?>
            <p class="vazio">Nenhuma música encontrada para esse critério.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Música</th>
                        <th>Total de acordes</th>
                        <th>Acordes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['musica']->getNome(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) $item['acordes']->totalAcordes(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) $item['acordes'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
