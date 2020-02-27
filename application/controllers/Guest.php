<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public $id_user_speciald;
    public $id_user_journal;
    public $fio;
    public $position;
    public $rank;
    public $creator_name;
    public $id_rig;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        $this->load->model('user_model');

        $this->load->helper('cookie');
        $this->load->helper(['generate_salt']);
    }

    public function index($id_user_speciald = 0, $id_user_journal = 0, $id_rig = 0)
    {

        $id_user_speciald= intval($id_user_speciald);
        $id_user_journal= intval($id_user_journal);
        $id_rig= intval($id_rig);

        $this->id_user_speciald = $id_user_speciald;
        $this->id_user_journal = $id_user_journal;

        $journal_user = $this->user_model->get_journal_permissions_by_user_id($id_user_journal);

        $this->fio = $journal_user['fio_for_speciald'];
        $this->position = $journal_user['position_for_speciald'];
        $this->rank = $journal_user['rank_name'];
        $this->creator_name = $journal_user['creator_name_for_speciald'];
        $this->id_rig = $id_rig;


        $guest = $this->user_model->get_user_by_id($id_user_speciald);

        if (isset($guest) && !empty($guest) && $id_rig != 0 && $id_user_journal != 0 && $id_user_speciald != 0) {
            // print_r($guest);
            $this->login();
            $id = $this->session->userdata('id_user');

            redirect('create/form_standart');
        } else {

            redirect('catalog');
        }
    }

    public function login()
    {
        $user = $this->user_model->get_permissions_by_user_id($this->id_user_speciald);

        $this->session->set_userdata($user);

        $this->session->set_userdata('auth_fio', $this->fio);
        $this->session->set_userdata('position_name', $this->position);
        $this->session->set_userdata('rank_name', $this->rank);
        $this->session->set_userdata('creator_name', $this->creator_name);
        $this->session->set_userdata('id_rig', $this->id_rig);


        $remember_me = 1;

        if (isset($remember_me) && $remember_me == 1) {
            $key = generateSalt(); //назовем ее $key
            $this->set_cookie($this->session->userdata('id_user'), $this->session->userdata('id_user'));
        }
    }

    public function set_cookie($key, $id_user)
    {

        $unexpired_cookie_exp_time = 2147483647 - time();
        $cookie = array(
            'name'   => 'key_cookie_speciald',
            'value'  => $key,
            'expire' => $unexpired_cookie_exp_time
        );
        $this->input->set_cookie($cookie);
        // $this->user_model->set_key_cookie($id_user, $key);
        // echo "Congragulatio Cookie Set";
    }


        // log the user out
    public function logout()
    {
        $this->session->sess_destroy();
        delete_cookie("key_cookie_speciald");

    }
}
