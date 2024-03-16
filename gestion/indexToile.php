<?php
include ('/home/cose2962/www.coupille.fr/wordpress/wp-config.php');
include ('/home/cose2962/www.coupille.fr/wordpress/wp-load.php');
include ('/home/cose2962/www.coupille.fr/wordpress/wp-includes/pluggable.php');
include ('/home/cose2962/www.coupille.fr/wordpress/wp-admin/includes/post.php');

//FICHIERS REQUIS POUR INSERTION IMAGE MISE EN AVANT
require_once('/home/cose2962/www.coupille.fr/wordpress/wp-admin/includes/media.php');
require_once('/home/cose2962/www.coupille.fr/wordpress/wp-admin/includes/file.php');
require_once('/home/cose2962/www.coupille.fr/wordpress/wp-admin/includes/image.php');
?>

<?php
$CSVfp = fopen("paul.csv", "r");

if ($CSVfp !== FALSE) {

    while (! feof($CSVfp)) {
        
        $data = fgetcsv($CSVfp, 3000, ";");

        if (! empty($data)) {
            
                $titrePost = $data[3].' - '.$data[6];

                $filename = '/home/cose2962/coupille_sources_hd/'.$data[2];

                if (file_exists($filename)) {
                    $etat = 'publish';
                }else{
                    $etat = 'draft';
                }
                
                $post_information = array(
                    'post_title' => $titrePost,
                    'post_type' => 'toile',
                    'post_status' => $etat
                );                

                $toileIdWordpress = wp_insert_post($post_information);

                //UPDATE dateInsertWordpress
                $dateInsertWordpress = date('d-m-y h:i:s');
                                
                // ID Excel
                update_field('field_633846ecc5694',$data[0], $toileIdWordpress);
                // Base initiale
                update_field('field_633845315f0a6',$data[1], $toileIdWordpress);
                // Numéro de toile
                update_field('field_63384593e1a53',$data[3], $toileIdWordpress);
                // Année
                update_field('field_633845a59bee4',$data[4], $toileIdWordpress);
                // Dimensions
                update_field('field_633845b99bee5',$data[5], $toileIdWordpress);
                // Titre
                update_field('field_633845bf9bee6',$data[6], $toileIdWordpress);
                
                // Type d'oeuvre
                // update_field('field_63399508a0224',$data[], $toileIdWordpress);
                // Description
                // update_field('field_6338666e971d8',$data[3], $post_ID);

                // Collection
                $valueCollection = array($data[7]);
                update_field('field_633845c69bee7',$valueCollection, $toileIdWordpress);
                // Localisation
                $valueLocalisation = array($data[8]);
                update_field('field_633846021256b',$valueLocalisation, $toileIdWordpress);
                // Observations
                update_field('field_63384885f15a2',$data[9], $toileIdWordpress);


                $seoTitleToile = 'Paul COUPILLE - '.$data[6];
                $seoDescriptionToile = 'Paul COUPILLE, toute l\'oeuvre du peintre : '.$data[6].' - '.$data[4];

                
                if (file_exists($filename)) {
                    
                    $urlToile = 'http://source.coupille.fr/'.$data[2];

                    echo $urlToile;


                    // AJOUT IMAGE MISE EN AVANT SEJOUR
                    $url     = $urlToile;
                    $post_id = $toileIdWordpress;
                    $desc    = $titrePost;

                    $image = media_sideload_image( $url, $post_id, $desc,'id' );
                    set_post_thumbnail( $post_id, $image );
                    

                }
                else{
                }           
                
                //UPDATE YOAST SEO TITLE
                update_post_meta( $toileIdWordpress, '_yoast_wpseo_title', $seoTitleToile );
                //UPDATE YOAST SEO DESCRIPTION
                update_post_meta( $toileIdWordpress, '_yoast_wpseo_metadesc', $seoDescriptionToile );

                wp_reset_query();                

        }
    }
}
fclose($CSVfp);
