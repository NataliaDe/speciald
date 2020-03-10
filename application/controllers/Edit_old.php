<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends My_Controller
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
            $this->load->model('str_model');
            $this->load->model('ss_model');
            $this->load->model('create_model');

            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();

            $this->data['reasonrig'] = $this->main_model->get_reasonrig();


            // $this->data['active_item_menu'] = 'create';




            $this->load->library('form_validation');

//            //TWIG
//            $this->load->library('twig');
//
//            //$this->twig->addGlobal('sitename', 'My Awesome Site');
//            $this->twig->addGlobal('ORGAN_ID_RCU', 5);
        } else {
            redirect('catalog');
        }
    }

    public function form_standart($id_dones = 0)
    {
        $this->data['title'] = 'Ред. спец.донесение';
        $this->data['is_show_btn_search_rig'] = 1; //show btn "search rig"
        $this->data['bread_crumb'] = array('/' => 'Редактировать специальное донесение', 'ID = ' . $id_dones);


        /* classification */
        $this->data['work_innerservice'] = $this->main_model->get_work_innerservice();
        $this->data['office_belong'] = $this->journal_model->get_officebelong();

        $this->data['object_house'] = $this->main_model->get_object_house();
        $this->data['object_material'] = $this->main_model->get_object_material();
        $this->data['object_roof'] = $this->main_model->get_object_roof();
        $this->data['people_status'] = $this->main_model->get_people_status();
        $this->data['type_water_source'] = $this->main_model->get_type_water_source();
        $this->data['vid_specd'] = $this->main_model->get_vid_specd();
        $this->data['vid_hs_1'] = $this->main_model->get_vid_hs_1();
        $this->data['vid_hs_2'] = $this->main_model->get_vid_hs_2();
        $this->data['innerservice_list'] = $this->main_model->get_innerservice_list();

        $this->data['regions_cp_list'] = $this->ss_model->set_regions_cp_list();


        $this->data['is_edit_dones'] = 1; //sign of edit dones
        $this->data['id_dones'] = $id_dones; //ID of dones

        /* data of edit dones */
        $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);
        $this->data['dones']['silymchs'] = $this->create_model->get_dones_silymchs($id_dones);
        $innerservice= $this->create_model->get_dones_innerservice($id_dones);
        if(isset($innerservice) && !empty($innerservice)){//works of each innerservice row
            foreach ($innerservice as $key => $row) {
                $works=$this->create_model->get_dones_innerservice_work($row['id']);
                $ids_work=(isset($works) && !empty($works)) ? array_column($works, 'id_work_innerservice') : array();
                $innerservice[$key]['works']=$ids_work;
            }
        }
        $this->data['dones']['innerservice'] = $innerservice;


        $this->data['dones']['informing'] = $this->create_model->get_dones_informing($id_dones);
        $this->data['dones']['str'] = $this->create_model->get_dones_str($id_dones);
        $this->data['dones']['str_text'] = $this->create_model->get_dones_str_text($id_dones);
        $this->data['dones']['trunks'] = $this->create_model->get_dones_trunks($id_dones);


        $this->data['dones']['water_source'] = $this->create_model->get_dones_water_source($id_dones);


        $this->data['dones']['object'] = $this->create_model->get_dones_object($id_dones);



        $this->twig->display('create/standart/form_standart', $this->data);
    }
}
