<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
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
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper(['generate_salt']);

        $this->load->model('user_model');

        //TWIG
        //$this->twig->addGlobal('sitename', 'My Awesome Site');
    }

    public function index()
    {
        $role = $this->session->userdata('role');
        if (isset($role) && !empty($role)) {
            redirect($this->session->userdata('role') . '/catalog');
        } else {

            $this->data['title'] = 'Авторизация';
            $this->twig->display('auth/login', $this->data);
        }
    }

    public function login()
    {

        $login = $this->input->post('login');
        $password = $this->input->post('password');
        $remember_me = $this->input->post('remember_me');

        $user = $this->user_model->get_user_by_login_password($login, $password);

        if (isset($user) && !empty($user)) {

            //start session
            $this->session->set_userdata($user);

            if (isset($remember_me) && $remember_me == 1) {
                $key = generateSalt(); //назовем ее $key
                $this->set_cookie($this->session->userdata('id_user'), $this->session->userdata('id_user'));
            }


            redirect($this->session->userdata('role') . '/catalog');
        } else {
            redirect('auth');
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

        $cookie_from_journal = $this->input->cookie('key_cookie_speciald_from_journal', true);
        if (isset($cookie_from_journal))
            delete_cookie("key_cookie_speciald_from_journal");

        redirect('auth');
    }
}
