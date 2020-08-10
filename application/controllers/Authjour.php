<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authjour extends CI_Controller
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
    public $id_template=0;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        $this->load->model('user_model');

        $this->load->helper('cookie');
        $this->load->helper(['generate_salt']);
    }

    public function index($id_user_speciald = 0, $id_rig = 0, $type_sd = 'standart',$id_template=0)
    {

        $id_user_speciald = intval($id_user_speciald);
        $id_rig = intval($id_rig);

        $this->id_user_speciald = $id_user_speciald;

        $this->id_rig = $id_rig;
        $this->id_template=$id_template;

        $user_sd = $this->user_model->get_user_by_id($id_user_speciald);

        $this->id_user_journal = $this->user_model->get_user_journal_by_user_sd($id_user_speciald);

        if (isset($user_sd) && !empty($user_sd) && $id_rig != 0 && $id_user_speciald != 0) {

            $this->login();

            if ($type_sd == 'simple'){
                redirect('dones/form_simple/1');

            }
            else
                redirect('dones/form_standart');
        } else {

            redirect($this->session->userdata('role') . '/catalog');
        }
    }

    public function login()
    {
        $user = $this->user_model->get_permissions_by_user_id($this->id_user_speciald);

        $this->session->set_userdata($user);
        $this->session->set_userdata('id_rig', $this->id_rig);
        $this->session->set_userdata('id_user_jour', $this->id_user_journal);

        $this->session->set_userdata('id_template', $this->id_template);


        $remember_me = 1;

        if (isset($remember_me) && $remember_me == 1) {
            $key = generateSalt(); //назовем ее $key
            $this->set_cookie($this->session->userdata('id_user'), $this->session->userdata('id_user')); //????????
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

        //print_r($cookie);exit();
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
