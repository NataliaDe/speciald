<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends My_Controller
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

        $this->load->model('user_model');
        $this->load->model('dones_model');
        $this->load->model('logs_model');

        $this->data['active_item_menu'] ='catalog';


        //TWIG
        //$this->load->library('twig');

        //$this->twig->addGlobal('sitename', 'My Awesome Site');


    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {

            $id_dones = $this->input->post('sd_id');
            $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 3, false);//all actions


            if (isset($statuses) && !empty($statuses)) {

                echo json_encode([
                    'result' => $this->twig->render('history/catalog/history_by_id', [
                        'statuses' => $statuses
                    ]),
                    'success'=>1
                ]);
            } else {
                   echo json_encode([
                    'error' => 'Информация отсутствует'
                ]);
                //echo json_encode(array('error' => 'Информация отсутствует'));
            }
        }
    }


        public function detail_refuse_sd()
    {
        if ($this->input->is_ajax_request()) {

            $id_dones = $this->input->post('sd_id');
            $level = $this->input->post('level');

            if($level == 2){//umchs modal
$statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, Logs_model::ACTION_REFUSE_SD_UMCHS );//refuse action
            }
            elseif($level == 1){//rcu
$statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, Logs_model::ACTION_REFUSE_SD_RCU );//refuse action
            }




            if (isset($statuses) && !empty($statuses)) {

                echo json_encode([
                    'result' => $this->twig->render('history/catalog/detail_refuse_sd', [
                        'statuses' => $statuses,
                        'data'=> $this->data
                    ]),
                    'success'=>1
                ]);
            } else {
                echo json_encode(array('error' => 'Информация отсутствует'));
            }
        }
    }
}
