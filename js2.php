<?php
// Definisi kriteria dan bobot
$kriteria = [
    'c1' => ['name' => 'Fasilitas Pendukung', 'bobot' => 0.3, 'type' => 'benefit'],
    'c2' => ['name' => 'Harga per m²', 'bobot' => 0.2, 'type' => 'cost'],
    'c3' => ['name' => 'Tahun Konstruksi', 'bobot' => 0.2, 'type' => 'benefit'],
    'c4' => ['name' => 'Jarak dari Tempat Kerja', 'bobot' => 0.2, 'type' => 'cost'],
    'c5' => ['name' => 'Sistem Keamanan', 'bobot' => 0.1, 'type' => 'benefit']
];

// Data alternatif dan skor
$alternatif = [
    'Apartemen 1' => ['c1' => 2, 'c2' => 2, 'c3' => 2, 'c4' => 1, 'c5' => 3],
    'Apartemen 2' => ['c1' => 4, 'c2' => 1, 'c3' => 3, 'c4' => 2, 'c5' => 3],
    'Apartemen 3' => ['c1' => 3, 'c2' => 2, 'c3' => 2, 'c4' => 3, 'c5' => 4]
];

// Normalisasi data
$normalisasi = [];
foreach ($kriteria as $key => $info) {
    $values = array_column($alternatif, $key);
    if ($info['type'] == 'benefit') {
        $max = max($values);
        foreach ($alternatif as $alt => $data) {
            $normalisasi[$alt][$key] = $data[$key] / $max;
        }
    } else {
        $min = min($values);
        foreach ($alternatif as $alt => $data) {
            $normalisasi[$alt][$key] = $min / $data[$key];
        }
    }
}

// Hitung skor WSM & WPM
$wsm = [];
$wpm = [];
foreach ($alternatif as $alt => $data) {
    $wsm[$alt] = 0;
    $wpm[$alt] = 1;
    foreach ($kriteria as $key => $info) {
        $wsm[$alt] += $normalisasi[$alt][$key] * $info['bobot'];
        $wpm[$alt] *= pow($normalisasi[$alt][$key], $info['bobot']);
    }
}

// Urutkan hasil
arsort($wsm);
arsort($wpm);

echo "<h3>Hasil Perhitungan WSM:</h3><ul>";
foreach ($wsm as $alt => $score) {
    echo "<li>$alt : ".number_format($score, 4)."</li>";
}
echo "</ul>";

echo "<h3>Hasil Perhitungan WPM:</h3><ul>";
foreach ($wpm as $alt => $score) {
    echo "<li>$alt : ".number_format($score, 6)."</li>";
}
echo "</ul>";
?>
