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

        if ($this->data['active_user']['can_edit'] == 1) {
            $this->load->model('user_model');
            $this->load->model('dones_model');
            $this->load->model('logs_model');

            $this->data['active_item_menu'] = 'catalog';

            $this->load->helper('declination_helper');

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

        $filter = [];

        if ($this->data['active_user']['level'] == 3) {

            /* all  dones of grochs */

                $filter['is_delete']=0;

            if (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA])) {

                $this->data['outs'] = $this->dones_model->get_dones_by_grochs($filter, $this->data['active_user']['id_organ']);
            } else {

                $filter['author_local_id'] = $this->data['active_user']['id_local'];
                $filter['without_cp']=[Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA];
                $this->data['outs'] = $this->dones_model->get_dones_by_grochs($filter, FALSE);
            }



        if (isset($this->data['outs']) && !empty($this->data['outs'])) {
            foreach ($this->data['outs'] as $key => $value) {
                $this->data['outs'][$key]['statuses'] = $this->dones_model->get_statuses_by_id_dones($value['id'], 0, false); //only active statuses

                if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                    $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                    foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                        if ($row['id_action'] == 5) {

                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;

                        } else {
                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                        }
                    }


                }
            }
        }


        }
        elseif ($this->data['active_user']['level'] == 2) {//UMCHS
                $filter['author_region_id'] = $this->data['active_user']['id_region'];
            $filter['is_delete'] = 0;

//            if (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA])) {
//                $this->data['outs'] = $this->dones_model->get_dones_by_region($filter, $this->data['active_user']['id_organ']);
//            } else {

                $filter['without_cp'] = [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA, Main_model::ORGAN_ID_RCU];
                $this->data['outs'] = $this->dones_model->get_dones_by_region($filter, FALSE);
            //}




        if (isset($this->data['outs']) && !empty($this->data['outs'])) {
            foreach ($this->data['outs'] as $key => $value) {
                $this->data['outs'][$key]['statuses'] = $this->dones_model->get_statuses_by_id_dones($value['id'], 0, false); //only active statuses

                if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                    $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                    $is_other_refuse = 0;
                    foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                        if ($row['id_action'] == Logs_model::ACTION_REFUSE_SD_UMCHS) {

                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;

                            if ($row['id_user_action'] != $this->data['active_user']['id_user']) {
                                $is_other_refuse ++;
                            }
                        } else {
                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                        }
                    }

                    if($is_other_refuse > 0){
                        $this->data['outs'][$key]['is_other_refuse']= $is_other_refuse.' '.declination_word_by_number($is_other_refuse, array('замечание', 'замечания', 'замечаний'));
                        $this->data['outs'][$key]['is_other_refuse_cnt']=$is_other_refuse;
                    }
                }
            }
        }
        }
        elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {//rcu
            $filter['is_delete'] = 0;
            $this->data['outs'] = $this->dones_model->get_dones_for_rcu($filter, FALSE);

            if (isset($this->data['outs']) && !empty($this->data['outs'])) {
            foreach ($this->data['outs'] as $key => $value) {
                $this->data['outs'][$key]['statuses'] = $this->dones_model->get_statuses_by_id_dones($value['id'], 0, false); //only active statuses

                if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                    $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                    $is_other_refuse = 0;
                    foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                        if ($row['id_action'] == Logs_model::ACTION_REFUSE_SD_RCU) {

                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;

                            if ($row['id_user_action'] != $this->data['active_user']['id_user']) {
                                $is_other_refuse ++;
                            }
                        } else {
                            $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                        }
                    }

                    if($is_other_refuse > 0){
                        $this->data['outs'][$key]['is_other_refuse']= $is_other_refuse.' '.declination_word_by_number($is_other_refuse, array('замечание', 'замечания', 'замечаний'));
                        $this->data['outs'][$key]['is_other_refuse_cnt']=$is_other_refuse;
                    }
                }
            }
        }
        }

        //media
        if (isset($this->data['outs']) && !empty($this->data['outs'])) {
            foreach ($this->data['outs'] as $key => $value) {

                $media = $this->dones_model->get_dones_media($value['id']);
                $this->data['outs'][$key]['media'] = $media;
            }
        }





        if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {


            $this->twig->display('creator/catalog/grochs/index', $this->data);
        }

        elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS ) {


            $this->twig->display('creator/catalog/umchs/index', $this->data);
        }
        elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU ) {


            $this->twig->display('creator/catalog/rcu/index', $this->data);
        }

    }
}
