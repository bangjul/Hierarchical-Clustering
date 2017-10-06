<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include 'koneksi.php';
        $inisial = array();
        $result = mysqli_query(connect(), select_penampungan());
        $count = 0;
        while ($row = mysqli_fetch_array($result)) {
            $inisial[$count] = $row['besar_penampungan'];
            $count ++;
        }
        disconnect();
        echo 'nilai-nilai yang muncul: ';
        for($a=0; $a<count($inisial); $a++){
            echo $inisial[$a];
            if($a < count($inisial)-1){
                echo ', ';
            }
        }
        echo '<br><br>';
        $inisial_hitung = inisial_hitung($inisial);
        echo 'Inisial Awal';
        lihat_hasil($inisial_hitung);
        $iterasi_min = count($inisial_hitung);
        $iterasi = 1;
        while (true) {
            $inisial_minus = inisial_minus($inisial_hitung);
            $inisial_hitung = iterasi($inisial_hitung, $inisial_minus);
            echo '<br>Iterasi '.$iterasi;
            lihat_hasil($inisial_hitung);
            if ($iterasi_min == 3) {
                break;
            }
            $iterasi_min--;
            $iterasi++;
        }
        ?>
        <br>
        Done...<br>
        Nilai Terakhir adalah : <?php echo $inisial_hitung[0][1]?>
    </body>
</html>
