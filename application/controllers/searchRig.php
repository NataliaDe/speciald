<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchRig extends My_Controller
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

        if ($this->session->userdata('can_edit') == 1) {
            $this->load->model('user_model');
            $this->load->model('main_model');
            $this->load->model('journal_model');

            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();

            $this->data['active_item_menu'] = 'create';

            $this->load->library('form_validation');

//            //TWIG
//            $this->load->library('twig');
//
//            //$this->twig->addGlobal('sitename', 'My Awesome Site');
//            $this->twig->addGlobal('ORGAN_ID_RCU', 5);
        } else {
            redirect('creator/catalog');
        }
    }

    public function index()
    {


        if ($this->input->is_ajax_request()) {
            $filter = $this->input->get();

            if (isset($filter) && !empty($filter) && isset($filter['date_msg']) && !empty($filter['date_msg'])) {
                $date = date_create($filter['date_msg']);
                $filter['date_msg'] = date_format($date, 'Y-m-d');
            }


            $this->data['rigs'] = $this->journal_model->serach_rigs($filter);

            echo json_encode([
                'innerHtml' => $this->twig->render('searchRig/result_search_rig', $this->data, true)
            ]);
        }
    }
}
