<?php

/**
 * Class LoaderSuper_CPT
 */
class Super_CPT_Loader
{
    public static function load($SCPT_PLUGIN_URL, $SCPT_PLUGIN_DIR)
    {
        define('SCPT_PLUGIN_URL', $SCPT_PLUGIN_URL);
        define('SCPT_PLUGIN_DIR', $SCPT_PLUGIN_DIR);

        require_once 'super-cpt.php';
    }
}