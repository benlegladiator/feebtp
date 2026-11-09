<?php
/**
 * CATALOGUE VISUEL DES PHOTOS FEE BTP SARL
 * Permet au client de visualiser chaque photo avec son numéro officiel de 1 à N
 */
$baseDir = __DIR__ . '/images';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
$photos = [];

foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $name = $file->getFilename();
    $path = str_replace('\\', '/', $file->getPathname());
    $rel = str_replace(str_replace('\\', '/', __DIR__) . '/', '', $path);

    // Photos authentiques uniquement
    $isAuthentic = preg_match('/^(FAB-|IMG-2026|zetsa|keibog|guemechio|aghokeng)/i', $name);
    if (!$isAuthentic) continue;

    $info = @getimagesize($path);
    $w = $info ? $info[0] : 0;
    $h = $info ? $info[1] : 0;
    $sizeKb = round(filesize($path) / 1024);

    $photos[] = [
        'name' => $name,
        'rel' => $rel,
        'w' => $w,
        'h' => $h,
        'dim' => "{$w} × {$h} px",
        'size_kb' => $sizeKb,
        'orientation' => ($w >= $h) ? 'Paysage (Horizontal)' : 'Portrait (Vertical)',
        'badge_class' => ($w >= $h) ? 'badge-primary' : 'badge-info'
    ];
}

// Tri naturel
usort($photos, function($a, $b) {
    return strnatcasecmp($a['name'], $b['name']);
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Numéroté des Photos - FEE BTP SARL</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f1f5f9; color: #1e293b; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .hero-banner { background: linear-gradient(135deg, #1e2229 0%, #111418 100%); color: #fff; padding: 40px 0; border-bottom: 4px solid #f97316; }
        .photo-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid #e2e8f0; height: 100%; display: flex; flex-direction: column; }
        .photo-card:hover { transform: translateY(-4px); box-shadow: 0 12px 25px rgba(0,0,0,0.12); }
        .photo-img-wrap { height: 210px; background: #0f172a; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .photo-img-wrap img { width: 100%; height: 100%; object-fit: contain; background: #000; }
        .number-badge { position: absolute; top: 10px; left: 10px; background: #f97316; color: #fff; font-size: 16px; font-weight: 900; padding: 5px 12px; border-radius: 30px; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.5); z-index: 2; }
        .card-body-custom { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .filename-text { font-family: monospace; font-size: 13px; font-weight: bold; color: #1e2229; word-break: break-all; margin-bottom: 6px; }
        .meta-pill { font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 4px; background: #e2e8f0; color: #475569; display: inline-block; margin-right: 4px; margin-bottom: 4px; }
        .copy-btn { font-size: 12px; font-weight: 600; width: 100%; margin-top: 10px; }
        .filter-sticky { position: sticky; top: 0; z-index: 100; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <header class="hero-banner text-center">
        <div class="container">
            <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold mb-2">FEE BTP SARL • Dschang & Ouest</span>
            <h1 class="font-weight-bold">Catalogue Officiel des Photos (Numérotées 1 à <?= count($photos) ?>)</h1>
            <p class="lead mb-0 text-white-50">Visualisez ci-dessous l'ensemble des photos réelles disponibles. Choisissez vos numéros pour chaque emplacement du site web.</p>
        </div>
    </header>

    <div class="filter-sticky">
        <div class="container d-flex align-items-center justify-content-between flex-wrap">
            <div class="font-weight-bold text-dark">
                <i class="fas fa-camera text-primary mr-1"></i> Total : <strong><?= count($photos) ?> photos numérotées</strong>
            </div>
            <div>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Rechercher par numéro ou nom..." style="width: 280px; display: inline-block;">
                <a href="index.php" class="btn btn-sm btn-outline-primary ml-2"><i class="fas fa-arrow-left mr-1"></i> Retour au site</a>
            </div>
        </div>
    </div>

    <main class="container py-4">
        <div class="row" id="photoGrid">
            <?php foreach ($photos as $idx => $p): 
                $num = $idx + 1;
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 photo-item" data-num="<?= $num ?>" data-name="<?= strtolower($p['name']) ?>">
                <div class="photo-card">
                    <div class="photo-img-wrap">
                        <span class="number-badge">N° <?= $num ?></span>
                        <a href="<?= htmlspecialchars($p['rel']) ?>" target="_blank" title="Cliquez pour agrandir">
                            <img src="<?= htmlspecialchars($p['rel']) ?>" alt="Photo N° <?= $num ?>" loading="lazy">
                        </a>
                    </div>
                    <div class="card-body-custom">
                        <div>
                            <div class="filename-text"><?= htmlspecialchars($p['name']) ?></div>
                            <div>
                                <span class="meta-pill"><i class="fas fa-expand-arrows-alt mr-1"></i><?= $p['dim'] ?></span>
                                <span class="meta-pill"><i class="fas fa-weight-hanging mr-1"></i><?= $p['size_kb'] ?> Ko</span>
                                <span class="badge <?= $p['badge_class'] ?>"><?= $p['orientation'] ?></span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" onclick="copyNum(<?= $num ?>, '<?= htmlspecialchars($p['name']) ?>', this)">
                            <i class="far fa-copy mr-1"></i> Choisir N° <?= $num ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 small text-white-50">FEE BTP SARL — Outil interne de sélection et cartographie photographique.</p>
        </div>
    </footer>

    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            var val = e.target.value.toLowerCase().trim();
            var items = document.querySelectorAll('.photo-item');
            items.forEach(function(item) {
                var num = item.getAttribute('data-num');
                var name = item.getAttribute('data-name');
                if (!val || num === val || ('n° ' + num) === val || name.indexOf(val) !== -1) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        function copyNum(n, name, btn) {
            navigator.clipboard.writeText("Photo N° " + n + " (" + name + ")");
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-success mr-1"></i> N° ' + n + ' Copié !';
            setTimeout(function() { btn.innerHTML = orig; }, 1800);
        }
    </script>
</body>
</html>
