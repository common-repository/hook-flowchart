<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Hook_Flowchart
 * @author    Mte90 <daniele@codeat.it>
 * @license   GPL-2.0+
 * @link      http://codeat.it
 * @copyright 2015 GPL
 */
?>

<div class="wrap">
    <h2>Hook Flowchart</h2>
    <?php
    $cmb = new_cmb2_box( array(
	'id' => 'options',
	'hookup' => false,
	'show_on' => array( 'key' => 'options-page', 'value' => array( $this->plugin_slug ), ),
	'show_names' => true,
	    ) );

    $cmb->add_field( array(
	'name' => __( 'Exclude Parent Hook', $this->plugin_slug ),
	'desc' => __( 'Separate with a comma', $this->plugin_slug ),
	'id' => 'excluded',
	'type' => 'textarea'
    ) );

    cmb2_metabox_form( 'options', $this->plugin_slug );
    ?>
</div>
</div>