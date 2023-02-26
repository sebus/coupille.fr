<?php

$CSVfp = fopen("sample.csv", "r");

if ($CSVfp !== FALSE) {

    while (! feof($CSVfp)) {
        
        $data = fgetcsv($CSVfp, 3000, ";");

        if (! empty($data)) {
            ?>

                <?php echo $data[0]; ?>
                <?php echo $data[1]; ?>
                <?php
                    if($data[2] !=''){

                        $filename = '/home/cose2962/coupille_sources_hd/'.$data[2];
                        if (file_exists($filename)) {
                            $css = "";
                        } else {
                            $css = 'style=color:red';
                        }
                        ?>
                        
                        <?php echo 'http://source.coupille.fr/'.$data[2]; ?>
                        <?php
                    }else{
                        ?>
                        <?php
                    }
                    
                ?>                
                <?php echo $data[3]; ?>
                <?php echo $data[4]; ?>
                <?php echo $data[5]; ?>
                <?php echo $data[6]; ?>
                <?php echo $data[7]; ?>
                <?php echo $data[8]; ?>
                <?php echo $data[9]; ?>
        <?php }?>
    <?php
    }
}
fclose($CSVfp);