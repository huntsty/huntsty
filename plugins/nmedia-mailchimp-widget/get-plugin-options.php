<?php
//allows the plugin to get info from the plugin options page
global $nm_mc_options;
foreach ($nm_mc_options as $value) {
    if (get_option( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; }
    else { $$value['id'] = get_option( $value['id'] ); }
    }