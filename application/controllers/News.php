<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends My_Controller
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

        //$this->re_login();

        $this->load->model('user_model');
        $this->load->model('dones_model');
        $this->load->model('logs_model');
        $this->load->model('create_model');

        $this->data['active_item_menu'] = 'news';


        //TWIG
        $this->load->library('twig');
        //$this->twig->addGlobal('sitename', 'My Awesome Site');
    }

    public function index()
    {
        $this->data['title'] = 'Новости';

        $id_user = $this->session->userdata('id_user');
        if (isset($id_user) && !empty($id_user)) {
            $this->data['sidebar'] = $this->session->userdata('role');
            $this->data['active_user'] = $this->session->userdata;

            $this->twig->display('news/index', $this->data);
        } else {
            $this->twig->display('news/auth/index', $this->data);
        }
    }
}
