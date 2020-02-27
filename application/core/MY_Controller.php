<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * System base controller
 *
 * @property Program_model $program_model
 * @property Main_model $main_model
 * @property Matching_model $matching_model
 * @property Ion_auth_model $ion_auth_model
 * @property Ion_auth $ion_auth
 * @property CI_Session $session
 * @property Mentors_model $mentors_model
 * @property CI_Input $input
 */
class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');

        //TWIG
        $this->load->library('twig');

        //$this->twig->addGlobal('sitename', 'My Awesome Site');
        $this->twig->addGlobal('ORGAN_ID_RCU', ORGAN_ID_RCU);
    }

    public function re_login()
    {
        $cookie = $this->input->cookie('key_cookie_speciald', true);

        $id = $this->session->userdata('id_user');

        if (!isset($id) || empty($id)) {

            if (isset($cookie) && !empty($cookie)) {

                $user = $this->user_model->get_permissions_by_user_id($cookie);
                if (isset($user) && !empty($user)) {

                    //start session
                    $this->session->set_userdata($user);
                    $this->set_current_user();
                } else {
                    redirect('auth');
                }
            } else {
                redirect('auth');
            }
        } else {
            $this->set_current_user();
        }
    }

    public function set_current_user()
    {
        $this->data['sidebar'] = $this->session->userdata('role');
        $this->data['active_user'] = $this->session->userdata;
    }
}
