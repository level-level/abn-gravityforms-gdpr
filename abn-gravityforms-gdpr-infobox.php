<?php
namespace ABN\GDPR\GravityForms;

class Infobox {

    public function __construct(){
        $this->gravityFormsInfoboxInit();
    }

    public function gravityFormsInfoboxInit(){
        add_filter( 'option_gravityformsaddon_itsg_gf_infobox_settings', array( $this, 'overwriteInfoboxOptions') );
        add_filter( 'gform_pre_render', array( $this, 'gformPreRender'), 10, 1 );
    }

    /**
     * Always stop the enqueing of the Infobox CSS because we load our own
     */
    public function overwriteInfoboxOptions( $option ) {

        if( array_key_exists( 'includecss', $option ) ){
           $option['includecss'] = false;
        }
        return $option;
    }


    /**
     * Check if form has a field Infobox and overwrite some values / settings
     */
    public function gformPreRender( $form ){
        //var_dump( $form );
        foreach ( $form['fields'] as $field ){
            if( $field['type'] === 'Infobox' ){

                // clear titel / label
                $field['type'] = 'Infobox';
                $field['label'] = '';

                // Default text if it's empty to mimic adding the default text.
                if( $field['description'] === '' ){
                    $field['description'] = 'We gebruiken de persoonlijke informatie die u invult, om uw verzoek (of aanvraag) te verwerken. Wilt u meer weten over hoe we omgaan met uw persoonlijke informatie? <a target="_blank" rel=noopener  href="https://www.abnamro.nl/nl/prive/abnamro/privacy/toelichting-privacy.html">Lees meer in ons privacy statement.</a>';
                }

            }
        }

        return $form;
    }
}