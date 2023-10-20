<?php
include_once 'conf.php';
include ($racine_site . '/wp-config.php');
include ($racine_site . '/wp-load.php');
include ($racine_site . '/wp-includes/pluggable.php');
include ($racine_site . 'wp-admin/includes/post.php');


//FICHIERS REQUIS POUR INSERTION IMAGE MISE EN AVANT
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');


?>

<?php
$xml=simplexml_load_file($urlCatalaogueXml, 'SimpleXMLElement', LIBXML_NOCDATA) or die("Error: Cannot create object");

$xml = $xml->Segments[0];
foreach ($xml->Segment as $sejour) {

    //ON RECUPERE LE TYPE D'ARTICLE
    $typeSejourPGITO = $sejour->Code->attributes()->Group;
    
    //ON TEST TYPE ARTICLE = SEJ
    if ($typeSejourPGITO == "SEJ") {
        //ID SEJOUR PGITO
        $idSejourPGITO = (string)$sejour->Code->attributes()->ID;
        //REF SEJOUR
        $refSejourPGITO = (string)$sejour->Code->attributes()->Value;

        // ON TESTE SI LE SEJOUR EXISTE OU PAS
        $args = array(
            'post_type' => 'sejour',
            'meta_query' => array(
                array(
                'key' => 'id_sejour',
                'value' => $idSejourPGITO,
                'compare' => '='
                )
            )
        );

        $posts = get_posts( $args );

        // ON REPERE LE SEJOUR QUI EST TRAITE DANS LES CPT
        $post_ID = $posts[0]->ID;
    
        if ($posts) {
            //UPDATE CPT SEJOUR

            $date_update_wordpress = date('d-m-y h:i:s');
            //UPDATE date_upadte_wordpress
            update_field('field_62b1d5784454d',$date_update_wordpress, $post_ID);

            $attrXmlInfo = (string)$sejour->Descriptions->Description->attributes()->Role;
            $groupSejour = (string)$sejour->Code->attributes()->Group;
            $idSejour = (string)$sejour->Code->attributes()->ID;
            $refSejour = (string)$sejour->Code->attributes()->Value;
            $allXmlInfo = $sejour->Descriptions->Description;

            //RECUPERATION NOM SEJOUR
            if ($attrXmlInfo == "Name") {
                $nomSejour = (string)$sejour->Descriptions->Description->Text;
            }

            foreach ($allXmlInfo as $value) {
                $whatSejour = $value->attributes()->What;
                $roleSejour = $value->attributes()->Role;

                //RECUPERATION SEO TITLE
                if ($whatSejour == "Title") {
                    $seoTitlesejour = (string)$value->Text;
                }
                 //RECUPERATION SEO DESCIPTION
                 if ($whatSejour == "Description") {
                    $seoDescriptionsejour = (string)$value->Text;
                }

                //RECUPERATION INTRODUCTION
                if ($roleSejour == "Introduction") {
                    $introductionSejour = (string)$value->Text;
                }

                //RECUPERATION DESCRIPTIF
                if ($roleSejour == "Sales") {
                    $descriptifSejour = (string)$value->Text;
                }

                $imgSliderIntro = [];
                $imgGallery = [];
                foreach ($value as $value2) {
                    $subRoleSejour = (string)$value2->Type->attributes()->Ref;
                    //RECUPERATION ACTIVITES
                    if ($subRoleSejour == "produit-activite-s") {
                        $acitviteStringSejour = (string)$value->Paragraph->Text;
                        $explodeActivités = explode("|", $acitviteStringSejour);
                        $finalActivites = [];
                        $finalActivitesHtml = [];
                        $lenghtTableauActivite = count($explodeActivités);
                        for ($i=0; $i < $lenghtTableauActivite; $i++) { 
                            $var = array('activite_sejour' => "<span>".$explodeActivités[$i]."</span>");
                            $varNoHtml = array('activite_sejour' => $explodeActivités[$i]);
                            array_push($finalActivites, $varNoHtml);
                            array_push($finalActivitesHtml, $var);
                        }
                    }
                    //RECUPERATION PRIX COMPREND
                    if ($subRoleSejour == "_TY_IncludedPrice") {
                        $prixComprendSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION PRIX NE COMPREND PAS
                    if ($subRoleSejour == "_TY_NotIncludedPrice") {
                        $prixNeComprendPasSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION PRIX OPTION
                    if ($subRoleSejour == "_TY_AddOn") {
                        $prixOptionSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION MEDIA
                    if ($roleSejour == "Media") {
                        $imgSliderIntroSejour = (string)$value2->Description;
                        //IMAGE SLIDER INTRO SEJOUR (PGITO INTRODUCTION)
                        if ($imgSliderIntroSejour == "_TY_Introduction") {
                            $urlImgSliderIntroSejour = (string)$value2->URL;
                            $varImgIntro = array('image_slider_intro_sejour' => array(
                                    'title' => 'img',
                                    'url' => $urlImgSliderIntroSejour,
                            ));
                            array_push($imgSliderIntro, $varImgIntro);   
                        }
                        //IMAGE GALLERY SEJOUR (PGITO JOUR)
                        if ($imgSliderIntroSejour == "_TY_Itinerary") {
                            $urlImgGalleryIntroSejour = (string)$value2->URL;
                            $varImgGallery = array('image_gallery_sejour' => array(
                                'title' => 'img',
                                'url' => $urlImgGalleryIntroSejour,
                            ));
                            array_push($imgGallery, $varImgGallery);
                        }
                        //PDF BROCHURE SEJOUR
                        $PDFTypebrochure = (string)$value2->attributes()->Type;
                        if ($PDFTypebrochure == "fiche_sejour") {
                            $PDFBrochure = (string)$value2->URL;
                        }
                        //IMAGE MISE EN AVANT (PGITO DESCRIPTIF)
                        if ($imgSliderIntroSejour == "_TY_PracticalInformation") {
                            $URLImageMiseEnAvant = (string)$value2->URL;
                        }
                    }
                    //RECUPERATION URL IFRAME CARTE
                    if ($subRoleSejour == "produit-iframe-maps") {
                        $iframeCarteSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION URL VIDEO YOUTUBE
                    if ($subRoleSejour == "url-youtube") {
                        $urlYoutubeSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION HEBERGEMENT
                    if ($subRoleSejour == "_TY_Accomodation") {
                        $hebergementSejour = (string)$value->Paragraph->Text;
                    }
                }
            }

             //UPDATE titre_sejour
             update_field('field_62b18f1a61919',$nomSejour, $post_ID);
             //UPDATE reference_sejour
             update_field('field_62b18fb47a58d',$refSejour, $post_ID);
             //UPDATE mini-intro
             update_field('field_62b190467a58f',$introductionSejour, $post_ID);
             //UPDATE descriptif
             update_field('field_6317390ad08d1',$descriptifSejour, $post_ID);
             //UPDATE activites_sejour_repeteur
             update_field('field_62d6726bd7871',$finalActivitesHtml, $post_ID);
             //UPDATE activites_sejour_repeteur_no_html
             update_field('field_632ac25a54b74',$finalActivites, $post_ID);
             //UPDATE prix_comprend
             update_field('field_6319910918607',$prixComprendSejour, $post_ID);
             //UPDATE prix_ne_comprend_pas
             update_field('field_631991cb226a1',$prixNeComprendPasSejour, $post_ID);
             //UPDATE prix_options
             update_field('field_6319972f56a42',$prixOptionSejour, $post_ID);
             //UPDATE image_slider_intro_sejour_repeteur
             update_field('field_63183c29eadea',$imgSliderIntro, $post_ID);
             //UPDATE image_gallery_sejour_repeteur
             update_field('field_631b2e247f08a',$imgGallery, $post_ID);
             //UPDATE iframe_carte_jour
             update_field('field_6319e1126109a',$iframeCarteSejour, $post_ID);
             //UPDATE url_youtube_sejour
             update_field('field_6336a9f60880f',$urlYoutubeSejour, $post_ID);
             //UPDATE hebergement_jour
             update_field('field_6319ea7861ce2',$hebergementSejour, $post_ID);
             //UPDATE fiche_pdf_jour
             update_field('field_6319edceced28',$PDFBrochure, $post_ID);

            //UPDATE YOAST SEO TITLE
            update_post_meta( $post_ID, '_yoast_wpseo_title', $seoTitlesejour );
            //UPDATE YOAST SEO DESCRIPTION
            update_post_meta( $post_ID, '_yoast_wpseo_metadesc', $seoDescriptionsejour );
 
 
             // AJOUT IMAGE MISE EN AVANT SEJOUR
             $url     = $URLImageMiseEnAvant;
             $post_id = $post_ID;
             $desc    = "featured";
 
             $image = media_sideload_image( $url, $post_id, $desc,'id' );
 
             set_post_thumbnail( $post_id, $image );
        
        }else{
            //CREATION CPT SEJOUR
            $attrXmlInfo = (string)$sejour->Descriptions->Description->attributes()->Role;
            $groupSejour = (string)$sejour->Code->attributes()->Group;
            $idSejour = (string)$sejour->Code->attributes()->ID;
            $refSejour = (string)$sejour->Code->attributes()->Value;
            $allXmlInfo = $sejour->Descriptions->Description;

            //RECUPERATION NOM SEJOUR
            if ($attrXmlInfo == "Name") {
                $nomSejour = (string)$sejour->Descriptions->Description->Text;
            }

            foreach ($allXmlInfo as $value) {
                $whatSejour = $value->attributes()->What;
                $roleSejour = $value->attributes()->Role;

                
                //RECUPERATION PERMALINK
                if ($whatSejour == "Permalink") {
                    $permalinksejour = (string)$value->Text;
                }
                //RECUPERATION SEO TITLE
                if ($whatSejour == "Title") {
                    $seoTitlesejour = (string)$value->Text;
                }
                 //RECUPERATION SEO DESCIPTION
                 if ($whatSejour == "Description") {
                    $seoDescriptionsejour = (string)$value->Text;
                }
                //RECUPERATION INTRODUCTION
                if ($roleSejour == "Introduction") {
                    $introductionSejour = (string)$value->Text;
                }
                //RECUPERATION DESCRIPTIF
                if ($roleSejour == "Sales") {
                    $descriptifSejour = (string)$value->Text;
                }

                $imgSliderIntro = [];
                $imgGallery = [];
                foreach ($value as $value2) {
                    $subRoleSejour = (string)$value2->Type->attributes()->Ref;
                    //RECUPERATION ACTIVITES
                    if ($subRoleSejour == "produit-activite-s") {
                        $acitviteStringSejour = (string)$value->Paragraph->Text;
                        $explodeActivités = explode("|", $acitviteStringSejour);
                        $finalActivites = [];
                        $finalActivitesHtml = [];
                        $lenghtTableauActivite = count($explodeActivités);
                        for ($i=0; $i < $lenghtTableauActivite; $i++) { 
                            $var = array('activite_sejour' => "<span>".$explodeActivités[$i]."</span>");
                            $varNoHtml = array('activite_sejour' => $explodeActivités[$i]);
                            array_push($finalActivites, $varNoHtml);
                            array_push($finalActivitesHtml, $var);
                        }
                    }
                    //RECUPERATION MASSIF
                    if ($subRoleSejour == "massifs") {
                        $massifSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION DESTINATION
                    if ($subRoleSejour == "destination") {
                        $destinationSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION PARENT OU ENFANT
                    if ($subRoleSejour == "sejour-principal") {
                        $parentEnfantSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION ID PARENT
                    if ($parentEnfantSejour == "Enfant") {
                        if ($subRoleSejour == "num-id-parent") {
                            $idParentSejour = (string)$value->Paragraph->Text;
                        }
                    }else{
                        $idParentSejour = "";
                    }
                    //RECUPERATION PRIX COMPREND
                    if ($subRoleSejour == "_TY_IncludedPrice") {
                        $prixComprendSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION PRIX NE COMPREND PAS
                    if ($subRoleSejour == "_TY_NotIncludedPrice") {
                        $prixNeComprendPasSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION PRIX OPTION
                    if ($subRoleSejour == "_TY_AddOn") {
                        $prixOptionSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION MEDIA
                    if ($roleSejour == "Media") {
                        $imgSliderIntroSejour = (string)$value2->Description;
                        //IMAGE SLIDER INTRO SEJOUR (PGITO INTRODUCTION)
                        if ($imgSliderIntroSejour == "_TY_Introduction") {
                            $urlImgSliderIntroSejour = (string)$value2->URL;
                            $varImgIntro = array('image_slider_intro_sejour' => array(
                                    'title' => 'img',
                                    'url' => $urlImgSliderIntroSejour,
                            ));
                            array_push($imgSliderIntro, $varImgIntro);   
                        }
                        //IMAGE GALLERY SEJOUR (PGITO JOUR)
                        if ($imgSliderIntroSejour == "_TY_Itinerary") {
                            $urlImgGalleryIntroSejour = (string)$value2->URL;
                            $varImgGallery = array('image_gallery_sejour' => array(
                                'title' => 'img',
                                'url' => $urlImgGalleryIntroSejour,
                            ));
                            array_push($imgGallery, $varImgGallery);
                        }
                        //PDF BROCHURE SEJOUR
                        $PDFTypebrochure = (string)$value2->attributes()->Type;
                        if ($PDFTypebrochure == "fiche_sejour") {
                            $PDFBrochure = (string)$value2->URL;
                        }
                        //IMAGE MISE EN AVANT (PGITO DESCRIPTIF)
                        if ($imgSliderIntroSejour == "_TY_PracticalInformation") {
                            $URLImageMiseEnAvant = (string)$value2->URL;
                        }
                    }
                    //RECUPERATION URL IFRAME CARTE
                    if ($subRoleSejour == "produit-iframe-maps") {
                        $iframeCarteSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION URL VIDEO YOUTUBE
                    if ($subRoleSejour == "url-youtube") {
                        $urlYoutubeSejour = (string)$value->Paragraph->Text;
                    }
                    //RECUPERATION HEBERGEMENT
                    if ($subRoleSejour == "_TY_Accomodation") {
                        $hebergementSejour = (string)$value->Paragraph->Text;
                    }
                }
            }

            // CREATION SEJOUR CPT
            $post_information = array(
                'post_title' => $nomSejour,
                // 'post_name' => $urlBien,
                'post_type' => 'sejour'
            );

            $sejourIDwordPress = wp_insert_post($post_information);

            //UPDATE dateInsertWordpress
            $dateInsertWordpress = date('d-m-y h:i:s');
            update_field('field_62b1d55d695e2',$dateInsertWordpress, $sejourIDwordPress);
            //UPDATE titre_sejour
            update_field('field_62b18f1a61919',$nomSejour, $sejourIDwordPress);
            //UPDATE group_sejour
            update_field('field_62b18f7b7a58b',$groupSejour, $sejourIDwordPress);
            //UPDATE id_sejour
            update_field('field_62b18f8b7a58c',$idSejour, $sejourIDwordPress);
            //UPDATE reference_sejour
            update_field('field_62b18fb47a58d',$refSejour, $sejourIDwordPress);
            //UPDATE mini-intro
            update_field('field_62b190467a58f',$introductionSejour, $sejourIDwordPress);
            //UPDATE descriptif
            update_field('field_6317390ad08d1',$descriptifSejour, $sejourIDwordPress);
            //UPDATE activites_sejour_repeteur
             update_field('field_62d6726bd7871',$finalActivitesHtml, $post_ID);
            //UPDATE activites_sejour_repeteur_no_html
             update_field('field_632ac25a54b74',$finalActivites, $post_ID);
            //UPDATE massif_sejour
            update_field('field_62d6a160bfe20',$massifSejour, $sejourIDwordPress);
            //UPDATE destination_sejour
            update_field('field_62d6a8ef01e56',$destinationSejour, $sejourIDwordPress);
            //UPDATE parent_enfant_sejour
            update_field('field_62d6a9e860a7d',$parentEnfantSejour, $sejourIDwordPress);
            //UPDATE id_parent
            update_field('field_62d6ab2a8eb77',$idParentSejour, $sejourIDwordPress);
            //UPDATE url_id_parent
            update_field('field_63033e209aa26',$permalinksejour, $sejourIDwordPress);
            //UPDATE prix_comprend
            update_field('field_6319910918607',$prixComprendSejour, $sejourIDwordPress);
            //UPDATE prix_ne_comprend_pas
            update_field('field_631991cb226a1',$prixNeComprendPasSejour, $sejourIDwordPress);
            //UPDATE prix_options
            update_field('field_6319972f56a42',$prixOptionSejour, $sejourIDwordPress);
            //UPDATE image_slider_intro_sejour_repeteur
            update_field('field_63183c29eadea',$imgSliderIntro, $sejourIDwordPress);
            //UPDATE image_gallery_sejour_repeteur
            update_field('field_631b2e247f08a',$imgGallery, $sejourIDwordPress);
            //UPDATE iframe_carte_jour
            update_field('field_6319e1126109a',$iframeCarteSejour, $sejourIDwordPress);
            //UPDATE url_youtube_sejour
            update_field('field_6336a9f60880f',$urlYoutubeSejour, $sejourIDwordPress);
            //UPDATE hebergement_jour
            update_field('field_6319ea7861ce2',$hebergementSejour, $sejourIDwordPress);
            //UPDATE fiche_pdf_jour
            update_field('field_6319edceced28',$PDFBrochure, $sejourIDwordPress);

            //UPDATE YOAST SEO TITLE
            update_post_meta( $sejourIDwordPress, '_yoast_wpseo_title', $seoTitlesejour );
            //UPDATE YOAST SEO DESCRIPTION
            update_post_meta( $sejourIDwordPress, '_yoast_wpseo_metadesc', $seoDescriptionsejour );


            // AJOUT IMAGE MISE EN AVANT SEJOUR
            $url     = $URLImageMiseEnAvant;
            $post_id = $sejourIDwordPress;
            $desc    = "featured";

            $image = media_sideload_image( $url, $post_id, $desc,'id' );

            set_post_thumbnail( $post_id, $image );

            //MAJ DE LA LOCATION POUR LE BASCULER EN PUBLISH
            $new_elem = array(
                "post_name" => $permalinkSejour,
                'post_type' => 'sejour',
                'ID' => $sejourIDwordPress,
                'post_status' => 'publish'
            );

            wp_update_post($new_elem);

        }

    }
    
}


//GESTION DU FICHIER PRIX XML
$xml=simplexml_load_file($urlPrixXml) or die("Error: Cannot create object");

$additions = [];

foreach ($xml->Segments->Segments->Segment as $segment) {

    // ID SEJOUR
    $idSejour = (string)$segment->Code->attributes()->ID;

    //DATE DEBUT SEJOUR
    $dateDebutSejourTMP = $segment->Segments->Segment->children()->Begins;
    $tableauDateDebut = [];
    $finalTableauDateDebut = [];
    foreach ($dateDebutSejourTMP as $value) {
        $tmp = $value->Begin;
        foreach ($tmp as  $value2) {
            $dateDebutTMP = (string)$value2->attributes()->Value;
            array_push($tableauDateDebut, $dateDebutTMP);
        }
    }

    // TRIE DATE PAR ORDRE CROISSANT
    usort($tableauDateDebut, "sortFunction");

    $lenghtTableauDateDebut = count($tableauDateDebut);

    $finalDateDebutSejour = array();

    for ($i=0; $i < $lenghtTableauDateDebut; $i++) { 
        $var = array('date_debut_sejour' => $tableauDateDebut[$i]);
        array_push($finalDateDebutSejour, $var);
    }

    //DATE FIN SEJOUR
    $finFinSejourTMP = $segment->Segments->Segment->children()->Durations;
    $tableauDateFin = [];
    $finalTableauDateFin = [];
    foreach ($finFinSejourTMP as $value) {
        $tmp = $value->Duration;
        foreach ($tmp as  $value2) {
            $finTMP = (string)$value2->attributes();
            if ($finTMP == "Night") {
                $numberDays = (string)$value2->attributes()->Value;
                array_push($tableauDateFin, $numberDays);
            }
            
        }
        
    }

    $finalAddDaysTableauDateFin = [];
    foreach ($finalDateDebutSejour as $value) {
        $tmpDateFinal = date( "Y-m-d",strtotime($value["date_debut_sejour"]. '+'.$tableauDateFin[0].' day' ));
        array_push($finalAddDaysTableauDateFin, $tmpDateFinal);
    }

    // TRIE DATE PAR ORDRE CROISSANT
    usort($finalAddDaysTableauDateFin, "sortFunction");

    $lenghtTableauFinDebut = count($finalAddDaysTableauDateFin);

    $finalDateFinSejour = array();

    for ($i=0; $i < $lenghtTableauFinDebut; $i++) { 
        $var = array('date_fin_sejour' => $finalAddDaysTableauDateFin[$i]);
        array_push($finalDateFinSejour, $var);
    }

    //PRIX SEJOUR
    $prixSejour = (string)$segment->Segments->Segment->Begins->Begin->Price->attributes()->Value;
    $prixSejour = substr($prixSejour, 0, 3);
    $prixSejour =  (string)$prixSejour." €";

    //NOMBRE JOUR SEJOUR
    $nombreJourSejour = $segment->Segments->Segment->Durations->Duration;
    foreach ($nombreJourSejour as $valueNbreJour) {
        $unitJourSejour = (string)$valueNbreJour->attributes()->Unit;
        if ($unitJourSejour == "Day") {
            $valueNbrJourSejour = (string)$valueNbreJour->attributes()->Value;
        }
    }
    

    //ON DETECTE SI LE SEJOUR EST DEJA PRESENT DANS LES CPT
    $args = array(
        'post_type' => 'sejour',
        'meta_query' => array(
            array(
            'key' => 'id_sejour',
            'value' => $idSejour,
            'compare' => '='
            )
        )
    );

    $posts = get_posts( $args );
    // ON REPERE LE SEJOUR QUI EST TRAITE DANS LES CPT
    $post_ID = $posts[0]->ID;
    // SI LE SEJOUR EXISTE ON UPDATE LES CHAMPS
    if ($posts) {
        $urlSejour = get_permalink($post_ID);
        
        $date_update_wordpress = date('d-m-y h:i:s');

        //ON RASSEMBLE LES DATES DEBUT ET DATES FIN
        $arrayDateGlobal = [];
        foreach ($finalDateDebutSejour as $key => $value) {
            $dateDebutGlobal = $value["date_debut_sejour"];
            $dateDebutGlobal = date_create($dateDebutGlobal);
            $dateDebutGlobal = date_format($dateDebutGlobal,"d/m/Y");
            $dateFinGlobal = $finalDateFinSejour[$key]["date_fin_sejour"];
            $dateFinGlobal = date_create($dateFinGlobal);
            $dateFinGlobal = date_format($dateFinGlobal,"d/m/Y");
            $globalDate = $dateDebutGlobal." au ".$dateFinGlobal;
            array_push($arrayDateGlobal, $globalDate);
        }

        // TRIE DATE PAR ORDRE CROISSANT
        // usort($tableauDateDebut, "sortFunction");

        $lenghtTableauDateGLobal = count($arrayDateGlobal);

        $finalDateGlobalSejour = array();

        for ($i=0; $i < $lenghtTableauDateGLobal; $i++) { 
            $var = array('date_global_sejour' => $arrayDateGlobal[$i]);
            array_push($finalDateGlobalSejour, $var);
        }

        //UPDATE url_id_parent
        update_field('field_63033e209aa26',$urlSejour, $post_ID);
        //UPDATE date_upadte_wordpress
        update_field('field_62b1d5784454d',$date_update_wordpress, $post_ID);
        //UPDATE date_debut_sejour
        update_field('field_62b3181ade074',$finalDateDebutSejour, $post_ID);
        //UPDATE fin_debut_sejour
        update_field('field_62b318b3de075',$finalDateFinSejour, $post_ID);
        //UPDATE date_global_sejour_repeteur
        update_field('field_632b04b4624e0',$finalDateGlobalSejour, $post_ID);
        //UPDATE nombre_jour_sejour
        update_field('field_6315eb6b514ea',$valueNbrJourSejour, $post_ID);
        //UPDATE prix_sejour
        update_field('field_62b318c2de076',$prixSejour, $post_ID);

    }

}

// INJECTION DE L'URL PARENT AUX CHAMPS URL ENFANT + // TEST SI PARENT A UNE DATE DEBUT SEJOUR
//ON BOUCLE POUR RECUPERER LES PARENTS
$loop = new WP_Query( array( 'post_type' => 'sejour', 'posts_per_page' => '5000' ) );
while ( $loop->have_posts() ) : $loop->the_post();
    $parentRECUP = get_field('parent_enfant_sejour');
    if ($parentRECUP == 'Parent') {
        $idSejourRECUP = get_field('id_sejour');
        $idWordpresSejour = get_the_id();
        $urlSejourParent = get_permalink();
 
        //UPDATE id_parent dans le parent
        update_field('field_62d6ab2a8eb77',$idSejourRECUP, $idWordpresSejour);

        $dateDebutSejourRecup = get_field('date_debut_sejour_repeteur');

        if ($dateDebutSejourRecup == NULL) {
            wp_set_post_categories( $idWordpresSejour, array( 3 ) );
        }else {
            wp_set_post_categories( $idWordpresSejour, array( 4 ) );
        }


        $arrayFIcheSejourPDF = array();
        $arrayNbreJourSejourEnfant = array();
        // ON BOUCLE POUR RECUPERER LES ENFANTS
        $loop2 = new WP_Query( array( 
            'post_type' => 'sejour',
            'posts_per_page' => '5000',
            'meta_key'		=> 'id_parent',
            'meta_value'	=> $idSejourRECUP
        ) );
        while ( $loop2->have_posts() ) : $loop2->the_post();
        $idWordpressEnfant = get_the_id();
        $titleSejourRECUP = get_field('titre_sejour');
        $enfantSejourRECUP = get_field('parent_enfant_sejour');
        $lienPDF = get_field('fiche_pdf_jour');
        $nbrJourPdf = get_field('nombre_jour_sejour');
        update_field('field_63033e209aa26',$urlSejourParent, $idWordpressEnfant);

        //FICHE PDF SEJOUR ENFANT AJOUTER AU PARENT
        array_push($arrayFIcheSejourPDF, $lienPDF);
        $lenghtarrayFIcheSejourPDF = count($arrayFIcheSejourPDF);
        $finalArrayFIcheSejourPDF = array();
        for ($i=0; $i < $lenghtarrayFIcheSejourPDF; $i++) { 
            $var = array('fiche_pdf_parent_enfant_sejour' => $arrayFIcheSejourPDF[$i]);
            array_push($finalArrayFIcheSejourPDF, $var);
        }

        //NOMBRE JOUR SEJOUR ENFANT AJOUTER AU PARENT
        array_push($arrayNbreJourSejourEnfant, $nbrJourPdf);
        $lenghtarrayNbreJourSejourEnfant = count($arrayNbreJourSejourEnfant);
        $finalArrayNbreJourSejourEnfant = array();
        for ($i=0; $i < $lenghtarrayNbreJourSejourEnfant; $i++) { 
            $var = array('nombre_jour_parent_enfant_sejour' => $arrayNbreJourSejourEnfant[$i]);
            array_push($finalArrayNbreJourSejourEnfant, $var);
        }

        //MODIFICATION URL ET NO INDEX SEJOUR ENFANT
        if ( $enfantSejourRECUP == 'Enfant') {
            wp_update_post([
                "post_name" => "enfant/$titleSejourRECUP",
                "ID" => $idWordpressEnfant,
            ]);

            update_post_meta($idWordpressEnfant,'_yoast_wpseo_meta-robots-noindex', '1');
        }
       

        endwhile; 
        wp_reset_query();
        //UPDATE fiche_pdf_parent_enfant
        $reversedFinalArrayFIcheSejourPDF = array_reverse($finalArrayFIcheSejourPDF);
        update_field('field_633a9d207f001',$reversedFinalArrayFIcheSejourPDF, $idWordpresSejour);
        //UPDATE nombre_jour_parent_enfant
        $reversedFinalArrayNbreJourSejourEnfant = array_reverse($finalArrayNbreJourSejourEnfant);
        update_field('field_633aaef56b6b1',$reversedFinalArrayNbreJourSejourEnfant, $idWordpresSejour);
    }

endwhile; 
wp_reset_query();