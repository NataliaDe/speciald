<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog extends My_Controller
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

        $this->re_login();

        if ($this->data['active_user']['can_edit'] == 0) {
            $this->load->model('user_model');

            $this->data['active_item_menu'] = 'catalog';

            //TWIG
            //$this->load->library('twig');
            //$this->twig->addGlobal('sitename', 'My Awesome Site');
        } else {
            redirect('auth');
        }
    }

    public function index()
    {
        // print_r($this->session->userdata);
        $this->data['title'] = 'Спец.донесения. Список';
        $this->twig->display('catalog/index', $this->data);
    }
}
