<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller
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
//    const actions = [
//        'create_sd' => 1,
//        'edit_sd'   => 2,
//        'delete_sd' => 3,
//        'prove_sd'  => 4,
//        'refuse_sd' => 5,
//    ];

    public function __construct()
    {
        parent::__construct();

        $this->re_login();
        if ($this->session->userdata('can_edit') == 1 && $this->session->userdata('is_admin') == 1 && $this->session->userdata('level') == 1) {
            $this->load->model('user_model');
            $this->load->model('main_model');
            $this->load->model('journal_model');
            $this->load->model('str_model');
            $this->load->model('ss_model');
            $this->load->model('create_model');
            $this->load->model('logs_model');
            $this->load->model('dones_model');

            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();

            $this->data['reasonrig'] = $this->main_model->get_reasonrig();
            $this->data['firereason'] = $this->main_model->get_firereason();
            $this->data['ver_firereason'] = $this->main_model->get_ver_firereason();


            $this->data['id_owner_dead'] = Main_model::ID_OWNER_DEAD;


            $this->load->library('form_validation');

            $this->load->helper('floor_by_number_helper');
            $this->load->helper('declination_helper');
            $this->load->helper('positions_declination');
            $this->load->helper('validate_float');


//            //TWIG
//            $this->load->library('twig');
//
//            //$this->twig->addGlobal('sitename', 'My Awesome Site');
//            $this->twig->addGlobal('ORGAN_ID_RCU', 5);
        } else {
            redirect('auth');
        }
    }

    public function index()
    {

        $this->data['section'] = 'create_spec_d';
        $this->data['title'] = 'Админка';
        $this->data['active_item_menu'] = 'create';

        $this->twig->display('create/index', $this->data);
    }

    public function form_clear_sd()
    {

        $this->data['section'] = 'adminka';
        $this->data['title'] = 'Очистка старых СД';
        $this->data['active_item_menu'] = 'adminka';
        $this->data['bread_crumb'] = array(array('/dones' => 'Панель администрирования'),
            array('Очистка старых СД'));

        $this->data['msg'] = $this->session->flashdata('msg');
        //echo $this->session->flashdata('msg');
        $this->twig->display('admin/clear_sd/form_clear_sd', $this->data);
    }

    public function clear_sd()
    {
        $this->config->load('storage', TRUE);
        $upload_path = $this->config->item('upload_path', 'storage');


        $post = $this->input->post();


        $filter = [];

        $dates_arr = explode(' - ', $post['daterange']);

        $from = trim($dates_arr[0]);
        $to = trim($dates_arr[1]);

        $filter['from'] = \DateTime::createFromFormat('d.m.Y H:i:s', $dates_arr[0])->format('Y-m-d H:i:s');
        $filter['to'] = \DateTime::createFromFormat('d.m.Y H:i:s', $dates_arr[1])->format('Y-m-d H:i:s');

        $filter['is_test_sd'] = 1;

        $sd = $this->dones_model->get_dones_by_period($filter);


        if (!empty($sd)) {
            foreach ($sd as $row) {
                $media = $this->dones_model->get_dones_media($row['id']);

                foreach ($media as $value) {
                    $deleted = '';
                    if ($value['type'] == 'photo')
                        $url = 'sd_photo';
                    elseif ($value['type'] == 'video')
                        $url = 'sd_video';
                    elseif ($value['type'] == 'pdf')
                        $url = 'sd_pdf';
                    elseif ($value['type'] == 'doc')
                        $url = 'sd_doc';
                    elseif ($value['type'] == 'audio')
                        $url = 'sd_audio';

                    $deleted = $upload_path . '/' . $url . '/' . $value['file'];

                    if (file_exists($deleted)) {
                        unlink($deleted);
                    }


                }
            }


            foreach ($sd as $row) {
                $this->dones_model->delete_sd_by_id($row['id']);
            }
        }

        $this->session->set_flashdata('msg','Тестовые СД удалены из БД,');
        redirect('admin/form_clear_sd');
    }
}
