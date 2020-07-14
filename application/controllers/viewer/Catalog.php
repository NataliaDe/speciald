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

        $this->data['title'] = 'Спец.донесения. Список';

        $filter = [];

        // period for select SD
        $id_range = $this->dones_model->get_range_filter_sd($this->data['active_user']['id_user']);
        $this->data['id_range'] = (isset($id_range['id_range'])) ? $id_range['id_range'] : 0;
        $filter['id_range'] = (isset($id_range['id_range'])) ? $id_range['id_range'] : 0;

        $id_current_user = $this->data['active_user']['id_user'];

        if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {

            /* all  dones of grochs */

            $filter['is_delete'] = 0;

            if (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA])) {

                $this->data['outs'] = $this->dones_model->get_dones_by_grochs($filter, $this->data['active_user']['id_organ']);
            } else {

                $filter['author_local_id'] = $this->data['active_user']['id_local'];
                $filter['without_cp'] = [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA];
                $this->data['outs'] = $this->dones_model->get_dones_by_grochs($filter, FALSE);
            }



            if (isset($this->data['outs']) && !empty($this->data['outs'])) {
                foreach ($this->data['outs'] as $key => $value) {
                    $this->data['outs'][$key]['statuses'] = $this->dones_model->get_statuses_by_id_dones($value['id'], 0, false); //only active statuses
                    //edit after refuse. all levels
                    $this->data['outs'][$key]['dates_actions'] = [];

                    if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                        $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                        foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                            if (!isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) || (isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) && $row['date_action'] > $this->data['outs'][$key]['dates_actions'][$row['id_action']]))
                                $this->data['outs'][$key]['dates_actions'][$row['id_action']] = $row['date_action'];


                            if ($row['id_action'] == 5) {

                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;
                            } else {
                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                            }
                        }
                    }
                }
            }
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {//UMCHS
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

                        //edit after refuse. all levels
                        $this->data['outs'][$key]['dates_actions'] = [];

                        $is_other_refuse = 0;
                        foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                            if (!isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) || (isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) && $row['date_action'] > $this->data['outs'][$key]['dates_actions'][$row['id_action']]))
                                $this->data['outs'][$key]['dates_actions'][$row['id_action']] = $row['date_action'];


                            if ($row['id_action'] == Logs_model::ACTION_REFUSE_SD_UMCHS) {

                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;

                                if ($row['id_user_action'] != $this->data['active_user']['id_user']) {
                                    $is_other_refuse ++;
                                }
                            } else {
                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                            }
                        }

                        if ($is_other_refuse > 0) {
                            $this->data['outs'][$key]['is_other_refuse'] = $is_other_refuse . ' ' . declination_word_by_number($is_other_refuse, array('замечание', 'замечания', 'замечаний'));
                            $this->data['outs'][$key]['is_other_refuse_cnt'] = $is_other_refuse;
                        }
                    }
                }
            }
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {//rcu
            $filter['is_delete'] = 0;
            $this->data['outs'] = $this->dones_model->get_dones_for_rcu($filter, FALSE);

            if (isset($this->data['outs']) && !empty($this->data['outs'])) {
                foreach ($this->data['outs'] as $key => $value) {
                    $this->data['outs'][$key]['statuses'] = $this->dones_model->get_statuses_by_id_dones($value['id'], 0, false); //only active statuses

                    if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                        $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                        //edit after refuse. all levels
                        $this->data['outs'][$key]['dates_actions'] = [];
                        //edit after refuse. action per user
                        $this->data['outs'][$key]['dates_actions_by_user'] = [];


                        $is_other_refuse = 0;
                        foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                            if (!isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) || (isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) && $row['date_action'] > $this->data['outs'][$key]['dates_actions'][$row['id_action']]))
                                $this->data['outs'][$key]['dates_actions'][$row['id_action']] = $row['date_action'];


                            if ($row['id_action'] == Logs_model::ACTION_REFUSE_SD_RCU) {

                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']][$row['id_user_action']] = $row;

                                if ($row['id_user_action'] != $this->data['active_user']['id_user']) {
                                    $is_other_refuse ++;
                                }
                            } else {
                                $this->data['outs'][$key]['statuses_detail'][$row['id_action']] = $row;
                            }
                        }

                        if ($is_other_refuse > 0) {
                            $this->data['outs'][$key]['is_other_refuse'] = $is_other_refuse . ' ' . declination_word_by_number($is_other_refuse, array('замечание', 'замечания', 'замечаний'));
                            $this->data['outs'][$key]['is_other_refuse_cnt'] = $is_other_refuse;
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



                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 0;
                $this->data['outs'][$key]['edit_after_refuse_rcu'] = 0;

                $refuse_date_rcu = isset($this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_REFUSE_SD_RCU]) ? $this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_REFUSE_SD_RCU] : '';
                $refuse_date_umchs = isset($this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_REFUSE_SD_UMCHS]) ? $this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_REFUSE_SD_UMCHS] : '';
                $edit_date = (isset($this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_EDIT_SD])) ? $this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_EDIT_SD] : "";
                $set_number_date = (isset($this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_SET_NUMBER_SD])) ? $this->data['outs'][$key]['dates_actions'][Logs_model::ACTION_SET_NUMBER_SD] : '';


                // if edit was after refuse rcu. for all levels
                if ($edit_date > $refuse_date_rcu) {
                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                } elseif ($set_number_date > $refuse_date_rcu) {
                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                }

                // if edit was after refuse umchs. for all levels
                if ($edit_date > $refuse_date_umchs) {
                    $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                } elseif ($set_number_date > $refuse_date_umchs) {
                    $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                }

                // for level RCU. was or no edit after refuse
                if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {

                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 0;

                    // if edit was after refuse rcu
                    $refuse_date_user = (isset($this->data['outs'][$key]['dates_actions_by_user'][Logs_model::ACTION_REFUSE_SD_RCU][$id_current_user])) ? $this->data['outs'][$key]['dates_actions_by_user'][Logs_model::ACTION_REFUSE_SD_RCU][$id_current_user] : '';

                    if (($edit_date > $refuse_date_user)) {
                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                    } elseif ($set_number_date > $refuse_date_user) {
                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                    } elseif (empty($refuse_date_user) && ($edit_date > $refuse_date_rcu || $set_number_date > $refuse_date_rcu)) {
                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                    }
                }
            }
        }




        if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {

            $this->twig->display('viewer/catalog/grochs/index', $this->data);
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {


            $this->twig->display('viewer/catalog/umchs/index', $this->data);
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {


            $this->twig->display('viewer/catalog/rcu/index', $this->data);
        }
    }
}
