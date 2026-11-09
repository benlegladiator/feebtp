<?php
$dir = __DIR__;
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
$total = 0;
foreach ($files as $f) {
    $total += $f->getSize();
}
echo "Total: " . round($total / 1024 / 1024, 2) . " MB\n";
