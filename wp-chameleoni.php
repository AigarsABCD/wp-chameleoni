<?php
/*
Plugin Name: Chameleoni integration
Description: Plugin for sending CVs to Chameleoni
Version: 1.0.0
Author: Wave
Author URI: http://www.wave-digital.co.uk
Text Domain: chameleoni
*/

require_once( plugin_dir_path( __FILE__ ) . 'chameleoni.class.php' );

function main_load_chameleoni($application_id){


    $post = get_post( $application_id );

    $meta = get_post_meta( $application_id );

    if(!empty($meta)){

        //file_put_contents(plugin_dir_path( __FILE__ ) . "log.txt", print_r($post, true), FILE_APPEND);
        //file_put_contents(plugin_dir_path( __FILE__ ) . "log.txt", print_r($meta, true), FILE_APPEND);

        $name_split = explode(' ', $meta['Full name'][0]);

        $forename = $name_split[0];
        $surname = $name_split[count($name_split)-1];
        $email = $meta['_candidate_email'][0];


        $chameleoni = new chameleoni();


        $check_email = $chameleoni->CheckEmail($email);

        if ($check_email['response']->ContactCount > 1) {

            $res = $chameleoni->CandidateLogin($email, 'gsdfgsdhsdghrsdghsdg');

        } else {

            $res = $chameleoni->CandidateRegister(1, $forename, $surname, $email, 'gsdfgsdhsdghrsdghsdg');

        }

        file_put_contents(plugin_dir_path(__FILE__) . "log.txt", print_r($res, true), FILE_APPEND);

        $ContactId = $res['response']->ContactId;

        $res = $chameleoni->AttachCV($ContactId, unserialize($meta['_attachment_file'][0])[0]);

        file_put_contents(plugin_dir_path(__FILE__) . "log.txt", print_r($res, true), FILE_APPEND);



    }








}

add_action( 'new_job_application', 'main_load_chameleoni' );
register_activation_hook( __FILE__, 'activate_func_chameleoni' );
register_deactivation_hook( __FILE__, 'deactivate_func_chameleoni' );


function activate_func_chameleoni(){


}

function deactivate_func_chameleoni(){


}