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
        $this->load->model('main_model');

        $this->load->library('pagination');

        //TWIG
        $this->load->library('twig');

        //$this->twig->addGlobal('sitename', 'My Awesome Site');
        $this->twig->addGlobal('ORGAN_ID_RCU', Main_model::ORGAN_ID_RCU);
        $this->twig->addGlobal('PHOTO_CNT_PER_SD', Main_model::PHOTO_CNT_PER_SD);
        $this->twig->addGlobal('VIDEO_CNT_PER_SD', Main_model::VIDEO_CNT_PER_SD);
        $this->twig->addGlobal('AUDIO_CNT_PER_SD', Main_model::AUDIO_CNT_PER_SD);
    }

    public function re_login()
    {
        $cookie = $this->input->cookie('key_cookie_speciald', true);
        $cookie_from_journal = $this->input->cookie('key_cookie_speciald_from_journal', true);

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


                if (isset($cookie_from_journal) && !empty($cookie_from_journal)) {// auth was from journal
                    $journal_user = $this->user_model->get_journal_permissions_by_user_id($cookie_from_journal);

                    if (isset($journal_user) && !empty($journal_user)) {

                        //set data from journal
                        $this->session->set_userdata('auth_fio', $journal_user['fio_for_speciald']);
                        $this->session->set_userdata('position_name', $journal_user['position_for_speciald']);
                        $this->session->set_userdata('rank_name', $journal_user['rank_name']);
                        $this->session->set_userdata('creator_name', $journal_user['creator_name_for_speciald']);
                        $this->session->set_userdata('id_user_jour', $journal_user['id_user']);
                        $this->set_current_user();
                    }
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
