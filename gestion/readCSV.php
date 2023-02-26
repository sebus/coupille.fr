<?php

$CSVfp = fopen("paul.csv", "r");

if ($CSVfp !== FALSE) {
    ?>
        <table class="striped" border="1">
            <thead>
                <tr>
                    <th>id_excel</th>
                    <th>base_initiale</th>
                    <th>fichier</th>
                    <th>num_toile</th>
                    <th>annee</th>
                    <th>dimensions</th>
                    <th>titre</th>
                    <th>collection</th>
                    <th>localisation</th>
                    <th>observations</th>

                </tr>
            </thead>
<?php
    while (! feof($CSVfp)) {
        
        $data = fgetcsv($CSVfp, 3000, ";");

        if (! empty($data)) {
            ?>
            <tr class="data">
                <td><?php echo $data[0]; ?></td>
                <td><?php echo $data[1]; ?></td>
                <?php
                    if($data[2] !=''){

                        $filename = '/home/cose2962/coupille_sources_hd/'.$data[2];
                        if (file_exists($filename)) {
                            $css = "";
                        } else {
                            $css = 'style=color:red';
                        }
                        ?>
                        
                        <td <?php echo $css; ?>>http://source.coupille.fr/<?php echo $data[2]; ?></td>
                        <?php
                    }else{
                        ?>
                        <td></td>
                        <?php
                    }
                    
                ?>                
                <td><?php echo $data[3]; ?></td>
                <td><?php echo $data[4]; ?></td>
                <td><?php echo $data[5]; ?></td>
                <td><?php echo $data[6]; ?></td>
                <td><?php echo $data[7]; ?></td>
                <td><?php echo $data[8]; ?></td>
                <td><?php echo $data[9]; ?></td>
            </tr>
 <?php }?>
<?php
    }
    ?>
        </table>
<?php
}
fclose($CSVfp);