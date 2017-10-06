<?php

/* fungsi utama */

function connect() {
    $dbname = "hierarchical_clustering";
    $host = "localhost";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (mysqli_connect_errno()) {
        echo "koneksi server gagal";
        exit();
    }
    return $conn;
}

function disconnect() {
    mysqli_close(connect());
}

/**
 * Digunakan untuk insert, update, dan delete saja
 * @param type $sql
 */
function execute($sql) {
    mysqli_query(connect(), $sql);
    disconnect();
}

/* fungsi utama */

/* kumpulan query */

function select_penampungan() {
    $result = "SELECT besar_penampungan FROM PENAMPUNGAN";
    return $result;
}

/* kumpulan query */

/* kumpulan logic */

function lihat_hasil($array) {
    echo '<table border = 1>';
    for ($a = 0; $a < count($array); $a++) {
        echo '<tr>';
        for ($b = 0; $b < count($array[$a]); $b++) {
            echo '<td>';
            echo $array[$a][$b];
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

function perbandingan_awal($ph1, $ph2) {
    $result = 0;
    if ($ph2 > $ph1) {
        $result = $ph1 / $ph2;
    } else {
        $result = $ph2 / $ph1;
    }
    return round($result, 3);
}

function nilai_minimum($inisial_minus) {
    for ($a = 0; $a < count($inisial_minus); $a++) {
        $min[$a] = min($inisial_minus[$a]);
    }
    return min($min);
}

function nilai_key_minimum($nilai_minimum, $inisial_minus) {
    for ($x = 0; $x < count($inisial_minus); $x++) {
        $y = array_search($nilai_minimum, $inisial_minus[$x]);
        //jika ketemu
        if ($y !== false) {
            $result['x'] = $x;
            $result['y'] = $y;
            break;
        } else {
            continue;
        }
    }
    return $result;
}

function penggabungan($merge_a, $merge_b) {
    for ($a = 0; $a < count($merge_a); $a++) {
        if ($merge_a[$a] > $merge_b[$a]) {
            $new_merge[$a] = $merge_a[$a];
        } else {
            $new_merge[$a] = $merge_b[$a];
        }
    }
    return $new_merge;
}

//transpose array multidimensional
function transpose($inisial_hitung) {
    $out = array();
    foreach ($inisial_hitung as $key => $subarr) {
        foreach ($subarr as $subkey => $subvalue) {
            $out[$subkey][$key] = $subvalue;
        }
    }
    return $out;
}

function iterasi_array($inisial_hitung, $x, $y, $new_merge) {
    $key_merge = 0;
    $unset_key = 0;
    if ($x < $y) {
        $key_merge = $x;
        $unset_key = $y;
    } else {
        $key_merge = $y;
        $unset_key = $x;
    }
    $inisial_hitung[$key_merge] = $new_merge;
    $inisial_hitung = transpose($inisial_hitung);
    $inisial_hitung[$key_merge] = $new_merge;
    unset($inisial_hitung[$unset_key]);
    $inisial_hitung = array_values($inisial_hitung);
    $inisial_hitung = transpose($inisial_hitung);
    unset($inisial_hitung[$unset_key]);
    $inisial_hitung = array_values($inisial_hitung);
    return $inisial_hitung;
}

function iterasi($inisial_hitung, $inisial_minus) {
    //mendapatkan min value (function-ny lihat di koneksi.php)
    $nilai_minimum = nilai_minimum($inisial_minus);
    //mendapatkan key array dari min value yg sudah didapatkan(function-ny lihat di koneksi.php)
    $nilai_key_minimum = nilai_key_minimum($nilai_minimum, $inisial_minus);
    echo 'nilai yang mendekati 1 ada di ';
    print_r($nilai_key_minimum);
    echo 'yaitu ' . $inisial_hitung[$nilai_key_minimum['x']][$nilai_key_minimum['y']];
    echo '<br>';
    $merge_a = $inisial_hitung[$nilai_key_minimum['x']];
    $merge_b = $inisial_hitung[$nilai_key_minimum['y']];
    $new_merge = penggabungan($merge_a, $merge_b);
//        echo '<br>';
    $x = $nilai_key_minimum['x'];
    $y = $nilai_key_minimum['y'];
    //iterasi_array ada di koneksi.php
    $inisial_hitung = iterasi_array($inisial_hitung, $x, $y, $new_merge);
    return $inisial_hitung;
}

function inisial_hitung($inisial) {
    $inisial_hitung = array();
    for ($a = 0; $a < count($inisial); $a++) {
        for ($b = 0; $b < count($inisial); $b++) {
            $inisial_hitung[$a][$b] = perbandingan_awal($inisial[$a], $inisial[$b]);
        }
    }
    return $inisial_hitung;
}

function inisial_minus($inisial_hitung) {
    $inisial_minus = array();
    for ($a = 0; $a < count($inisial_hitung); $a++) {
        for ($b = 0; $b < count($inisial_hitung[$a]); $b++) {
            $inisial_minus[$a][$b] = 1 - $inisial_hitung[$a][$b];
            if ($inisial_minus[$a][$b] == 0) {
                $inisial_minus[$a][$b] = 1;
            }
        }
    }
    return $inisial_minus;
}
