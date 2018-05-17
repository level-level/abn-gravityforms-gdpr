<?php
namespace ABN\GDPR\GravityForms;


/**
 * Here we miss-use the infobox plugin instead of building a whole custom Gravity Forms field.
 */
class Infobox {

    public function __construct(){
        $this->gravityFormsInfoboxInit();
    }

    public function gravityFormsInfoboxInit(){
        $option = 'gravityformsaddon_itsg_gf_infobox_settings';
        add_action( 'wp_print_scripts', array( $this, 'dequeue_script' ), 100 );
        add_filter( 'default_option_'.$option.'', array( $this, 'overwriteInfoboxOptions') );
        add_filter( 'pre_option_'.$option.'', array( $this, 'overwriteInfoboxOptions') );
        add_filter( 'gform_pre_render', array( $this, 'gformPreRender'), 10, 1 );
    }

    /**
     * Always stop the enqueing of the Infobox CSS because we load our own.
     * Just include the Less file from the /assets/infobox.less if you would like the default styling of copy it and build your own.
     *
     */
    public function overwriteInfoboxOptions( $option ) {
        if (!is_array($option)) {
            $option = array();
        }

        $option['includecss'] = false;
        return $option;
    }

    public function dequeue_script() {
        wp_dequeue_script( 'itsp_infobox_script' );
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