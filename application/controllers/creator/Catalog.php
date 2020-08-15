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

            $this->data['vid_specd'] = $this->main_model->get_vid_specd();

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
        $bd_filter = [];

        $id_current_user = $this->data['active_user']['id_user'];


        if ($this->input->is_ajax_request()) {//filter
            $post = $this->input->post();
            $filter['id_dones'] = $bd_filter['id_dones'] = (isset($post['id_dones']) && !empty($post['id_dones'])) ? intval($post['id_dones']) : '';
            //$filter['date_dones'] = $bd_filter['date_dones'] = (isset($post['date_dones']) && !empty($post['date_dones'])) ? (\DateTime::createFromFormat('d.m.Y', trim($post['date_dones']))->format('Y-m-d')) : '';



            $daterange = (isset($post['date_dones']) && !empty($post['date_dones'])) ? $post['date_dones'] : '';

            if (!empty($post['date_dones'])) {
                $arr_daterange = explode(' - ', $daterange);
                if (isset($arr_daterange) && is_array($arr_daterange)) {

                    $start_date = $arr_daterange[0];
                    if (count($arr_daterange) == 1) {
                        $end_date = $arr_daterange[0];
                    } else
                        $end_date = $arr_daterange[1];
                    $filter['start_date_dones'] = $bd_filter['start_date_dones'] = (isset($start_date) && !empty($start_date)) ? (\DateTime::createFromFormat('d.m.Y', trim($start_date))->format('Y-m-d')) : '';
                    $filter['end_date_dones'] = $bd_filter['end_date_dones'] = (isset($end_date) && !empty($end_date)) ? (\DateTime::createFromFormat('d.m.Y', trim($end_date))->format('Y-m-d')) : '';
                }
            }

            //$filter['date_dones'] = $bd_filter['date_dones'] = (isset($post['date_dones']) && !empty($post['date_dones'])) ? (\DateTime::createFromFormat('d.m.Y', trim($post['date_dones']))->format('Y-m-d')) : '';

            $filter['number_dones'] = $bd_filter['number_dones'] = (isset($post['number_dones']) && !empty($post['number_dones'])) ? $post['number_dones'] : '';
            $filter['address_dones'] = $bd_filter['address_dones'] = (isset($post['address_dones']) && !empty($post['address_dones'])) ? $post['address_dones'] : '';
            $filter['creator_name'] = $bd_filter['creator_name'] = (isset($post['creator_name']) && !empty($post['creator_name'])) ? $post['creator_name'] : '';
            $filter['short_description'] = $bd_filter['short_description'] = (isset($post['short_description']) && !empty($post['short_description'])) ? $post['short_description'] : '';
            $filter['specd_vid'] = $bd_filter['specd_vid'] = (isset($post['specd_vid']) && !empty($post['specd_vid'])) ? intval($post['specd_vid']) : '';
            $filter['status_sd'] = $bd_filter['status_sd'] = (isset($post['status_sd']) && !empty($post['status_sd'])) ? intval($post['status_sd']) : '';

            $filter['is_open_filter'] = $bd_filter['is_open_filter'] = 1;

            $filter_users = $this->main_model->get_filter_by_user($id_current_user);
            if (isset($filter_users['id']) && !empty($filter_users['id'])) {
                //update
                $f_u['value'] = json_encode($bd_filter);
                $f_u['last_update'] = date("Y-m-d H:i:s");
                $this->main_model->edit_filter_user($f_u, $filter_users['id']);
            } else {
                $f_u['value'] = json_encode($bd_filter);
                $f_u['id_user'] = $id_current_user;
                $f_u['date_create'] = $f_u['last_update'] = date("Y-m-d H:i:s");
                $this->main_model->add_filter_user($f_u);
            }
        } else {
            $filter_users = $this->main_model->get_filter_by_user($id_current_user);
            if (!empty($filter_users['value'])) {
                //print_r($filter_users['value']);
                $filter = json_decode($filter_users['value'], TRUE);
            }
        }

        // period for select SD
        $id_range = $this->dones_model->get_range_filter_sd($this->data['active_user']['id_user']);

        //print_r($filter);

        $this->data['id_range'] = $filter['id_range'] = (isset($id_range['id_range'])) ? $id_range['id_range'] : 0;
//print_r($id_range);exit();
        // $filter['id_range'] = (isset($id_range['id_range'])) ? $id_range['id_range'] : 0;






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


                            if ($row['id_action'] == Logs_model::ACTION_REFUSE_SD_UMCHS) {

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
                    //edit after refuse. all levels
                    $this->data['outs'][$key]['dates_actions'] = [];


//if($value['id'] == 10){
//    echo '==';
//                    print_r($this->data['outs'][$key]['statuses']);
//}


                    if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                        $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

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
                    //edit after refuse. all levels
                    $this->data['outs'][$key]['dates_actions'] = [];
                    //edit after refuse. action per user
                    $this->data['outs'][$key]['dates_actions_by_user'] = [];


                    if (isset($this->data['outs'][$key]['statuses']) && !empty($this->data['outs'][$key]['statuses'])) {
                        $this->data['outs'][$key]['statuses_id'] = array_column($this->data['outs'][$key]['statuses'], 'id_action');

                        $is_other_refuse = 0;
                        foreach ($this->data['outs'][$key]['statuses'] as $row) {//detail actions
                            if (!isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) || (isset($this->data['outs'][$key]['dates_actions'][$row['id_action']]) && $row['date_action'] > $this->data['outs'][$key]['dates_actions'][$row['id_action']]))
                                $this->data['outs'][$key]['dates_actions'][$row['id_action']] = $row['date_action'];

                            if (!isset($this->data['outs'][$key]['dates_actions_by_user'][$row['id_action']][$row['id_user_action']]) || (isset($this->data['outs'][$key]['dates_actions_by_user'][$row['id_action']][$row['id_user_action']]) && $row['date_action'] > $this->data['outs'][$key]['dates_actions_by_user'][$row['id_action']][$row['id_user_action']]))
                                $this->data['outs'][$key]['dates_actions_by_user'][$row['id_action']][$row['id_user_action']] = $row['date_action'];




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


        if (isset($this->data['outs']) && !empty($this->data['outs'])) {
            foreach ($this->data['outs'] as $key => $value) {


                if (isset($filter['status_sd']) && !empty($filter['status_sd'])) {
                    if (isset($value['statuses_id']) && !empty($value['statuses_id'])) {
//                        if(in_array($filter['status_sd'], array(1))){
//                            if(in_array(Logs_model::ACTION_PROVE_SD_RCU, $value['statuses_id']) || in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $value['statuses_id']) ||
//                                in_array(Logs_model::ACTION_REFUSE_SD_RCU, $value['statuses_id']) || in_array(Logs_model::ACTION_REFUSE_SD_UMCHS, $value['statuses_id'])){
// unset($this->data['outs'][$key]);
//                            continue;
//
//                                }
//                        }
//
//                        elseif (!in_array($filter['status_sd'], $value['statuses_id'])) {
//                            unset($this->data['outs'][$key]);
//                            continue;
//                        }


                        if (isset($filter['status_sd']) && !empty($filter['status_sd'])) {
                            if (isset($value['statuses_id']) && !empty($value['statuses_id'])) {

                                if ($filter['status_sd'] == 1) {
                                    if (in_array(Logs_model::ACTION_PROVE_SD_RCU, $value['statuses_id']) || in_array(Logs_model::ACTION_REFUSE_SD_RCU, $value['statuses_id'])) {
                                        unset($this->data['outs'][$key]);
                                        continue;
                                    }
                                } elseif ($filter['status_sd'] == 2) {
                                    if ((in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $value['statuses_id']) || in_array(Logs_model::ACTION_REFUSE_SD_UMCHS, $value['statuses_id'])) || in_array($value['author_id_organ'], array(Main_model::ORGAN_ID_AVIA, Main_model::ORGAN_ID_RCU, Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ))) {
                                        unset($this->data['outs'][$key]);
                                        continue;
                                    }
                                } elseif ($filter['status_sd'] == 8) {
                                    if (!in_array($value['author_id_organ'], array(Main_model::ORGAN_ID_AVIA, Main_model::ORGAN_ID_RCU, Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ))) {
                                        unset($this->data['outs'][$key]);
                                        continue;
                                    }
                                } elseif (!in_array($filter['status_sd'], $value['statuses_id'])) {
                                    unset($this->data['outs'][$key]);
                                    continue;
                                }
                            }
                        }
                    }
                }

                //media
                $media = $this->dones_model->get_dones_media($value['id']);
                $this->data['outs'][$key]['media'] = $media;



                 /* sign edit after refuse */

                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 0;
                $this->data['outs'][$key]['edit_after_refuse_rcu'] = 0;

                $date_edit_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_EDIT_SD);
                $date_set_number_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_SET_NUMBER_SD);

                $date_umchs_refuse_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_REFUSE_SD_UMCHS);
                $date_umchs_refresh_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_UPDATE_REFUSE_UMCHS);

                $date_user_refuse_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_REFUSE_SD_RCU);
                $date_user_refresh_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_UPDATE_REFUSE_RCU);


                // if edit was after refuse rcu. for all levels
                if ($this->data['active_user']['level'] != Main_model::LEVEL_ID_RCU) {

                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 0;

                    if (!empty($date_user_refuse_sd)) {
                        if (!empty($date_edit_sd) || !empty($date_set_number_sd)) {
                            if (!empty($date_user_refresh_sd)) {//was refresh rcu user
                                if ($date_edit_sd > $date_user_refresh_sd || $date_set_number_sd > $date_user_refresh_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                }
                            } else {//not was refresh rcu user
                                if ($date_edit_sd > $date_user_refuse_sd || $date_set_number_sd > $date_user_refuse_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                }
                            }
                        }
                    }
                }


                // if edit was after refuse umchs. for all levels
                if (!empty($date_umchs_refuse_sd)) {
                    if (!empty($date_edit_sd) || !empty($date_set_number_sd)) {
                        if (!empty($date_umchs_refresh_sd)) {//was refresh umchs
                            if (!empty($date_edit_sd) && $date_edit_sd > $date_umchs_refresh_sd) {
                                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                            } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_umchs_refresh_sd) {
                                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                            }
                        } else {//not was refresh umchs user
                            if (!empty($date_edit_sd) && $date_edit_sd > $date_umchs_refuse_sd) {
                                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                            } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_umchs_refuse_sd) {
                                $this->data['outs'][$key]['edit_after_refuse_umchs'] = 1;
                            }
                        }
                    }
                }


                // for level RCU. was or no edit after refuse
                if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {

                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 0;
                    $date_user_refuse_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_REFUSE_SD_RCU, $id_current_user);


                    if (!empty($date_user_refuse_sd)) {//was refuse rcu user
                        $date_user_refresh_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_UPDATE_REFUSE_RCU, $id_current_user);

                        if (!empty($date_edit_sd) || !empty($date_set_number_sd)) {
                            if (!empty($date_user_refresh_sd)) {//was refresh rcu user
                                if (!empty($date_edit_sd) && $date_edit_sd > $date_user_refresh_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_user_refresh_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                }
                            } else {//not was refresh rcu user
                                if (!empty($date_edit_sd) && $date_edit_sd > $date_user_refuse_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_user_refuse_sd) {
                                    $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                }
                            }
                        }
                    } else {//not was refuse rcu  user
                        $date_user_refuse_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_REFUSE_SD_RCU);

                        $date_user_refresh_sd = $this->dones_model->date_action_by_id_dones($value['id'], Logs_model::ACTION_UPDATE_REFUSE_RCU);

                        if (!empty($date_user_refuse_sd)) {
                            if (!empty($date_edit_sd) || !empty($date_set_number_sd)) {
                                if (!empty($date_user_refresh_sd)) {//was refresh
                                    if (!empty($date_edit_sd) && $date_edit_sd > $date_user_refresh_sd) {
                                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                    } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_user_refresh_sd) {
                                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                    }
                                } else {//not was refresh
                                    if (!empty($date_edit_sd) && $date_edit_sd > $date_user_refuse_sd) {
                                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                    } elseif (!empty($date_set_number_sd) && $date_set_number_sd > $date_user_refuse_sd) {
                                        $this->data['outs'][$key]['edit_after_refuse_rcu'] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
                /* END sign edit after refuse */
        
        $this->data['filter'] = $filter;


        if ($this->input->is_ajax_request()) {//filter
            if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {



                if (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA])) {

                    echo json_encode([
                        'innerHtml' => $this->twig->render('creator/catalog/grochs/rosn/list', $this->data, true)
                    ]);
                } else {

                    echo json_encode([
                        'innerHtml' => $this->twig->render('creator/catalog/grochs/list', $this->data, true)
                    ]);
                }
            } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {

                echo json_encode([
                    'innerHtml' => $this->twig->render('creator/catalog/umchs/list', $this->data, true)
                ]);
            } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {
                echo json_encode([
                    'innerHtml' => $this->twig->render('creator/catalog/rcu/list', $this->data, true)
                ]);
            }



            die;
        }


        if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {


            $this->twig->display('creator/catalog/grochs/index', $this->data);
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {


            $this->twig->display('creator/catalog/umchs/index', $this->data);
        } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {


            $this->twig->display('creator/catalog/rcu/index', $this->data);
        }
    }

    public function close_filter()
    {

        $post = $this->input->post();
        $id_current_user = $this->data['active_user']['id_user'];

        if (isset($post['is_open'])) {
            $is_open = intval($post['is_open']);
            $filter_users = $this->main_model->get_filter_by_user($id_current_user);
            if (isset($filter_users['id']) && !empty($filter_users['id'])) {
                //update
                $last_value = json_decode($filter_users['value'], TRUE);
                $last_value['is_open_filter'] = $is_open;
                $f_u['value'] = json_encode($last_value);
                $f_u['last_update'] = date("Y-m-d H:i:s");
                //print_r($f_u);
                $this->main_model->edit_filter_user($f_u, $filter_users['id']);
            } else {
                $last_value['is_open_filter'] = $is_open;
                $f_u['value'] = json_encode($last_value);
                $f_u['id_user'] = $id_current_user;
                $f_u['date_create'] = $f_u['last_update'] = date("Y-m-d H:i:s");
                $this->main_model->add_filter_user($f_u);
            }
        }
    }
}
