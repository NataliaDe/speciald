<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dones extends My_Controller
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

        $uri = $this->uri->segment('2');


        if ($this->session->userdata('can_edit') == 1 ||
            ($this->session->userdata('can_edit') == 0 && $uri == 'edit_form_standart') ||
            ($this->session->userdata('can_edit') == 0 && $uri == 'edit_form_simple')) {
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





            $this->load->library('form_validation');

            $this->load->helper('floor_by_number_helper');

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
        $this->data['title'] = 'Создать спец.донесение';
        $this->data['active_item_menu'] = 'create';
        $this->twig->display('create/index', $this->data);
    }

    //form standart
    public function form_standart($is_default = 0)
    {

//         $cookie_from_journal = $this->input->cookie('key_cookie_speciald_from_journal', true);
//         $cookie_from = $this->input->cookie('key_cookie_speciald', true);
//         echo $cookie_from_journal;
//         echo $cookie_from;
//         exit();
//        $ids_pasp=array(525,529);
//         print_r($this->getStrByIdsPasp(array_unique($ids_pasp)));
//         exit();

        $this->data['type_sd'] = Main_model::TYPE_SD_STANDART;
        $this->data['title'] = 'Новое спец.донесение';
        $this->data['active_item_menu'] = 'create';
        $this->data['active_item_menu_type_create'] = 'standart';
        $this->data['is_show_btn_search_rig'] = 1; //show btn "search rig"

        $this->data['bread_crumb'] = array(array('/dones' => 'Создать специальное донесение'),
            array('Стандартное'));


        /* settings */
        $this->data['settings'] = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $this->data['settings'] = $this->user_model->get_user_settings_options_format($this->data['settings']);


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

        $this->data['face_belong'] = $this->main_model->get_face_belong();
        $this->data['owner_categories'] = $this->journal_model->get_owner_categories();

        $this->data['api_source'] = $this->main_model->get_api_source();

        /* default number sd */
        $request_region = $this->data['active_user']['id_region'];
        if ($this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_ROSN || $this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_UGZ ||
            $this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_AVIA) {
            $request_region = $this->data['active_user']['id_organ'];

            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_organ($request_region);
        } elseif ($this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_RCU) {
            $request_region = Main_model::REGION_ID_RCU;
            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_organ(Main_model::ORGAN_ID_RCU);
        } else {
            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_region($request_region);
        }

        if (!isset($cnt_dones_per_region) || empty($cnt_dones_per_region)) {
            $cnt_dones_per_region = 1;
        }

        $this->data['first_part_number_sd'] = $this->main_model->get_first_part_number_sd($request_region);
        $all_cnt_dones = $this->dones_model->get_cnt_dones();
        if (!isset($all_cnt_dones) || empty($all_cnt_dones)) {
            $all_cnt_dones = 1;
        }

        $this->data['default_number_sd'] = $this->data['first_part_number_sd'] . '/' . $all_cnt_dones . '/' . $cnt_dones_per_region;
        /* END default number sd */





        if ($is_default == 1) {//show empty form
        } elseif ($is_default == 2 && $this->input->is_ajax_request()) {//get data from rig and fill form
            $id_rig = $this->input->get('id_rig');
        }
        // auto from journal rig
        elseif (isset($this->data['active_user']['id_rig']) && $this->data['active_user']['id_rig'] != 0) {
            $id_rig = $this->data['active_user']['id_rig'];
        } else {

        }


        if (isset($id_rig) && !empty($id_rig)) {
            $this->data['id_rig_current'] = $id_rig;
            $this->data['rig'] = $this->journal_model->get_rig_by_id($id_rig);
            $this->data['rig']['reasonrig_name'] = trim(stristr($this->data['rig']['reasonrig_name'], ' '));
            $this->data['rig']['people'] = $this->journal_model->get_people_by_rig_id($id_rig);
            //$this->data['rig']['silymchs'] = $this->journal_model->get_silymchs_by_rig_id($id_rig);
            $this->data['rig']['silymchs'] = $this->journal_model->get_silymchs_by_rig_id_sort_distance($id_rig);


//            if (isset($this->data['rig']['silymchs']) && !empty($this->data['rig']['silymchs'])) {
//
//                $is_min_br = 0;
//
//                foreach ($this->data['rig']['silymchs'] as $key => $value) {
//
//                    if (isset($value['pasp_id']) && !empty($value['pasp_id']))
//                        $ids_pasp[] = $value['pasp_id']; // ids pasp of each car
//
//
//                    if (!empty($value['id_teh']) && $value['id_teh'] != NULL) {//isset last dateduty car ?
//                        if ($this->str_model->is_last_dateduty_car($value['id_teh']) > 0) {
//                            $man_per_car = $this->str_model->get_man_per_car($value['id_teh']);
//                            $man_per_car_id[$value['id_teh']] = (isset($man_per_car) ) ? $man_per_car : $value['min_br'];
//                        } else {
//                            $is_min_br++;
//                            $this->data['rig']['silymchs'][$key]['man_per_car_note'] = 'указан мин.б.р.';
//                        }
//                    } else {
//                        $is_min_br++;
//                        $this->data['rig']['silymchs'][$key]['man_per_car_note'] = 'указан мин.б.р.';
//                    }
//
//                    /* cnt man on each car. If not isset row in str.s_man_per_car, then take min br of car  */
//                    $this->data['rig']['silymchs'][$key]['man_per_car'] = (isset($man_per_car) ) ? $man_per_car : $value['min_br'];
//                }
//
//                if ($is_min_br > 0)
//                    $this->data['rig']['man_per_car_note'] = 'указан мин.б.р.';
//            }

            $man_per_car_id = array();
            if (isset($this->data['rig']['silymchs']) && !empty($this->data['rig']['silymchs'])) {
//print_r($this->data['rig']['silymchs']);
                foreach ($this->data['rig']['silymchs'] as $key => $value) {

                    if (isset($value['pasp_id']) && !empty($value['pasp_id']) && $value['pasp_id'] != null)
                        $ids_pasp[] = $value['pasp_id']; // ids pasp of each car
                }
            }

            /* get data from str */
            if (isset($ids_pasp) && !empty($ids_pasp)) {
                $this->data['str']['table'] = $this->getStrByIdsPasp(array_unique($ids_pasp)); //data for table: shtat, vacant...
            }

            if (isset($this->data['str']['table']) && !empty($this->data['str']['table'])) {
                foreach ($this->data['str']['table'] as $value) {
                    /* get last ch of each card */
                    if (isset($value['ch']) && !empty($value['ch']) && isset($value['id_card']) && !empty($value['id_card'])) {
                        $last_ch_by_card[$value['id_card']] = $value['ch'];
                    }
                }
            }

            if (isset($this->data['rig']['silymchs']) && !empty($this->data['rig']['silymchs'])) {

                $is_min_br = 0;

                foreach ($this->data['rig']['silymchs'] as $key => $value) {

                    if (isset($last_ch_by_card[$value['pasp_id']]) && isset($value['id_teh']) && !empty($value['id_teh']) && $value['id_teh'] != null) {

                        $result = $this->str_model->get_man_per_car_by_ch($value['id_teh'], $last_ch_by_card[$value['pasp_id']]);

                        if ((isset($result['is_result']) && $result['is_result'] > 0)) {
                            $man_per_car = $result['cnt_man'];
                            $man_per_car_id[$value['id_teh']] = (isset($man_per_car) ) ? $man_per_car : $value['min_br'];
                        } else {
                            $is_min_br++;
                            $this->data['rig']['silymchs'][$key]['man_per_car_note'] = 'указан мин.б.р.';
                        }
                    } else {
                        $is_min_br++;
                        $this->data['rig']['silymchs'][$key]['man_per_car_note'] = 'указан мин.б.р.';
                    }

                    /* cnt man on each car. If not isset row in str.s_man_per_car, then take min br of car  */
                    $this->data['rig']['silymchs'][$key]['man_per_car'] = (isset($man_per_car) ) ? $man_per_car : $value['min_br'];
                }

                if ($is_min_br > 0)
                    $this->data['rig']['man_per_car_note'] = 'указан мин.б.р.';
            }


            $this->data['rig']['innerservice'] = $this->journal_model->get_innerservice_by_rig_id($id_rig);
            $this->data['rig']['informing'] = $this->journal_model->get_informing_by_rig_id($id_rig);

            /* get data about journal trunks */
            $this->data['rig']['trunks'] = $this->getTrunksByIdRig($id_rig, $man_per_car_id); //data for trunks
        }

        if ($is_default == 1) {//show empty form
        } elseif ($is_default == 2 && $this->input->is_ajax_request()) {//get data from rig and fill form
            if (isset($this->data['rig']) && !empty($this->data['rig'])) {
                $is_data = 1;
            } else {
                $is_data = 0;
            }

            echo json_encode([
                'opening_block'   => $this->twig->render('create/standart/opening_block', $this->data, true),
                'middle_block'    => $this->twig->render('create/standart/middle_block', $this->data, true),
                'silymchs'        => $this->twig->render('create/standart/middle-block/silymchs', $this->data, true),
                'innerservice'    => $this->twig->render('create/standart/middle-block/innerservice', $this->data, true),
                'informing'       => $this->twig->render('create/standart/middle-block/informing', $this->data, true),
                'final_block'     => $this->twig->render('create/standart/final_block', $this->data, true),
                'str_block'       => $this->twig->render('create/standart/middle-block/str', $this->data, true),
                'trunks_block'    => $this->twig->render('create/standart/middle-block/trunks', $this->data, true),
                'object_data'     => $this->twig->render('create/standart/parts/object_data', $this->data, true),
                'object_floor'    => $this->twig->render('create/standart/parts/object_floor', $this->data, true),
                'people_rig_data' => $this->twig->render('create/standart/parts/people_rig_data', $this->data, true),
                'law_face_office_belong'     => $this->twig->render('create/standart/owner/parts/law_face_office_belong', $this->data, true),
                'owner_from_jour'     => $this->twig->render('create/standart/owner/parts/owner_from_jour', $this->data, true),
                'is_data'         => $is_data,
                'id_face_belong'=> (($this->data['rig']['id_owner_category'] != 0 || !empty($this->data['rig']['owner_fio'])) ? 1 : 0)
            ]);
            die;
        }
        // auto from journal rig
        elseif (isset($this->data['active_user']['id_rig']) && $this->data['active_user']['id_rig'] != 0) {

        } else {

        }

        $this->twig->display('create/standart/form_standart', $this->data);
    }

    //form simple
    public function form_simple()
    {

        $this->data['type_sd'] = Main_model::TYPE_SD_SIMPLE;
        $this->data['title'] = 'Новое спец.донесение (простое)';
        $this->data['active_item_menu'] = 'create';
        $this->data['is_show_btn_search_rig'] = 0; //don't show btn "search rig"

        $this->data['bread_crumb'] = array(array('/dones' => 'Создать специальное донесение'),
            array('Простое'));

                /* settings */
        $this->data['settings'] = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $this->data['settings'] = $this->user_model->get_user_settings_options_format($this->data['settings']);


        $this->data['vid_specd'] = $this->main_model->get_vid_specd();
        $this->data['minirovanie_id']= Main_model::VID_SD_MINIROVANIE;


                /* default number sd */
        $request_region = $this->data['active_user']['id_region'];
        if ($this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_ROSN || $this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_UGZ ||
            $this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_AVIA) {
            $request_region = $this->data['active_user']['id_organ'];

            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_organ($request_region);
        } elseif ($this->data['active_user']['id_organ'] == Main_model::ORGAN_ID_RCU) {
            $request_region = Main_model::REGION_ID_RCU;
            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_organ(Main_model::ORGAN_ID_RCU);
        } else {
            $cnt_dones_per_region = $this->dones_model->get_cnt_dones_per_region($request_region);
        }

        if (!isset($cnt_dones_per_region) || empty($cnt_dones_per_region)) {
            $cnt_dones_per_region = 1;
        }

        $this->data['first_part_number_sd'] = $this->main_model->get_first_part_number_sd($request_region);
        $all_cnt_dones = $this->dones_model->get_cnt_dones();
        if (!isset($all_cnt_dones) || empty($all_cnt_dones)) {
            $all_cnt_dones = 1;
        }

        $this->data['default_number_sd'] = $this->data['first_part_number_sd'] . '/' . $all_cnt_dones . '/' . $cnt_dones_per_region;
        /* END default number sd */

        $this->twig->display('create/simple/form_simple', $this->data);
    }

    public function getStrByIdsPasp($ids_pasp, $dateduty_manual = null)
    {

        $diviz_organ_of_pasp = $this->str_model->get_inf_by_id_pasp($ids_pasp);

        foreach ($diviz_organ_of_pasp as $key => $row) {

            $str_table[$row['id_pasp']] = $row;

            if ($row['diviz_id'] == 8 || $row['id_organ'] == 5) {//cou or rcu
                /* ch,dateduty, id_card....From table str.maincou */
                if ($row['diviz_id'] == 8) {

                    if ($dateduty_manual != NULL) {//get data by dateduty
                        $is_main = $this->str_model->get_maincou_by_id_pasp_and_dateduty($row['id_pasp'], $dateduty_manual);

                        if (isset($is_main) && !empty($is_main))
                            $diviz_organ_of_pasp[$key] = array_merge($row, $is_main);
                        else
                            $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_maincou_by_id_pasp($row['id_pasp'])); //cou last
                    }
                    else {//get data by last duty ch
                        $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_maincou_by_id_pasp($row['id_pasp'])); //cou
                    }
                } elseif ($row['id_organ'] == 5) {

                    if ($dateduty_manual != NULL) {//get data by dateduty
                        $is_main = $this->str_model->get_mainrcu_by_id_pasp_and_dateduty($row['id_pasp'], $dateduty_manual);

                        if (isset($is_main) && !empty($is_main))
                            $diviz_organ_of_pasp[$key] = array_merge($row, $is_main);
                        else
                            $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_mainrcu_by_id_pasp($row['id_pasp'])); //rcu
                    }
                    else {//get data by last duty ch
                        $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_mainrcu_by_id_pasp($row['id_pasp'])); //rcu
                    }

                }

                $current_ch = $diviz_organ_of_pasp[$key]['ch'];
                $current_dateduty = $diviz_organ_of_pasp[$key]['dateduty'];

                /* listls, vacant in duty ch */
                $diviz_organ_of_pasp[$key]['vacant_ch'] = $this->str_model->get_vacant_in_ch_cou($row['id_pasp'], $current_ch);
                $diviz_organ_of_pasp[$key]['on_list_ch'] = $this->str_model->get_listls_in_ch_cou($row['id_pasp'], $current_ch);
                //$diviz_organ_of_pasp[$key]['shtat_ch'] = $this->str_model->get_shtat_in_ch_cou($row['id_pasp'], $current_ch);

                /*  face = all on fields in cou str */
                if ($row['id_organ'] == 8) {
                    $maincou_all_row = $this->str_model->get_all_in_ch_cou($row['id_pasp'], $current_ch);
                } elseif ($row['id_organ'] == 5) {
                    $maincou_all_row = $this->str_model->get_all_in_ch_rcu($row['id_pasp'], $current_ch);
                }

                $count_all = array(); //all select on position - id of fio
                if (!empty($maincou_all_row)) {
                    foreach ($maincou_all_row as $face) {

                        if (!empty($face['id_fio'])) {
                            $count_all[] = $face['id_fio'];
                        }
                    }
                }
                $count_all_unique = array_unique($count_all);
                $face_cou = count($count_all_unique);

                $diviz_organ_of_pasp[$key]['face_ch'] = $face_cou;


                /* br */
                $is_car = $this->str_model->is_car_in_ch_cou($row['id_pasp'], $current_ch);
                if ($is_car > 0)
                    $count_fio_on_car = 0;
                else {
                    $count_fio_on_car = $this->str_model->br_in_pasp_on_date($row['id_pasp'], $current_dateduty);
                }

                $diviz_organ_of_pasp[$key]['br_ch'] = $count_fio_on_car;
            } else {

                /* listls, face, br of  ch....From table str.main */
                if ($dateduty_manual != NULL) {//get data by dateduty
                    $is_main = $this->str_model->get_main_by_id_pasp_and_dateduty($row['id_pasp'], $dateduty_manual);

                    if (isset($is_main) && !empty($is_main))
                        $diviz_organ_of_pasp[$key] = array_merge($row, $is_main);
                    else
                        $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_main_by_id_pasp($row['id_pasp']));
                }
                else {//get data by last duty ch
                    $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_main_by_id_pasp($row['id_pasp']));
                }
            }



            /* shtat, vacant of all pasp */
            $diviz_organ_of_pasp[$key]['shtat'] = $this->str_model->get_shtat_of_pasp_by_id_pasp($row['id_pasp']);
            $diviz_organ_of_pasp[$key]['vacant'] = $this->str_model->get_vacant_of_pasp_by_id_pasp($row['id_pasp']);


            /* trip,hol,ill,other on dateduty */
            if (isset($diviz_organ_of_pasp[$key]['dateduty']) && !empty($diviz_organ_of_pasp[$key]['dateduty'])) {

                /* man in trip */
                $diviz_organ_of_pasp[$key]['trip_man'] = $this->str_model->get_cnt_trip_by_id_pasp($row['id_pasp'], $diviz_organ_of_pasp[$key]['dateduty'], $diviz_organ_of_pasp[$key]['ch']);
                $diviz_organ_of_pasp[$key]['cnt_trip_man'] = count($diviz_organ_of_pasp[$key]['trip_man']);

                if (!empty($diviz_organ_of_pasp[$key]['trip_man'])) {
                    foreach ($diviz_organ_of_pasp[$key]['trip_man'] as $trip) {

                        if (!empty($trip['fio'])) {
                            $inf = '1 чел. ';
                            $inf = $inf . '(' . mb_strtolower($trip['position']) . ' ' . $trip['fio'] . ') ';
                            $inf = $inf . '- командировка с ' . $trip['date1'] . ((!empty($trip['date2']) && $trip['date2'] != NULL && $trip['date2'] != '00.00.0000') ? (' по ' . $trip['date2']) : '');
                            $inf = $inf . ((!empty($trip['place'])) ? (', ' . $trip['place']) : '');
                            $inf = $inf . ((!empty($trip['prikaz'])) ? (' (' . $trip['prikaz'] . ')') : '');


                            if (isset($inf) && !empty($inf))
                                $diviz_organ_of_pasp[$key]['trip_man_row'][] = $inf;
                        }
                    }
                }

                /* man on holiday */
                $diviz_organ_of_pasp[$key]['holiday_man'] = $this->str_model->get_cnt_holiday_by_id_pasp($row['id_pasp'], $diviz_organ_of_pasp[$key]['dateduty'], $diviz_organ_of_pasp[$key]['ch']);
                $diviz_organ_of_pasp[$key]['cnt_holiday_man'] = count($diviz_organ_of_pasp[$key]['holiday_man']);

                if (!empty($diviz_organ_of_pasp[$key]['holiday_man'])) {
                    foreach ($diviz_organ_of_pasp[$key]['holiday_man'] as $hol) {

                        if (!empty($hol['fio'])) {
                            $inf = '1 чел. ';
                            $inf = $inf . '(' . mb_strtolower($hol['position']) . ' ' . $hol['fio'] . ') ';
                            $inf = $inf . '- отпуск с ' . $hol['date1'] . ((!empty($hol['date2']) && $hol['date2'] != NULL && $hol['date2'] != '00.00.0000') ? (' по ' . $hol['date2']) : '');
                            $inf = $inf . ((!empty($hol['prikaz'])) ? (' (' . $hol['prikaz'] . ')') : '');


                            if (isset($inf) && !empty($inf))
                                $diviz_organ_of_pasp[$key]['holiday_man_row'][] = $inf;
                        }
                    }
                }

                /* man ill */
                $diviz_organ_of_pasp[$key]['ill_man'] = $this->str_model->get_cnt_ill_by_id_pasp($row['id_pasp'], $diviz_organ_of_pasp[$key]['dateduty'], $diviz_organ_of_pasp[$key]['ch']);
                $diviz_organ_of_pasp[$key]['cnt_ill_man'] = count($diviz_organ_of_pasp[$key]['ill_man']);


                if (!empty($diviz_organ_of_pasp[$key]['ill_man'])) {
                    foreach ($diviz_organ_of_pasp[$key]['ill_man'] as $ill) {

                        if (!empty($ill['fio'])) {
                            $inf = '1 чел. ';
                            $inf = $inf . '(' . mb_strtolower($ill['position']) . ' ' . $ill['fio'] . ') ';
                            $inf = $inf . '- болен с ' . $ill['date1'] . ((!empty($ill['date2']) && $ill['date2'] != NULL && $ill['date2'] != '00.00.0000') ? (' по ' . $ill['date2']) : '');
                            $inf = $inf . ((!empty($ill['diagnosis'])) ? (' (' . $ill['diagnosis'] . ')') : '');


                            if (isset($inf) && !empty($inf))
                                $diviz_organ_of_pasp[$key]['ill_man_row'][] = $inf;
                        }
                    }
                }



                /* man other */
                $diviz_organ_of_pasp[$key]['other_man'] = $this->str_model->get_cnt_other_by_id_pasp($row['id_pasp'], $diviz_organ_of_pasp[$key]['dateduty'], $diviz_organ_of_pasp[$key]['ch']);
                $diviz_organ_of_pasp[$key]['cnt_other_man'] = count($diviz_organ_of_pasp[$key]['other_man']);
                $inf = '';
                if (!empty($diviz_organ_of_pasp[$key]['other_man'])) {
                    foreach ($diviz_organ_of_pasp[$key]['other_man'] as $other) {

                        if (!empty($other['fio'])) {
                            $inf = '1 чел. ';
                            $inf = $inf . '(' . mb_strtolower($other['position']) . ' ' . $other['fio'] . ') ';
                            $inf = $inf . '- другие причины с ' . $other['date1'] . ((!empty($other['date2']) && $other['date2'] != NULL && $other['date2'] != '00.00.0000') ? (' по ' . $other['date2']) : '');
                            $inf = $inf . ((!empty($other['reason'])) ? (', ' . $other['reason']) : '');
                            $inf = $inf . ((!empty($other['note'])) ? ( '(' . mb_strtolower(mb_substr($other['note'], 0, 1)) . mb_substr($other['note'], 1) . ') ' ) : '');


                            if (isset($inf) && !empty($inf))
                                $diviz_organ_of_pasp[$key]['other_man_row'][] = $inf;
                        }
                    }
                }

                /* vacant inf in ch */
                $diviz_organ_of_pasp[$key]['vacant_inf'] = $this->str_model->get_cnt_vacant_inf_by_id_pasp($row['id_pasp'], $diviz_organ_of_pasp[$key]['ch']);

                if (!empty($diviz_organ_of_pasp[$key]['vacant_inf'])) {
                    foreach ($diviz_organ_of_pasp[$key]['vacant_inf'] as $vac) {

                        if (!empty($vac['position'])) {
                            $inf = '1 чел. ';
                            $inf = $inf . '(' . mb_strtolower($vac['position']) . ') ';
                            $inf = $inf . '- вакансия ';



                            if (isset($inf) && !empty($inf))
                                $diviz_organ_of_pasp[$key]['vacant_man_row'][] = $inf;
                        }
                    }
                }


                /* naryad inf */
                if (!empty($diviz_organ_of_pasp[$key]['fio_duty'])) {

                    $inf = $diviz_organ_of_pasp[$key]['fio_duty'];
                    $inf = $inf . ' - наряд ';

                    if (isset($inf) && !empty($inf))
                        $diviz_organ_of_pasp[$key]['naryad_man_row'][] = $inf;
                }



                $diviz_organ_of_pasp[$key]['non_available'] = array_merge((isset($diviz_organ_of_pasp[$key]['trip_man_row']) ? $diviz_organ_of_pasp[$key]['trip_man_row'] : array()), (isset($diviz_organ_of_pasp[$key]['holiday_man_row']) ? $diviz_organ_of_pasp[$key]['holiday_man_row'] : array()), (isset($diviz_organ_of_pasp[$key]['ill_man_row']) ? $diviz_organ_of_pasp[$key]['ill_man_row'] : array()), (isset($diviz_organ_of_pasp[$key]['other_man_row']) ? $diviz_organ_of_pasp[$key]['other_man_row'] : array()), (isset($diviz_organ_of_pasp[$key]['vacant_man_row']) ? $diviz_organ_of_pasp[$key]['vacant_man_row'] : array()), (isset($diviz_organ_of_pasp[$key]['naryad_man_row']) ? $diviz_organ_of_pasp[$key]['naryad_man_row'] : array()));
            }
        }

        return $diviz_organ_of_pasp;
    }
    /* get trunks from journal */

    public function getTrunksByIdRig($id_rig, $man_per_car_id)
    {

        //$trunks = $this->journal_model->get_trunks_by_id_rig($id_rig); //table trunks
        $trunks = $this->journal_model->get_trunks_by_id_rig_sort_distance($id_rig); //table trunks



        if (!empty($man_per_car_id) && !empty($trunks)) {//man per car
            foreach ($trunks as $key => $value) {

                if (!empty($value['id_teh']) && $value['id_teh'] != NULL && isset($man_per_car_id[$value['id_teh']])) {//man per car from str
                    $trunks[$key]['man_per_car'] = $man_per_car_id[$value['id_teh']];
                } else {// man per car = min br from kusis
                    $trunks[$key]['man_per_car'] = $value['min_br'];
                    $trunks[$key]['man_per_car_note'] = 'указан мин.б.р.';
                }
            }
        }

        return $trunks;
    }

    public function add_work_innerservice_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_work = $this->journal_model->is_work_innerservice($name);

        if ($is_work == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->journal_model->add_work_innerservice($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'вид был успешно добавлен в БД',
                "work_name"  => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_work_innerservice_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_work = $this->journal_model->is_work_innerservice($name);

        if ($is_work == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->journal_model->edit_work_innerservice_by_id($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "work_name"  => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_work_innerservice_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->journal_model->delete_work_innerservice_by_id($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function add_object_house_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_vid = $this->main_model->is_object_house($name);

        if ($is_vid == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->main_model->add_object_house($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'Вид был успешно добавлен в БД',
                "name"       => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_object_house_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_vid = $this->main_model->is_object_house($name);

        if ($is_vid == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_house($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "name"       => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_object_house_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_house($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function add_object_material_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_vid = $this->main_model->is_object_material($name);

        if ($is_vid == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->main_model->add_object_material($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'Вид был успешно добавлен в БД',
                "name"       => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_object_material_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_vid = $this->main_model->is_object_material($name);

        if ($is_vid == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_material($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "name"       => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_object_material_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_material($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function add_object_roof_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_vid = $this->main_model->is_object_roof($name);

        if ($is_vid == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->main_model->add_object_roof($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'Вид был успешно добавлен в БД',
                "name"       => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_object_roof_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_vid = $this->main_model->is_object_roof($name);

        if ($is_vid == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_roof($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "name"       => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_object_roof_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_object_roof($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function add_people_status_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_vid = $this->main_model->is_people_status($name);

        if ($is_vid == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->main_model->add_people_status($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'Вид был успешно добавлен в БД',
                "name"       => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_people_status_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_vid = $this->main_model->is_people_status($name);

        if ($is_vid == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_people_status($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "name"       => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_people_status_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_people_status($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function add_water_source_ajax()
    {
        $name = $this->input->post('name');
        //$name = urldecode($name);
        $is_vid = $this->main_model->is_water_source($name);

        if ($is_vid == 0 && $name != '') {

            $save['name'] = $name;
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['created_by'] = $this->data['active_user']['id_user'];
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['date_insert'] = date("Y-m-d H:i:s");
            $save['last_update'] = date("Y-m-d H:i:s");


            $id = $this->main_model->add_water_source($save);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id,
                'message'    => 'Вид был успешно добавлен в БД',
                "name"       => $name
                //'removeTagsForm' => getRemoveTagsForm()
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был добавлен, т.к. уже существует в БД'
            ]);
        }
    }

    public function edit_water_source_ajax()
    {
        $name = $this->input->post('name');
        $id_edit = $this->input->post('id_edit');
        $is_vid = $this->main_model->is_water_source($name);

        if ($is_vid == 0 && $name != '' && !empty($id_edit)) {

            $save['name'] = $name;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_water_source($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'id'         => $id_edit,
                'message'    => 'вид был успешно обновлен в БД',
                "name"       => $name
            ]);
        } elseif ($name == '') {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Наименование не может быть пустым'
            ]);
        } else {
            echo json_encode([
                'is_success' => 0,
                'message'    => 'Вид не был обновлен, т.к. вид с таким именем уже существует в БД'
            ]);
        }
    }

    public function delete_water_source_ajax()
    {
        $id_edit = $this->input->post('id');

        if (!empty($id_edit)) {

            $save['is_delete'] = 1;
            $save['updated_by'] = $this->data['active_user']['id_user'];
            $save['last_update'] = date("Y-m-d H:i:s");

            $this->main_model->edit_water_source($save, $id_edit);

            echo json_encode([
                'is_success' => 1,
                'message'    => 'вид был успешно удален в БД'
            ]);
        }
    }

    public function get_grochs_by_region()
    {

        $ids_region = $this->input->post('ids_region');
        $grochs_by_region = $this->str_model->get_locorg_cp_list($ids_region);

        if (!empty($grochs_by_region)) {
            echo json_encode($grochs_by_region);
        }
    }

    public function get_pasp_by_locorg()
    {

        $ids_locorg = $this->input->post('ids_locorg');
        $pasp_by_locorg = $this->str_model->get_pasp_cp_list($ids_locorg);

        if (!empty($pasp_by_locorg)) {
            echo json_encode($pasp_by_locorg);
        }
    }

    public function get_str_cars_by_pasp()
    {

        $ids_pasp = $this->input->post('ids_pasp');

        if (isset($ids_pasp) && !empty($ids_pasp)) {

            $diviz_organ_of_pasp = $this->str_model->get_inf_by_id_pasp($ids_pasp);
//print_r($diviz_organ_of_pasp);
            foreach ($diviz_organ_of_pasp as $key => $row) {

                if ($row['diviz_id'] == 8 || $row['id_organ'] == 5) {//cou or rcu
                    /* ch,dateduty, id_card....From table str.maincou */
                    if ($row['diviz_id'] == 8) {
                        $main = $this->str_model->get_maincou_by_id_pasp($row['id_pasp']); //cou
                    } elseif ($row['id_organ'] == 5) {
                        $main = $this->str_model->get_mainrcu_by_id_pasp($row['id_pasp']); //rcu
                    }
                } else {
                    $main = $this->str_model->get_main_by_id_pasp($row['id_pasp']); //rcu
                }

                if (isset($main) && !empty($main)) {
                    $current_ch = $main['ch'];
                    $current_dateduty = $main['dateduty'];
                } else {//get duty ch from str.dutych
                    $duty = $this->str_model->get_dutych();
                    $current_ch = $duty['start_ch'];
                    $current_dateduty = $duty['start_date'];
                }

                $cars[] = $this->str_model->get_cars_by_pasp($row['id_pasp'], $current_dateduty, $current_ch);
            }
//print_r($cars);
            if (!empty($cars)) {
                echo json_encode(array('cars' => $cars, 'is_error' => 0));
            } else {
                echo json_encode(array('msg' => 'Информация не найдена', 'is_error' => 1));
            }
        } else {
            echo json_encode(array('msg' => 'Выберите ПАСЧ', 'is_error' => 1));
        }
    }

    public function add_str_cars_to_form()
    {

        $cars = $this->input->post('cars');

        if (isset($cars) && !empty($cars)) {

            foreach ($cars as $value) {
                $row = explode('~', $value);

                $res = array();
                $res['id_teh'] = (isset($row[0])) ? trim($row[0]) : '';
                $res['mark'] = (isset($row[1])) ? trim($row[1]) : '';
                $res['pasp_name'] = (isset($row[2])) ? trim($row[2]) : '';
                $res['locorg_name'] = (isset($row[3])) ? trim($row[3]) : '';
                $res['v_ac'] = (isset($row[4])) ? $row[4] : '';
                $res['man_per_car'] = (isset($row[5])) ? $row[5] : '';

                if (!empty($res))
                    $result[] = $res;
            }

            if (isset($result) && !empty($result)) {

                echo json_encode(array('cars' => $result, 'is_error' => 0));
            } else {
                echo json_encode(array('is_error' => 1, 'msg' => 'Данные по технике не найдены'));
            }
        } else {
            echo json_encode(array('is_error' => 1, 'msg' => 'Данные по технике не найдены'));
        }
    }

    public function add_str_inf_to_form()
    {

        $ids_pasp = $this->input->post('ids_pasp');

        if (isset($ids_pasp) && !empty($ids_pasp)) {


            $result = $this->getStrByIdsPasp(array_unique($ids_pasp)); //data for table: shtat, vacant...

            if (isset($result) && !empty($result)) {

                echo json_encode(array('pasp' => $result, 'is_error' => 0));
            } else {
                echo json_encode(array('is_error' => 1, 'msg' => 'Данные по подразделениям не найдены'));
            }
        } else {
            echo json_encode(array('is_error' => 1, 'msg' => 'Необходимо выбрать подразделение'));
        }
    }
    /* save standart form  */

    public function standart_save()
    {

        $post = $this->input->post();
        $dones = array();
//print_r($post);exit();
        $id_dones = (isset($post['id_dones']) && !empty($post['id_dones'])) ? intval($post['id_dones']) : 0; //id of edit dones


        if ($id_dones != 0) {// edit SD
            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, false);
            $statuses_id = array_column($statuses, 'id_action');


            /* ROSN, UGZ, AVIA */
            if ($this->data['active_user']['level'] == 3 &&
                (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA]))) {

                if (($this->data['active_user']['id_local'] !== $this->data['dones']['author_local_id']) && $this->data['active_user']['id_region'] != Main_model::REGION_MINSK) {

                    redirect('creator/catalog');
                    die();
                } elseif ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                    redirect('creator/catalog');
                    die();
                }
            } else {
                /*  author SD = current user
                 * proved umchs and proved rcu and not open update
                 * proved rcu and not open update
                 *          */
                if ($this->data['active_user']['level'] == 3 && (($this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    redirect('creator/catalog');
                    die();
                } elseif ($this->data['active_user']['level'] == 2 && (($this->data['active_user']['id_region'] != $this->data['dones']['author_region_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    redirect('creator/catalog');
                    die();
                }
            }
        }


        /* settings */
        $settings = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $settings = $this->user_model->get_user_settings_options_format($settings);


        $dones['is_copy'] = 0;
        $dones['copy_parent_id'] = 0;

        $dones['specd_date'] = (isset($post['specd_date']) && !empty($post['specd_date'])) ? (\DateTime::createFromFormat('d.m.Y', $post['specd_date'])->format('Y-m-d')) : null;
        $dones['specd_number'] = (isset($post['specd_number']) && !empty($post['specd_number'])) ? trim($post['specd_number']) : '';
        $dones['short_description'] = (isset($post['short_description']) && !empty($post['short_description'])) ? trim($post['short_description']) : '';

        /* who creates/last updates and when */
        if ($id_dones == 0) {//create a new
            $dones['created_by'] = $this->data['active_user']['id_user'];
            $dones['date_insert'] = date("Y-m-d H:i:s");
        }
        $dones['last_updated_by'] = $this->data['active_user']['id_user'];
        $dones['date_last_update'] = date("Y-m-d H:i:s");

        /* official block */
        $dones['specd_vid'] = (isset($post['specd_vid']) && !empty($post['specd_vid'])) ? intval($post['specd_vid']) : 0;

        if ($id_dones == 0) {//create a new
            $dones['official_date_start'] = (isset($post['official_date_start']) && !empty($post['official_date_start'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start'])->format('Y-m-d H:i:s')) : null;
            $dones['official_date_end'] = date("Y-m-d H:i:s"); // if create new dones
        } else {//edit dones
            $dones['official_date_start_edit'] = (isset($post['official_date_start_edit']) && !empty($post['official_date_start_edit'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start_edit'])->format('Y-m-d H:i:s')) : null;
            $dones['official_date_end_edit'] = date("Y-m-d H:i:s");

            if ($this->data['dones']['is_copy'] == 1) {
                $dones['official_date_start'] = $dones['official_date_start_edit'];
                $dones['official_date_end'] = $dones['official_date_end_edit'];
            }
        }

        $dones['official_creator_name'] = (isset($post['official_creator_name']) && !empty($post['official_creator_name'])) ? trim($post['official_creator_name']) : '';
        $dones['official_creator_position'] = (isset($post['official_creator_position']) && !empty($post['official_creator_position'])) ? trim($post['official_creator_position']) : '';
        $dones['official_destination'] = (isset($post['official_destination']) && !empty($post['official_destination'])) ? trim($post['official_destination']) : '';

/* show or no blocks */
        $dones['is_show_address'] = (isset($post['is_show_address']) && !empty($post['is_show_address'])) ? 1 : 0;
        $dones['is_show_object'] = (isset($post['is_show_object']) && !empty($post['is_show_object'])) ? 1 : 0;
        $dones['is_show_prevention'] = (isset($post['is_show_prevention']) && !empty($post['is_show_prevention'])) ? 1 : 0;


        /* owner */
        $dones['id_face_belong'] = (isset($post['id_face_belong']) && !empty($post['id_face_belong'])) ? intval($post['id_face_belong']) : 0;

        if($dones['id_face_belong'] == 1){// individual face
            $dones['id_owner_category'] = (isset($post['id_owner_category']) && !empty($post['id_owner_category'])) ? intval($post['id_owner_category']) : 0;
            $dones['owner_fio'] = (isset($post['owner_fio']) && !empty($post['owner_fio'])) ? trim($post['owner_fio']) : '';
            $dones['owner_year_birthday'] = (isset($post['owner_year_birthday']) && !empty($post['owner_year_birthday'])) ? intval($post['owner_year_birthday']) : '';
            $dones['owner_address'] = (isset($post['owner_address']) && !empty($post['owner_address'])) ? trim($post['owner_address']) : '';
            $dones['owner_position'] = (isset($post['owner_position']) && !empty($post['owner_position'])) ? trim($post['owner_position']) : '';
            $dones['owner_job'] = (isset($post['owner_job']) && !empty($post['owner_job'])) ? trim($post['owner_job']) : '';
            $dones['owner_character'] = (isset($post['owner_character']) && !empty($post['owner_character'])) ? trim($post['owner_character']) : '';
            $dones['owner_is_uhet'] = (isset($post['owner_is_uhet']) && !empty($post['owner_is_uhet'])) ? intval($post['owner_is_uhet']) : 0;
            $dones['owner_live_together'] = (isset($post['owner_live_together']) && !empty($post['owner_live_together'])) ? intval($post['owner_live_together']) : 0;

            $dones['law_face_office_belong'] = 0;
            $dones['law_face_name_owner'] = '';
            $dones['is_show_owner']=(isset($post['is_show_owner']) && !empty($post['is_show_owner'])) ? intval($post['is_show_owner']) : 0;

        }
        elseif($dones['id_face_belong'] == 2){// law face
            $dones['id_owner_category'] = 0;
            $dones['owner_fio'] = '';
            $dones['owner_year_birthday'] = '';
            $dones['owner_address'] = '';
            $dones['owner_position'] = '';
            $dones['owner_job'] = '';
            $dones['owner_character'] = '';
            $dones['owner_is_uhet'] =  0;
            $dones['owner_live_together'] =  0;

            $dones['law_face_office_belong'] = (isset($post['law_face_office_belong']) && !empty($post['law_face_office_belong'])) ? intval($post['law_face_office_belong']) : 0;
            $dones['law_face_name_owner'] = (isset($post['law_face_name_owner']) && !empty($post['law_face_name_owner'])) ? trim($post['law_face_name_owner']) : '';

            $dones['is_show_owner']=(isset($post['is_show_owner_law']) && !empty($post['is_show_owner_law'])) ? intval($post['is_show_owner_law']) : 0;
        }
        else{
              $dones['id_owner_category'] = 0;
            $dones['owner_fio'] = '';
            $dones['owner_year_birthday'] = '';
            $dones['owner_address'] = '';
            $dones['owner_position'] = '';
            $dones['owner_job'] = '';
            $dones['owner_character'] = '';
            $dones['owner_is_uhet'] =  0;
            $dones['owner_live_together'] =  0;

            $dones['law_face_office_belong'] = 0;
            $dones['law_face_name_owner'] = '';
            $dones['is_show_owner']=0;
        }


        if(isset($dones['is_show_owner']) && $dones['is_show_owner'] == 1){
            $dones['owner_word']=(isset($post['owner_word']) && !empty($post['owner_word'])) ? trim($post['owner_word']) : '';
        }
        else{
           $dones['owner_word']='' ;
        }


        /* object text */
        if(isset($dones['is_show_object']) && $dones['is_show_object'] == 1){
            $dones['object_word']=(isset($post['object_word']) && !empty($post['object_word'])) ? trim($post['object_word']) : '';
        }
        else{
           $dones['object_word']='' ;
        }


        /* start text SD*/
        $dones['is_show_opening_descr'] = (isset($post['is_show_opening_descr']) && !empty($post['is_show_opening_descr'])) ? 1 : 0;
        $dones['opening_word'] = (isset($post['opening_word']) && !empty($post['opening_word'])) ? trim($post['opening_word']) : '';


        $dones['opening_description'] = (isset($post['opening_description']) && !empty($post['opening_description'])) ? trim($post['opening_description']) : '';

        /* description of RIG */
        $dones['id_rig'] = (isset($post['id_rig_current']) && !empty($post['id_rig_current'])) ? intval($post['id_rig_current']) : 0;

        if (!empty($settings) && isset($settings['is_seconds_show']) && in_array('yes', $settings['is_seconds_show'])) {
            $dones['time_msg'] = (isset($post['time_msg']) && !empty($post['time_msg'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', trim($post['time_msg']))->format('Y-m-d H:i:s')) : NULL;
        } else {
            $dones['time_msg'] = (isset($post['time_msg']) && !empty($post['time_msg'])) ? (\DateTime::createFromFormat('d.m.Y H:i', trim($post['time_msg']))->format('Y-m-d H:i:s')) : NULL;
        }

        if (!empty($settings) && isset($settings['is_seconds_show']) && in_array('yes', $settings['is_seconds_show'])) {
            $dones['time_loc'] = (isset($post['time_loc']) && !empty($post['time_loc'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', trim($post['time_loc']))->format('Y-m-d H:i:s')) : null;
        } else {
            $dones['time_loc'] = (isset($post['time_loc']) && !empty($post['time_loc'])) ? (\DateTime::createFromFormat('d.m.Y H:i', trim($post['time_loc']))->format('Y-m-d H:i:s')) : null;
        }

        if (!empty($settings) && isset($settings['is_seconds_show']) && in_array('yes', $settings['is_seconds_show'])) {
            $dones['time_likv'] = (isset($post['time_likv']) && !empty($post['time_likv'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', trim($post['time_likv']))->format('Y-m-d H:i:s')) : null;
        } else {
            $dones['time_likv'] = (isset($post['time_likv']) && !empty($post['time_likv'])) ? (\DateTime::createFromFormat('d.m.Y H:i', trim($post['time_likv']))->format('Y-m-d H:i:s')) : null;
        }

        $dones['podr_take_msg'] = (isset($post['podr_take_msg']) && !empty($post['podr_take_msg'])) ? trim($post['podr_take_msg']) : '';
        $dones['disp_take_msg'] = (isset($post['disp_take_msg']) && !empty($post['disp_take_msg'])) ? trim($post['disp_take_msg']) : '';
        $dones['address'] = (isset($post['address']) && !empty($post['address'])) ? trim($post['address']) : '';
        $dones['latitude'] = (isset($post['latitude']) && !empty($post['latitude'])) ? trim($post['latitude']) : '';
        $dones['longitude'] = (isset($post['longitude']) && !empty($post['longitude'])) ? trim($post['longitude']) : '';
        $dones['vid_hs_1'] = (isset($post['vid_hs_1']) && !empty($post['vid_hs_1'])) ? intval($post['vid_hs_1']) : 0;
        $dones['vid_hs_2'] = (isset($post['vid_hs_2']) && !empty($post['vid_hs_2'])) ? intval($post['vid_hs_2']) : 0;
        $dones['reason_rig'] = (isset($post['reason_rig']) && !empty($post['reason_rig'])) ? trim($post['reason_rig']) : '';
        $dones['firereason_rig'] = (isset($post['firereason_rig']) && !empty($post['firereason_rig'])) ? trim($post['firereason_rig']) : '';
        $dones['id_firereason'] = (isset($post['id_firereason']) && !empty($post['id_firereason'])) ? intval($post['id_firereason']) : 0;
        $dones['inspector'] = (isset($post['inspector']) && !empty($post['inspector'])) ? trim($post['inspector']) : '';


        /* gdzs block */
        $dones['spec_cnt_gdzs'] = (isset($post['spec_cnt_gdzs']) && !empty($post['spec_cnt_gdzs'])) ? intval($post['spec_cnt_gdzs']) : 0;
        $dones['spec_time_work_gdzs'] = (isset($post['spec_time_work_gdzs']) && !empty($post['spec_time_work_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_work_gdzs'])->format('H:i')) : null;
        $dones['spec_time_work_bef_inj_gdzs'] = (isset($post['spec_time_work_bef_inj_gdzs']) && !empty($post['spec_time_work_bef_inj_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_work_bef_inj_gdzs'])->format('H:i')) : null;
        $dones['spec_time_shtab_gdzs'] = (isset($post['spec_time_shtab_gdzs']) && !empty($post['spec_time_shtab_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_shtab_gdzs'])->format('H:i')) : null;


        /* people data block */
        $dones['people_fio'] = (isset($post['people_fio']) && !empty($post['people_fio'])) ? trim($post['people_fio']) : '';
        $dones['people_phone'] = (isset($post['people_phone']) && !empty($post['people_phone'])) ? trim($post['people_phone']) : '';
        $dones['people_address'] = (isset($post['people_address']) && !empty($post['people_address'])) ? trim($post['people_address']) : '';
        $dones['people_position'] = (isset($post['people_position']) && !empty($post['people_position'])) ? trim($post['people_position']) : '';
        $dones['people_status'] = (isset($post['people_status']) && !empty($post['people_status'])) ? intval($post['people_status']) : 0;



        /* detail inf block */
        $dones['detail_inf'] = (isset($post['detail_inf']) && !empty($post['detail_inf'])) ? trim($post['detail_inf']) : '';


        /* prevention block */
        $dones['prevention_time'] = (isset($post['prevention_time']) && !empty($post['prevention_time'])) ? (\DateTime::createFromFormat('d.m.Y', $post['prevention_time'])->format('Y-m-d')) : NULL;
        $dones['prevention_who'] = (isset($post['prevention_who']) && !empty($post['prevention_who'])) ? trim($post['prevention_who']) : '';
        $dones['prevention_result'] = (isset($post['prevention_result']) && !empty($post['prevention_result'])) ? trim($post['prevention_result']) : '';
        $dones['prevention_events'] = (isset($post['prevention_events']) && !empty($post['prevention_events'])) ? trim($post['prevention_events']) : '';


        /* is involved or no */
        $dones['is_not_involved_silymchs'] = (isset($post['is_not_involved_silymchs']) && !empty($post['is_not_involved_silymchs'])) ? 1 : 0;
        $dones['is_not_involved_innerservice'] = (isset($post['is_not_involved_innerservice']) && !empty($post['is_not_involved_innerservice'])) ? 1 : 0;
        $dones['is_not_involved_informing'] = (isset($post['is_not_involved_informing']) && !empty($post['is_not_involved_informing'])) ? 1 : 0;
        $dones['is_not_involved_trunks'] = (isset($post['is_not_involved_trunks']) && !empty($post['is_not_involved_trunks'])) ? 1 : 0;
        $dones['is_wide_table_trunks'] = (isset($post['is_wide_table_trunks']) && !empty($post['is_wide_table_trunks'])) ? 1 : 0;
        $dones['is_not_involved_str'] = (isset($post['is_not_involved_str']) && !empty($post['is_not_involved_str'])) ? 1 : 0;

        $dones['is_test_sd'] = (isset($post['is_test_sd']) && !empty($post['is_test_sd'])) ? 1 : 0;

        $dones['file_doc'] = (isset($post['file_doc']) && !empty($post['file_doc'])) ? $post['file_doc'] : null;

        // type SD
        $dones['type'] = Main_model::TYPE_SD_STANDART;

        /* insert/edit dones */
        if ($id_dones == 0) {//create a new
            if ($this->data['active_user']['is_guest'] == 1) {
                $dones['fio_jour'] = $this->data['active_user']['auth_fio'];
                $dones['position_name_jour'] = $this->data['active_user']['position_name'];
                $dones['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $dones['creator_name_jour'] = $this->data['active_user']['creator_name'];
                if (isset($this->data['active_user']['id_user_jour']) && !empty($this->data['active_user']['id_user_jour']))
                    $dones['id_user_jour'] = $this->data['active_user']['id_user_jour'];
            }



            $id_dones_new = $this->create_model->add_new_dones($dones);

            //logs
            $logs['id_user'] = $this->data['active_user']['id_user'];
            if ($this->data['active_user']['is_guest'] == 1) {
                $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
            }
            $logs['id_dones'] = $id_dones_new;
            //$logs['id_action']=self::actions['create_sd'];
            $logs['id_action'] = Logs_model::ACTION_CREATE_SD;
            $logs['date_action'] = date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
        } else {//edit
            $this->create_model->edit_dones($id_dones, $dones);
            $id_dones_new = $id_dones;

            //status
            if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {//grochs
                //statuses to history
                $history_actions['history_actions'] = array(Logs_model::ACTION_EDIT_SD, Logs_model::ACTION_PROVE_SD_UMCHS, Logs_model::ACTION_PROVE_SD_RCU);
            } else {
                //statuses to history
                $history_actions['history_actions'] = array(Logs_model::ACTION_EDIT_SD);
            }

            $history_actions['id_dones'] = $id_dones_new;
            $this->logs_model->delete_dones_statuses($history_actions);

            //set new status
            $logs['id_user'] = $this->data['active_user']['id_user'];
            if ($this->data['active_user']['is_guest'] == 1) {
                $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
            }
            $logs['id_dones'] = $id_dones_new;
            $logs['id_action'] = Logs_model::ACTION_EDIT_SD;
            $logs['date_action'] = date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
        }


        /*---------------- live together --------------*/

        //delete live_together
        $this->dones_model->delete_dones_live_together($id_dones_new);
        if ($dones['owner_live_together'] > 0) {
            $live_together = (isset($post['live_together']) && !empty($post['live_together'])) ? $post['live_together'] : array();

            if (!empty($live_together)) {
                foreach ($live_together as $k => $row) {
                    $dones_live_together = array();
                    if (isset($row['fio']) && !empty(trim($row['fio']))) {

                        $dones_live_together['id_dones'] = $id_dones_new;
                        $dones_live_together['fio'] = trim($row['fio']);
                        $dones_live_together['year_birthday'] = (isset($row['year_birthday']) && !empty($row['year_birthday'])) ? trim($row['year_birthday']) : '';
                        $dones_live_together['note'] = (isset($row['note']) && !empty($row['note'])) ? trim($row['note']) : '';

                        $dones_live_together['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                        $this->dones_model->add_dones_live_together($dones_live_together);
                    }
                }
            }
        }



        /* ------------ silymchs of dones 1-∞ ------------- */
        $silymchs = (isset($post['silymchs']) && !empty($post['silymchs'])) ? $post['silymchs'] : array();

        $silymchs_by_dones = $this->create_model->get_dones_silymchs($id_dones_new); // silymchs of dones
        $prev_ids_silymchs_by_dones = (isset($silymchs_by_dones) && !empty($silymchs_by_dones)) ? array_unique(array_column($silymchs_by_dones, 'id')) : array();

        if (isset($silymchs) && !empty($silymchs) && $dones['is_not_involved_silymchs'] == 0) {
            foreach ($silymchs as $k => $row) {
                $dones_silymchs = array();
                if (isset($row['mark']) && !empty(trim($row['mark']))) {

                    $dones_silymchs['id_dones'] = $id_dones_new;
                    $dones_silymchs['mark'] = trim($row['mark']);
                    $dones_silymchs['id_teh'] = (isset($row['id_teh']) && !empty($row['id_teh'])) ? intval($row['id_teh']) : 0;
                    $dones_silymchs['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_silymchs['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_silymchs['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? (trim($row['v_ac']) * 1000) : 0;
                    $dones_silymchs['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                    $dones_silymchs['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i', $row['time_exit'])->format('H:i')) : null;
                    $dones_silymchs['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : null;
                    $dones_silymchs['time_follow'] = (isset($row['time_follow']) && !empty($row['time_follow'])) ? intval($row['time_follow']) : 0;
                    $dones_silymchs['distance'] = (isset($row['distance']) && !empty($row['distance'])) ? trim($row['distance']) : '';
                    $dones_silymchs['time_end'] = (isset($row['time_end']) && !empty($row['time_end'])) ? (\DateTime::createFromFormat('H:i', $row['time_end'])->format('H:i')) : NULL;
                    $dones_silymchs['time_return'] = (isset($row['time_return']) && !empty($row['time_return'])) ? (\DateTime::createFromFormat('H:i', $row['time_return'])->format('H:i')) : null;
                    $dones_silymchs['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $id_silymchs = (isset($row['id_silymchs']) && !empty($row['id_silymchs'])) ? intval($row['id_silymchs']) : 0; //edit id of table silymchs

                    if ($id_silymchs == 0) {//add new silymchs
                        $this->create_model->add_new_dones_silymchs($dones_silymchs);
                    } else {//edit silymchs
                        $this->create_model->edit_new_dones_silymchs($id_silymchs, $dones_silymchs);
                        if (isset($prev_ids_silymchs_by_dones) && !empty($prev_ids_silymchs_by_dones) && (($key = array_search($id_silymchs, $prev_ids_silymchs_by_dones)) !== false)) {
                            unset($prev_ids_silymchs_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev silymchs of dones */
        if (isset($prev_ids_silymchs_by_dones) && !empty($prev_ids_silymchs_by_dones)) {
            $this->create_model->delete_dones_silymchs_by_ids($id_dones_new, $prev_ids_silymchs_by_dones);
        }



        /* ------------ innerservice of dones 1-∞ ------------- */
        $innerservice = (isset($post['innerservice']) && !empty($post['innerservice'])) ? $post['innerservice'] : array();

        $innerservice_by_dones = $this->create_model->get_dones_innerservice($id_dones_new); // innerservice of dones
        $prev_ids_innerservice_by_dones = (isset($innerservice_by_dones) && !empty($innerservice_by_dones)) ? array_unique(array_column($innerservice_by_dones, 'id')) : array();

        if (isset($innerservice) && !empty($innerservice) && $dones['is_not_involved_innerservice'] == 0) {
            foreach ($innerservice as $k => $row) {
                $dones_innerservice = array();
                if (isset($row['service_id']) && !empty(intval($row['service_id']))) {

                    $dones_innerservice['id_dones'] = $id_dones_new;
                    $dones_innerservice['service_id'] = intval($row['service_id']);
                    $dones_innerservice['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i', $row['time_msg'])->format('H:i')) : NULL;
                    $dones_innerservice['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : NULL;
                    $dones_innerservice['distance'] = (isset($row['distance']) && !empty($row['distance'])) ? trim($row['distance']) : '';
                    $dones_innerservice['note'] = (isset($row['note']) && !empty($row['note'])) ? trim($row['note']) : '';
                    $dones_innerservice['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $id_innerservice = (isset($row['id_innerservice']) && !empty($row['id_innerservice'])) ? intval($row['id_innerservice']) : 0; //edit id of table dones_innerservice

                    if ($id_innerservice == 0) {//add new innerservice
                        $id_dones_innerservice_new = $this->create_model->add_new_dones_innerservice($dones_innerservice);
                        //add work innerservice
                        if (isset($row['work_innerservice']) && !empty($row['work_innerservice'])) {
                            foreach ($row['work_innerservice'] as $value) {
                                $arr['id_dones_innerservice'] = $id_dones_innerservice_new;
                                $arr['id_work_innerservice'] = $value;
                                $this->create_model->add_new_dones_innerservice_work($arr);
                            }
                        }
                    } else {//edit innerservice
                        $this->create_model->edit_dones_innerservice($id_innerservice, $dones_innerservice);

                        //clear prev work innerservice
                        $this->create_model->delete_dones_innerservice_work($id_innerservice);
                        //add work innerservice
                        if (isset($row['work_innerservice']) && !empty($row['work_innerservice'])) {
                            foreach ($row['work_innerservice'] as $value) {
                                $arr['id_dones_innerservice'] = $id_innerservice;
                                $arr['id_work_innerservice'] = $value;
                                $this->create_model->add_new_dones_innerservice_work($arr);
                            }
                        }


                        if (isset($prev_ids_innerservice_by_dones) && !empty($prev_ids_innerservice_by_dones) && (($key = array_search($id_innerservice, $prev_ids_innerservice_by_dones)) !== false)) {
                            unset($prev_ids_innerservice_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev innerservice of dones */
        if (isset($prev_ids_innerservice_by_dones) && !empty($prev_ids_innerservice_by_dones)) {
            $this->create_model->delete_dones_innerservice_by_ids($id_dones_new, $prev_ids_innerservice_by_dones);
        }



        /* ------------ informing of dones 1-∞ ------------- */
        $informing = (isset($post['informing']) && !empty($post['informing'])) ? $post['informing'] : array();

        $informing_by_dones = $this->create_model->get_dones_informing($id_dones_new); // innerservice of dones
        $prev_ids_informing_by_dones = (isset($informing_by_dones) && !empty($informing_by_dones)) ? array_unique(array_column($informing_by_dones, 'id')) : array();

        if (isset($informing) && !empty($informing) && $dones['is_not_involved_informing'] == 0) {
            foreach ($informing as $k => $row) {
                $dones_informing = array();
                if (isset($row['fio']) && !empty(trim($row['fio']))) {

                    $dones_informing['id_dones'] = $id_dones_new;
                    $dones_informing['fio'] = trim($row['fio']);
                    $dones_informing['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i', $row['time_msg'])->format('H:i')) : NULL;
                    $dones_informing['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i', $row['time_exit'])->format('H:i')) : NULL;
                    $dones_informing['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : NULL;
                    $dones_informing['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $id_informing = (isset($row['id_informing']) && !empty($row['id_informing'])) ? intval($row['id_informing']) : 0; //edit id of table informing

                    if ($id_informing == 0) {//add new informing
                        $this->create_model->add_new_dones_informing($dones_informing);
                    } else {//edit informing
                        $this->create_model->edit_dones_informing($id_informing, $dones_informing);
                        if (isset($prev_ids_informing_by_dones) && !empty($prev_ids_informing_by_dones) && (($key = array_search($id_informing, $prev_ids_informing_by_dones)) !== false)) {
                            unset($prev_ids_informing_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev informing of dones */
        if (isset($prev_ids_informing_by_dones) && !empty($prev_ids_informing_by_dones)) {
            $this->create_model->delete_dones_informing_by_ids($id_dones_new, $prev_ids_informing_by_dones);
        }





        /* ------------ str of dones 1-∞ ------------- */
        $str = (isset($post['str']) && !empty($post['str'])) ? $post['str'] : array();

        $str_by_dones = $this->create_model->get_dones_str($id_dones_new); // str of dones
        $prev_ids_str_by_dones = (isset($str_by_dones) && !empty($str_by_dones)) ? array_unique(array_column($str_by_dones, 'id')) : array();

        if (isset($str) && !empty($str) && $dones['is_not_involved_str'] == 0) {
            foreach ($str as $k => $row) {
                $dones_str = array();
                if (isset($row['pasp_name']) && !empty(trim($row['pasp_name']))) {

                    $dones_str['id_dones'] = $id_dones_new;
                    $dones_str['id_pasp'] = (isset($row['id_pasp']) && !empty($row['id_pasp'])) ? intval($row['id_pasp']) : 0;
                    $dones_str['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_str['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_str['shtat'] = (isset($row['shtat']) && !empty($row['shtat'])) ? intval($row['shtat']) : 0;
                    $dones_str['vacant'] = (isset($row['vacant']) && !empty($row['vacant'])) ? intval($row['vacant']) : 0;
                    $dones_str['on_list_ch'] = (isset($row['on_list_ch']) && !empty($row['on_list_ch'])) ? intval($row['on_list_ch']) : 0;
                    $dones_str['vacant_ch'] = (isset($row['vacant_ch']) && !empty($row['vacant_ch'])) ? intval($row['vacant_ch']) : 0;
                    $dones_str['face_ch'] = (isset($row['face_ch']) && !empty($row['face_ch'])) ? intval($row['face_ch']) : 0;
                    $dones_str['br_ch'] = (isset($row['br_ch']) && !empty($row['br_ch'])) ? intval($row['br_ch']) : 0;
                    $dones_str['cnt_trip_man'] = (isset($row['cnt_trip_man']) && !empty($row['cnt_trip_man'])) ? intval($row['cnt_trip_man']) : 0;
                    $dones_str['cnt_holiday_man'] = (isset($row['cnt_holiday_man']) && !empty($row['cnt_holiday_man'])) ? intval($row['cnt_holiday_man']) : 0;
                    $dones_str['cnt_ill_man'] = (isset($row['cnt_ill_man']) && !empty($row['cnt_ill_man'])) ? intval($row['cnt_ill_man']) : 0;
                    $dones_str['cnt_naryd'] = (isset($row['cnt_naryd']) && !empty($row['cnt_naryd'])) ? intval($row['cnt_naryd']) : 0;
                    $dones_str['cnt_other_man'] = (isset($row['cnt_other_man']) && !empty($row['cnt_other_man'])) ? intval($row['cnt_other_man']) : 0;
                    $dones_str['gas'] = (isset($row['gas']) && !empty($row['gas'])) ? intval($row['gas']) : 0;
                    $dones_str['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;


                    $id_str = (isset($row['id_str']) && !empty($row['id_str'])) ? intval($row['id_str']) : 0; //edit id of table dones_str

                    if ($id_str == 0) {//add new str
                        $this->create_model->add_new_dones_str($dones_str);
                    } else {//edit str
                        $this->create_model->edit_dones_str($id_str, $dones_str);
                        if (isset($prev_ids_str_by_dones) && !empty($prev_ids_str_by_dones) && (($key = array_search($id_str, $prev_ids_str_by_dones)) !== false)) {
                            unset($prev_ids_str_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev str of dones */
        if (isset($prev_ids_str_by_dones) && !empty($prev_ids_str_by_dones)) {
            $this->create_model->delete_dones_str_by_ids($id_dones_new, $prev_ids_str_by_dones);
        }




        /* ------------ str text of dones 1-∞ ------------- */
        $str_text = (isset($post['str_text']) && !empty($post['str_text'])) ? $post['str_text'] : array();

        $str_text_by_dones = $this->create_model->get_dones_str_text($id_dones_new); // str_text of dones
        $prev_ids_str_text_by_dones = (isset($str_text_by_dones) && !empty($str_text_by_dones)) ? array_unique(array_column($str_text_by_dones, 'id')) : array();

        if (isset($str_text) && !empty($str_text) && $dones['is_not_involved_str'] == 0) {
            foreach ($str_text as $k => $row) {
                $dones_str_text = array();
                if (isset($row['str_text_podr_name']) && !empty(trim($row['str_text_podr_name']))) {

                    $dones_str_text['id_dones'] = $id_dones_new;
                    $dones_str_text['id_pasp'] = (isset($row['id_pasp']) && !empty($row['id_pasp'])) ? intval($row['id_pasp']) : 0;
                    $dones_str_text['str_text_podr_name'] = (isset($row['str_text_podr_name']) && !empty($row['str_text_podr_name'])) ? trim($row['str_text_podr_name']) : '';
                    $dones_str_text['str_text_description'] = (isset($row['str_text_description']) && !empty($row['str_text_description'])) ? trim($row['str_text_description']) : '';
                    $dones_str_text['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $id_str_text = (isset($row['id_str_text']) && !empty($row['id_str_text'])) ? intval($row['id_str_text']) : 0; //edit id of table dones_str_text

                    if ($id_str_text == 0) {//add new str_text
                        $this->create_model->add_new_dones_str_text($dones_str_text);
                    } else {//edit str_text
                        $this->create_model->edit_dones_str_text($id_str_text, $dones_str_text);
                        if (isset($prev_ids_str_text_by_dones) && !empty($prev_ids_str_text_by_dones) && (($key = array_search($id_str_text, $prev_ids_str_text_by_dones)) !== false)) {
                            unset($prev_ids_str_text_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev str_text of dones */
        if (isset($prev_ids_str_text_by_dones) && !empty($prev_ids_str_text_by_dones)) {
            $this->create_model->delete_dones_str_text_by_ids($id_dones_new, $prev_ids_str_text_by_dones);
        }




        /* ------------ trunks of dones 1-∞ ------------- */
        $trunks = (isset($post['trunks']) && !empty($post['trunks'])) ? $post['trunks'] : array();

        $trunks_by_dones = $this->create_model->get_dones_trunks($id_dones_new); // trunks of dones
        $prev_ids_trunks_by_dones = (isset($trunks_by_dones) && !empty($trunks_by_dones)) ? array_unique(array_column($trunks_by_dones, 'id')) : array();

        //$trunks = (isset($post['trunks']) && !empty($post['trunks'])) ? $post['trunks'] : array();
//print_r($trunks);exit();

        if (isset($trunks) && !empty($trunks)) {
            foreach ($trunks as $k => $row) {
                $dones_trunks = array();
                if (isset($row['mark']) && !empty(trim($row['mark']))) {

                    $dones_trunks['id_dones'] = $id_dones_new;
                    $dones_trunks['mark'] = trim($row['mark']);
                    $dones_trunks['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_trunks['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_trunks['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? (trim($row['v_ac']) * 1000) : 0;
                    $dones_trunks['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                    $dones_trunks['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : NULL;
                    $dones_trunks['s_fire_arrival'] = (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? trim($row['s_fire_arrival']) : '';
                    $dones_trunks['time_pod'] = (isset($row['time_pod']) && !empty($row['time_pod'])) ? (\DateTime::createFromFormat('H:i', $row['time_pod'])->format('H:i')) : NULL;
                    $dones_trunks['means_trunks'] = (isset($row['means_trunks']) && !empty($row['means_trunks'])) ? trim($row['means_trunks']) : '';
                    $dones_trunks['water_po_out'] = (isset($row['water_po_out']) && !empty($row['water_po_out'])) ? trim($row['water_po_out']) : '';
                    $dones_trunks['time_loc'] = (isset($row['time_loc']) && !empty($row['time_loc'])) ? (\DateTime::createFromFormat('H:i', $row['time_loc'])->format('H:i')) : NULL;
                    $dones_trunks['s_fire_loc'] = (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? trim($row['s_fire_loc']) : '';
                    $dones_trunks['time_likv'] = (isset($row['time_likv']) && !empty($row['time_likv'])) ? (\DateTime::createFromFormat('H:i', $row['time_likv'])->format('H:i')) : NULL;

                    if ($dones['is_wide_table_trunks'] == 1) {
                        $dones_trunks['actions_ls'] = (isset($row['actions_ls']) && !empty($row['actions_ls'])) ? trim($row['actions_ls']) : '';
                    } else {
                        $dones_trunks['actions_ls'] = '';
                    }



                    $dones_trunks['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;



                    $id_trunks = (isset($row['id_trunks']) && !empty($row['id_trunks'])) ? intval($row['id_trunks']) : 0; //edit id of table dones_trunks





                    if ($id_trunks == 0) {//add new trunks
                        $this->create_model->add_new_dones_trunks($dones_trunks);
                    } else {//edit trunks
                        $this->create_model->edit_dones_trunks($id_trunks, $dones_trunks);
                        if (isset($prev_ids_trunks_by_dones) && !empty($prev_ids_trunks_by_dones) && (($key = array_search($id_trunks, $prev_ids_trunks_by_dones)) !== false)) {
                            unset($prev_ids_trunks_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev trunks of dones */
        if (isset($prev_ids_trunks_by_dones) && !empty($prev_ids_trunks_by_dones)) {
            $this->create_model->delete_dones_trunks_by_ids($id_dones_new, $prev_ids_trunks_by_dones);
        }



        /* ------------ water source of dones 1-∞ ------------- */
        $water_source = (isset($post['water_source']) && !empty($post['water_source'])) ? $post['water_source'] : array();

        $water_source_by_dones = $this->create_model->get_dones_water_source($id_dones_new); // water source of dones
        $prev_ids_water_source_by_dones = (isset($water_source_by_dones) && !empty($water_source_by_dones)) ? array_unique(array_column($water_source_by_dones, 'id')) : array();

        if (isset($water_source) && !empty($water_source)) {
            foreach ($water_source as $k => $row) {
                $dones_water_source = array();
                if (isset($row['water_source_type']) && !empty(intval($row['water_source_type']))) {

                    $dones_water_source['id_dones'] = $id_dones_new;
                    $dones_water_source['water_source_type'] = intval($row['water_source_type']);
                    $dones_water_source['water_source_distance'] = (isset($row['water_source_distance']) && !empty($row['water_source_distance'])) ? trim($row['water_source_distance']) : '';
                    $dones_water_source['water_source_use'] = (isset($row['water_source_use']) && !empty($row['water_source_use'])) ? trim($row['water_source_use']) : '';
                    $dones_water_source['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;


                    $id_water_source = (isset($row['id_water_source']) && !empty($row['id_water_source'])) ? intval($row['id_water_source']) : 0; //edit id of table dones_water_source





                    if ($id_water_source == 0) {//add new water_source
                        $this->create_model->add_new_dones_water_source($dones_water_source);
                    } else {//edit water_source
                        $this->create_model->edit_dones_water_source($id_water_source, $dones_water_source);
                        if (isset($prev_ids_water_source_by_dones) && !empty($prev_ids_water_source_by_dones) && (($key = array_search($id_water_source, $prev_ids_water_source_by_dones)) !== false)) {
                            unset($prev_ids_water_source_by_dones[$key]);
                        }
                    }
                }
            }
        }

        /* delete prev water_source of dones */
        if (isset($prev_ids_water_source_by_dones) && !empty($prev_ids_water_source_by_dones)) {
            $this->create_model->delete_dones_water_source_by_ids($id_dones_new, $prev_ids_water_source_by_dones);
        }


        /* ------------ object of dones 1-1 ------------- */
        $object = array();
        $object['id_dones'] = $id_dones_new;
        $object['object'] = (isset($post['object']) && !empty($post['object'])) ? trim($post['object']) : '';
        $object['object_office_belong'] = (isset($post['object_office_belong']) && !empty($post['object_office_belong'])) ? intval($post['object_office_belong']) : 0;
        $object['object_house'] = (isset($post['object_house']) && !empty($post['object_house'])) ? intval($post['object_house']) : 0;
        $object['object_floor'] = (isset($post['object_floor']) && !empty($post['object_floor'])) ? trim($post['object_floor']) : '';
        $object['object_size'] = (isset($post['object_size']) && !empty($post['object_size'])) ? trim($post['object_size']) : '';
        $object['object_is_electric'] = (isset($post['object_is_electric']) && !empty($post['object_is_electric'])) ? intval($post['object_is_electric']) : 0;
        $object['object_is_api'] = (isset($post['object_is_api']) && !empty($post['object_is_api'])) ? intval($post['object_is_api']) : 0;
        $object['object_material'] = (isset($post['object_material']) && !empty($post['object_material'])) ? intval($post['object_material']) : 0;
        $object['object_roof'] = (isset($post['object_roof']) && !empty($post['object_roof'])) ? intval($post['object_roof']) : 0;

        $object['is_aps'] = (isset($post['is_aps']) && !empty($post['is_aps'])) ? intval($post['is_aps']) : 0;

        if ($dones['id_face_belong'] == 1 && $object['object_is_api'] == 1) {// individual face
            $object['api_date'] = (isset($post['api_date']) && !empty($post['api_date'])) ? (\DateTime::createFromFormat('d.m.Y', trim($post['api_date']))->format('Y-m-d')) : NULL;
            $object['id_api_source'] = (isset($post['id_api_source']) && !empty($post['id_api_source'])) ? intval($post['id_api_source']) : 0;
            $object['is_api_worked'] = (isset($post['is_api_worked']) && !empty($post['is_api_worked'])) ? intval($post['is_api_worked']) : 0;
            $object['is_api_influence'] = (isset($post['is_api_influence']) && !empty($post['is_api_influence'])) ? intval($post['is_api_influence']) : 0;

        } else {// individual face
            $object['api_date'] = NULL;
            $object['id_api_source'] = 0;
            $object['is_api_worked'] = 0;
            $object['is_api_influence'] = 0;
        }

        if ($dones['id_face_belong'] == 2 && $object['is_aps'] == 1) {// law face
            $object['aps_name'] = (isset($post['api_date']) && !empty($post['aps_name'])) ? trim($post['aps_name']) : '';
            $object['is_aps_worked'] = (isset($post['is_aps_worked']) && !empty($post['is_aps_worked'])) ? intval($post['is_aps_worked']) : 0;
            $object['is_aps_influence'] = (isset($post['is_aps_influence']) && !empty($post['is_aps_influence'])) ? intval($post['is_aps_influence']) : 0;

        } else {// law face
            $object['aps_name'] = '';
            $object['is_aps_worked'] = 0;
            $object['is_aps_influence'] = 0;
        }

        $id_object = (isset($post['id_object']) && !empty($post['id_object'])) ? intval($post['id_object']) : 0; //edit id of table object

        if ($id_object == 0) {//add new object
            $this->create_model->add_new_dones_object($object);
        } else {//edit object
            $this->create_model->edit_dones_object($id_object, $object);
        }




        /* media */
        $this->dones_model->delete_dones_media($id_dones_new);

        if (isset($post['sd_media']) && !empty($post['sd_media'])) {

            foreach ($post['sd_media'] as $type => $row) {

                if (!empty($row)) {
                    foreach ($row as $file) {
                        $media = [];
                        if (!empty($file)) {
                            $media['id_dones'] = $id_dones_new;
                            $media['file'] = $file;
                            $media['type'] = $type;

                            $this->dones_model->add_dones_media($media);
                        }
                    }
                }
            }
        }

        /*  settings accordion */
        $settings_accordion['value']=(isset($post['settings_accordion']) && !empty($post['settings_accordion'])) ? $post['settings_accordion'] : '';
        $settings_accordion['id_user']=$this->data['active_user']['id_user'];
        $settings_accordion['id_dones']=$id_dones_new;
        $settings_accordion['date_insert'] = date('Y-m-d H:i:s');
        $this->dones_model->delete_settings_accordion($id_dones_new,$settings_accordion['id_user']);
        if(!empty($settings_accordion['value']))
        $this->dones_model->add_settings_accordion($settings_accordion);

        redirect('/creator/catalog');
    }

    public function edit_form_standart($id_dones = 0)
    {
        $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);


        if ($this->data['dones']['type'] == Main_model::TYPE_SD_SIMPLE)
            redirect('/dones/edit_form_simple/' . $id_dones);

        $this->data['type_sd'] = Main_model::TYPE_SD_STANDART;
        $this->data['title'] = 'Ред. спец.донесение';
        $this->data['is_show_btn_search_rig'] = 1; //show btn "search rig"

        $this->data['bread_crumb'] = array(array('/' => 'Редактировать специальное донесение'),
            array('ID = ' . $id_dones)
        );


        /* settings */
        $this->data['settings'] = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $this->data['settings'] = $this->user_model->get_user_settings_options_format($this->data['settings']);



        $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, false);
        $statuses_id = array_column($statuses, 'id_action');

        $this->data['face_belong'] = $this->main_model->get_face_belong();
        $this->data['owner_categories'] = $this->journal_model->get_owner_categories();
        $this->data['api_source'] = $this->main_model->get_api_source();


        if ($this->session->userdata('can_edit') == 0) {// viewer can see SD
            $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
        } else {


            /* ROSN, UGZ, AVIA */
            if ($this->data['active_user']['level'] == 3 &&
                (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA]))) {

                if (($this->data['active_user']['id_local'] !== $this->data['dones']['author_local_id']) && $this->data['active_user']['id_region'] != Main_model::REGION_MINSK) {
                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                } elseif ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                }
            } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU &&
                $this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
            } else {
                /*  author SD = current user
                 * proved umchs and proved rcu and not open update
                 * proved rcu and not open update
                 *          */
                if ($this->data['active_user']['level'] == 3 && (($this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                } elseif ($this->data['active_user']['level'] == 2 && (($this->data['active_user']['id_region'] != $this->data['dones']['author_region_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                }
            }
        }

        if (isset($this->data['dones']['is_see']) && $this->data['dones']['is_see'] == 1) {
            $this->data['title'] = 'Прсмотр. спец.донесения';
            //$this->data['is_show_btn_search_rig'] = 0; //not show btn "search rig"

            $this->data['bread_crumb'] = array(array('/' => 'Просмотреть специальное донесение'),
                array('ID = ' . $id_dones)
            );
        }



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

        /* live together */
        $this->data['dones']['live_together_arr'] = $this->dones_model->get_dones_live_together($id_dones);

        /* data of edit dones */
        $this->data['dones']['silymchs'] = $this->create_model->get_dones_silymchs($id_dones);


        $innerservice = $this->create_model->get_dones_innerservice($id_dones);
        if (isset($innerservice) && !empty($innerservice)) {//works of each innerservice row
            foreach ($innerservice as $key => $row) {
                $works = $this->create_model->get_dones_innerservice_work($row['id']);
                $ids_work = (isset($works) && !empty($works)) ? array_column($works, 'id_work_innerservice') : array();
                $innerservice[$key]['works'] = $ids_work;
            }
        }
        $this->data['dones']['innerservice'] = $innerservice;


        $this->data['dones']['informing'] = $this->create_model->get_dones_informing($id_dones);
        $this->data['dones']['str'] = $this->create_model->get_dones_str($id_dones);
        $this->data['dones']['str_text'] = $this->create_model->get_dones_str_text($id_dones);
        $this->data['dones']['trunks'] = $this->create_model->get_dones_trunks($id_dones);


        $this->data['dones']['water_source'] = $this->create_model->get_dones_water_source($id_dones);


        $this->data['dones']['object'] = $this->create_model->get_dones_object($id_dones);
        $this->data['dones']['object_data'] = $this->create_model->get_dones_object_data($id_dones);
        if (!empty($this->data['dones']['object_data']) && !empty($this->data['dones']['object_data']['object'])) {
            $this->data['dones']['object_data']['object_preview'] = explode(PHP_EOL, $this->data['dones']['object_data']['object']);
            $this->data['dones']['object_data']['object_floor_text'] = get_text_by_floor(intval($this->data['dones']['object_data']['object_floor']));
        }
//print_r();exit();

        //media
        $media = $this->dones_model->get_dones_media($id_dones);
        $this->data['dones']['media'] = $media;


        /* preview */
        $this->data['dones']['preview_opening_description'] = explode(PHP_EOL, $this->data['dones']['opening_description']);
        $this->data['dones']['preview_prevention_results'] = explode(PHP_EOL, $this->data['dones']['prevention_result']);
        $this->data['dones']['preview_prevention_events'] = explode(PHP_EOL, $this->data['dones']['prevention_events']);
//        if (!empty($media)) {
//            $i = 0;
//            foreach ($media as $row) {
//                $i++;
//                $this->data['dones']['media'][$row['type']][$i] = $row['file'];
//            }
//        }

        /*  settings accordion */
        $this->data['settings_accordion']=$this->dones_model->get_settings_accordion($id_dones,$this->data['active_user']['id_user']);


        $this->twig->display('create/standart/form_standart', $this->data);
    }

    public function delete_sd($id_dones = 0)
    {

        if ($this->input->is_ajax_request()) {

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, false);
            $statuses_id = array_column($statuses, 'id_action');


            /* ROSN, UGZ, AVIA */
            if ($this->data['active_user']['level'] == 3 &&
                (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA]))) {

                if (($this->data['active_user']['id_local'] !== $this->data['dones']['author_local_id']) && $this->data['active_user']['id_region'] != Main_model::REGION_MINSK) {

                    redirect('creator/catalog');
                    die();
                } elseif ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                    redirect('creator/catalog');
                    die();
                }
            } else {

                /*  author SD = current user
                 * proved umchs and proved rcu and not open update
                 * proved rcu and not open update
                 *          */
                if ($this->data['active_user']['level'] == 3 && (($this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    echo json_encode(array('error' => 'Удаление недоступно'));
                    //redirect('creator/catalog');
                    die();
                } elseif ($this->data['active_user']['level'] == 2 && (($this->data['active_user']['id_region'] != $this->data['dones']['author_region_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    redirect('creator/catalog');
                    die();
                }
            }

            if (isset($id_dones) && !empty($id_dones)) {

                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;
                $logs['id_action'] = Logs_model::ACTION_DELETE_SD;
                $logs['date_action'] = date("Y-m-d H:i:s");
                $this->logs_model->add_logs($logs);

                $this->create_model->delete_dones($id_dones);

                echo json_encode(array('success' => 'Специальное донесение удалено'));
            }
        }
    }

    public function set_number_sd($id_dones = 0)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();
            $id_dones = $post['sd_id'];
            $sd_number = (isset($post['sd_number']) && !empty($post['sd_number'])) ? trim($post['sd_number']) : '';

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            /*  author SD = current user
             *          */
            if (($this->data['active_user']['level'] == 3 && $this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                ($this->data['active_user']['level'] == 2 && $this->data['active_user']['id_region'] != $this->data['dones']['author_region_id'])) {

                echo json_encode(array('error' => 'Функция не доступна. Вы не являетесь автором.'));
                die();
            }


            if (isset($id_dones) && !empty($id_dones) && isset($sd_number) && !empty($sd_number)) {

                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;
                $logs['id_action'] = Logs_model::ACTION_SET_NUMBER_SD;
                $logs['date_action'] = date("Y-m-d H:i:s");
                $this->logs_model->add_logs($logs);

                $this->dones_model->set_number_dones($id_dones, $sd_number);

                echo json_encode(array('success' => 1));
            }
        }
    }

    public function prove($id_dones = 0)
    {


        if ($this->input->is_ajax_request()) {

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            /*  author SD = current user
             *          */
            if (($this->data['active_user']['level'] == 3) ||
                ($this->data['active_user']['level'] == 2 && $this->data['active_user']['id_region'] != $this->data['dones']['author_region_id'])) {

                echo json_encode(array('error' => 'Функция не доступна. Вы не являетесь автором.'));
                die();
            }


            $is_proved_sd = $this->dones_model->is_status_by_id_dones($id_dones, ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_PROVE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_PROVE_SD_RCU : 0));


            if (isset($is_proved_sd) && !empty($is_proved_sd)) {
                echo json_encode(array('error' => 'Функция не доступна. СД уже было подтверждено.'));
                die();
            }


            if (isset($id_dones) && !empty($id_dones)) {


                //set statuses to history
                if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {//umchs
                    $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_UMCHS, Logs_model::ACTION_PROVE_SD_UMCHS, Logs_model::ACTION_UPDATE_REFUSE_UMCHS);
                } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {//rcu
                    $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_RCU, Logs_model::ACTION_PROVE_SD_RCU, Logs_model::ACTION_UPDATE_REFUSE_RCU);
                }

                $history_actions['id_dones'] = $id_dones;
                $this->logs_model->delete_dones_statuses($history_actions);

                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;
                $logs['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_PROVE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_PROVE_SD_RCU : 0);
                $logs['date_action'] = date("Y-m-d H:i:s");
                $this->logs_model->add_logs($logs);

                echo json_encode(array('success' => 'СД успешно подтверждено'));
            }
        }
    }

    public function refuse()
    {

        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();
            $is_refresh = (isset($post['is_refresh']) && !empty($post['is_refresh'])) ? intval($post['is_refresh']) : 0;
            $id_dones = $post['sd_id'];
            $description_refuse = (isset($post['description_refuse']) && !empty($post['description_refuse'])) ? trim($post['description_refuse']) : '';

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            /*  author SD = current user
             *          */
            if (($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) ||
                ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS && $this->data['active_user']['id_region'] != $this->data['dones']['author_region_id'])) {

                echo json_encode(array('error' => 'Функция не доступна. Вы не являетесь автором.'));
                die();
            }


            $is_proved_sd = $this->dones_model->is_status_by_id_dones($id_dones, ($this->data['active_user']['level'] == 2) ? Logs_model::ACTION_PROVE_SD_UMCHS : (($this->data['active_user']['level'] == 1) ? Logs_model::ACTION_PROVE_SD_RCU : 0));


            if (isset($is_proved_sd) && !empty($is_proved_sd)) {
                echo json_encode(array('error' => 'Функция не доступна. СД было подтверждено.'));
                die();
            }



            /* is refused by this user  */
            $stat['id_dones'] = $id_dones;
            $stat['id_user'] = $this->data['active_user']['id_user'];
            $stat['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_REFUSE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_REFUSE_SD_RCU : 0);
            $is_refused_sd = $this->logs_model->get_dones_satatus_by_user($stat);


            $history_actions = array();

            if (isset($id_dones) && !empty($id_dones) && isset($description_refuse) && !empty($description_refuse)) {

                if ($is_refresh == 0 && empty($is_refused_sd)) {//new refuse
                    $history_actions['id_dones'] = $id_dones;

                    //status
                    if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) {//umchs
                        //statuses to history
                        $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_UMCHS, Logs_model::ACTION_PROVE_SD_UMCHS);

                        $history_actions['history_actions'] = array(Logs_model::ACTION_PROVE_SD_UMCHS);
                        $this->logs_model->delete_dones_statuses($history_actions);

                        // refuse only current user
                        $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_UMCHS);
                        $history_actions['id_user'] = $this->data['active_user']['id_user'];
                        $this->logs_model->delete_dones_statuses_of_user($history_actions);
                    } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) {//rcu
                        //statuses to history
                        $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_RCU, Logs_model::ACTION_PROVE_SD_RCU);

                        $history_actions['history_actions'] = array(Logs_model::ACTION_PROVE_SD_RCU);
                        $this->logs_model->delete_dones_statuses($history_actions);


                        // refuse only current user
                        $history_actions['history_actions'] = array(Logs_model::ACTION_REFUSE_SD_RCU);
                        $history_actions['id_user'] = $this->data['active_user']['id_user'];
                        $this->logs_model->delete_dones_statuses_of_user($history_actions);
                    }
                }


                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;


                if ($is_refresh == 1 || !empty($is_refused_sd)) {//refresh refuse text
                    //$logs['is_service'] = 1;
                    $prev['id_dones'] = $id_dones;
                    $prev['id_user'] = $this->data['active_user']['id_user'];
                    $prev['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_REFUSE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_REFUSE_SD_RCU : 0);
                    $prev_description_refuse = $this->logs_model->get_dones_description_refuse($prev);

                    $logs['description_refuse'] = 'предыдущее: ' . $prev_description_refuse['description_refuse'] . ' новое: ' . $description_refuse;

                    $logs['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_UPDATE_REFUSE_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_UPDATE_REFUSE_RCU : 0);
                    $logs['date_action'] = date("Y-m-d H:i:s");
                    $this->logs_model->add_logs($logs);

                    //update refuse text
                    $logs['description_refuse'] = $description_refuse;
                    $logs['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_REFUSE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_REFUSE_SD_RCU : 0);
                    $this->logs_model->update_dones_description_refuse($logs);
                } else {

                    $logs['description_refuse'] = $description_refuse;
                    $logs['id_action'] = ($this->data['active_user']['level'] == Main_model::LEVEL_ID_UMCHS) ? Logs_model::ACTION_REFUSE_SD_UMCHS : (($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU) ? Logs_model::ACTION_REFUSE_SD_RCU : 0);
                    $logs['date_action'] = date("Y-m-d H:i:s");
                    $this->logs_model->add_logs($logs);
                }




                echo json_encode(array('success' => 1));
            } else {
                echo json_encode(array('error' => 'Укажите замечания'));
            }
        }
    }

    public function open_update_sd($id_dones = 0)
    {
        if ($this->input->is_ajax_request()) {

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            if ($this->data['active_user']['level'] != Main_model::LEVEL_ID_RCU) {

                echo json_encode(array('error' => 'Функция не доступна. У Вас нет прав.'));
                die();
            } elseif ($this->data['dones']['is_open_update'] == 1) {

                echo json_encode(array('error' => 'Функция не доступна. Доступ уже открыт.'));
                die();
            }


            if (isset($id_dones) && !empty($id_dones)) {
                $this->dones_model->set_open_update($id_dones, 1);

                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;
                $logs['id_action'] = Logs_model::ACTION_OPEN;
                $logs['date_action'] = date("Y-m-d H:i:s");
                $this->logs_model->add_logs($logs);

                echo json_encode(array('success' => 'Доступ на редактирование СД успешно открыт'));
            } else {
                echo json_encode(array('error' => 'СД не найдено'));
            }
        }
    }

    public function close_update_sd($id_dones = 0)
    {
        if ($this->input->is_ajax_request()) {

            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            if ($this->data['active_user']['level'] != Main_model::LEVEL_ID_RCU) {

                echo json_encode(array('error' => 'Функция не доступна. У Вас нет прав.'));
                die();
            } elseif ($this->data['dones']['is_open_update'] == 0) {

                echo json_encode(array('error' => 'Функция не доступна. Доступ уже закрыт.'));
                die();
            }


            if (isset($id_dones) && !empty($id_dones)) {
                $this->dones_model->set_open_update($id_dones, 0);

                //logs
                $logs['id_user'] = $this->data['active_user']['id_user'];
                if ($this->data['active_user']['is_guest'] == 1) {
                    $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                    $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                    $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                    $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
                }
                $logs['id_dones'] = $id_dones;
                $logs['id_action'] = Logs_model::ACTION_CLOSE;
                $logs['date_action'] = date("Y-m-d H:i:s");
                $this->logs_model->add_logs($logs);

                echo json_encode(array('success' => 'Доступ на редактирование СД успешно закрыт'));
            } else {
                echo json_encode(array('error' => 'СД не найдено'));
            }
        }
    }

    public function copy_standart_sd($id_dones = 0)
    {

        if (isset($id_dones) && !empty($id_dones)) {

            /* dones */
            $dones = $this->create_model->get_dones_by_id($id_dones);

            $type_sd = $dones['type'];

            if ($type_sd == Main_model::TYPE_SD_STANDART) {
                /* data of  dones */
                $silymchs = $this->create_model->get_dones_silymchs($id_dones);
                $innerservice = $this->create_model->get_dones_innerservice($id_dones);
                if (isset($innerservice) && !empty($innerservice)) {//works of each innerservice row
                    foreach ($innerservice as $key => $row) {
                        $works = $this->create_model->get_dones_innerservice_work($row['id']);
                        $ids_work = (isset($works) && !empty($works)) ? array_column($works, 'id_work_innerservice') : array();
                        $innerservice[$key]['works'] = $ids_work;
                    }
                }
                $informing = $this->create_model->get_dones_informing($id_dones);
                $str = $this->create_model->get_dones_str($id_dones);
                $str_text = $this->create_model->get_dones_str_text($id_dones);
                $trunks = $this->create_model->get_dones_trunks($id_dones);

                $water_source = $this->create_model->get_dones_water_source($id_dones);
                $object = $this->create_model->get_dones_object($id_dones);

                $live_together = $this->dones_model->get_dones_live_together($id_dones);
            }


            $new_dones = array();
            $new_dones['is_copy'] = 1;
            $new_dones['copy_parent_id'] = $id_dones;

            $new_dones['specd_date'] = date("Y-m-d H:i:s");
            $new_dones['short_description'] = (isset($dones['short_description']) && !empty($dones['short_description'])) ? trim($dones['short_description']) : '';

            $new_dones['created_by'] = $this->data['active_user']['id_user'];
            $new_dones['date_insert'] = date("Y-m-d H:i:s");

            $new_dones['last_updated_by'] = $this->data['active_user']['id_user'];
            $new_dones['date_last_update'] = date("Y-m-d H:i:s");

            /* official block */
            $new_dones['specd_vid'] = (isset($dones['specd_vid']) && !empty($dones['specd_vid'])) ? intval($dones['specd_vid']) : 0;

            $new_dones['official_creator_name'] = (isset($dones['official_creator_name']) && !empty($dones['official_creator_name'])) ? trim($dones['official_creator_name']) : '';
            $new_dones['official_creator_position'] = (isset($dones['official_creator_position']) && !empty($dones['official_creator_position'])) ? trim($dones['official_creator_position']) : '';
            $new_dones['official_destination'] = (isset($dones['official_destination']) && !empty($dones['official_destination'])) ? trim($dones['official_destination']) : '';



            /* owner */
            $new_dones['id_face_belong'] = $dones['id_face_belong'];
            $new_dones['id_owner_category'] = $dones['id_owner_category'];
            $new_dones['owner_fio'] = $dones['owner_fio'];
            $new_dones['owner_year_birthday'] = $dones['owner_year_birthday'];
            $new_dones['owner_address'] = $dones['owner_address'];
            $new_dones['owner_position'] = $dones['owner_position'];
            $new_dones['owner_job'] = $dones['owner_job'];
            $new_dones['owner_character'] = $dones['owner_character'];
            $new_dones['owner_is_uhet'] =  $dones['owner_is_uhet'];
            $new_dones['owner_live_together'] =  $dones['owner_live_together'];
            $new_dones['law_face_office_belong'] = $dones['law_face_office_belong'];
            $new_dones['law_face_name_owner'] = $dones['law_face_name_owner'];
            $new_dones['is_show_owner']=$dones['is_show_owner'];
            $new_dones['owner_word']=$dones['owner_word'];
            $new_dones['object_word']=$dones['object_word'];


                    /* start text SD */
            $new_dones['is_show_opening_descr'] = $dones['is_show_opening_descr'];
            $new_dones['opening_word'] = $dones['opening_word'];


            $new_dones['opening_description'] = (isset($dones['opening_description']) && !empty($dones['opening_description'])) ? trim($dones['opening_description']) : '';


            /* description of RIG */
            $new_dones['id_rig'] = (isset($dones['id_rig_current']) && !empty($dones['id_rig_current'])) ? intval($dones['id_rig_current']) : 0;
            $new_dones['time_msg'] = (isset($dones['time_msg']) && !empty($dones['time_msg'])) ? $dones['time_msg'] : NULL;
            $new_dones['time_loc'] = (isset($dones['time_loc']) && !empty($dones['time_loc'])) ? $dones['time_loc'] : NULL;
            $new_dones['time_likv'] = (isset($dones['time_likv']) && !empty($dones['time_likv'])) ? $dones['time_likv'] : NULL;
            $new_dones['podr_take_msg'] = (isset($dones['podr_take_msg']) && !empty($dones['podr_take_msg'])) ? trim($dones['podr_take_msg']) : '';
            $new_dones['disp_take_msg'] = (isset($dones['disp_take_msg']) && !empty($dones['disp_take_msg'])) ? trim($dones['disp_take_msg']) : '';
            $new_dones['address'] = (isset($dones['address']) && !empty($dones['address'])) ? trim($dones['address']) : '';
            $new_dones['latitude'] = (isset($dones['latitude']) && !empty($dones['latitude'])) ? trim($dones['latitude']) : '';
            $new_dones['longitude'] = (isset($dones['longitude']) && !empty($dones['longitude'])) ? trim($dones['longitude']) : '';

            $new_dones['is_show_coords'] = (isset($dones['is_show_coords']) && !empty($dones['is_show_coords'])) ? 1 : 0;

            $new_dones['vid_hs_1'] = (isset($dones['vid_hs_1']) && !empty($dones['vid_hs_1'])) ? intval($dones['vid_hs_1']) : 0;
            $new_dones['vid_hs_2'] = (isset($dones['vid_hs_2']) && !empty($dones['vid_hs_2'])) ? intval($dones['vid_hs_2']) : 0;
            $new_dones['reason_rig'] = (isset($dones['reason_rig']) && !empty($dones['reason_rig'])) ? trim($dones['reason_rig']) : '';
            $new_dones['firereason_rig'] = (isset($dones['firereason_rig']) && !empty($dones['firereason_rig'])) ? trim($dones['firereason_rig']) : '';
            $new_dones['id_firereason'] = (isset($dones['id_firereason']) && !empty($dones['id_firereason'])) ? trim($dones['id_firereason']) : 0;

            $new_dones['inspector'] = (isset($dones['inspector']) && !empty($dones['inspector'])) ? trim($dones['inspector']) : '';



            /* gdzs block */
            $new_dones['spec_cnt_gdzs'] = (isset($dones['spec_cnt_gdzs']) && !empty($dones['spec_cnt_gdzs'])) ? intval($dones['spec_cnt_gdzs']) : 0;
            $new_dones['spec_time_work_gdzs'] = (isset($dones['spec_time_work_gdzs']) && !empty($dones['spec_time_work_gdzs'])) ? $dones['spec_time_work_gdzs'] : '';
            $new_dones['spec_time_work_bef_inj_gdzs'] = (isset($dones['spec_time_work_bef_inj_gdzs']) && !empty($dones['spec_time_work_bef_inj_gdzs'])) ? $dones['spec_time_work_bef_inj_gdzs'] : '';
            $new_dones['spec_time_shtab_gdzs'] = (isset($dones['spec_time_shtab_gdzs']) && !empty($dones['spec_time_shtab_gdzs'])) ? $dones['spec_time_shtab_gdzs'] : '';


            /* people data block */
            $new_dones['people_fio'] = (isset($dones['people_fio']) && !empty($dones['people_fio'])) ? trim($dones['people_fio']) : '';
            $new_dones['people_phone'] = (isset($dones['people_phone']) && !empty($dones['people_phone'])) ? trim($dones['people_phone']) : '';
            $new_dones['people_address'] = (isset($dones['people_address']) && !empty($dones['people_address'])) ? trim($dones['people_address']) : '';
            $new_dones['people_position'] = (isset($dones['people_position']) && !empty($dones['people_position'])) ? trim($dones['people_position']) : '';
            $new_dones['people_status'] = (isset($dones['people_status']) && !empty($dones['people_status'])) ? intval($dones['people_status']) : 0;



            /* detail inf block */
            $new_dones['detail_inf'] = (isset($dones['detail_inf']) && !empty($dones['detail_inf'])) ? trim($dones['detail_inf']) : '';


            /* prevention block */
            $new_dones['prevention_time'] = (isset($dones['prevention_time']) && !empty($dones['prevention_time'])) ? $dones['prevention_time'] : '';
            $new_dones['prevention_who'] = (isset($dones['prevention_who']) && !empty($dones['prevention_who'])) ? trim($dones['prevention_who']) : '';
            $new_dones['prevention_result'] = (isset($dones['prevention_result']) && !empty($dones['prevention_result'])) ? trim($dones['prevention_result']) : '';
            $new_dones['prevention_events'] = (isset($dones['prevention_events']) && !empty($dones['prevention_events'])) ? trim($dones['prevention_events']) : '';


            /* is involved or no */
            $new_dones['is_not_involved_silymchs'] = (isset($dones['is_not_involved_silymchs']) && !empty($dones['is_not_involved_silymchs'])) ? 1 : 0;
            $new_dones['is_not_involved_innerservice'] = (isset($dones['is_not_involved_innerservice']) && !empty($dones['is_not_involved_innerservice'])) ? 1 : 0;
            $new_dones['is_not_involved_informing'] = (isset($dones['is_not_involved_informing']) && !empty($dones['is_not_involved_informing'])) ? 1 : 0;
            $new_dones['is_not_involved_trunks'] = (isset($dones['is_not_involved_trunks']) && !empty($dones['is_not_involved_trunks'])) ? 1 : 0;
            $new_dones['is_wide_table_trunks'] = (isset($dones['is_wide_table_trunks']) && !empty($dones['is_wide_table_trunks'])) ? 1 : 0;
            $new_dones['is_not_involved_str'] = (isset($dones['is_not_involved_str']) && !empty($dones['is_not_involved_str'])) ? 1 : 0;

            $new_dones['is_test_sd'] = (isset($dones['is_test_sd']) && !empty($dones['is_test_sd'])) ? 1 : 0;
            $new_dones['type'] = $dones['type'];

            $new_dones['is_show_address'] = (isset($dones['is_show_address']) && !empty($dones['is_show_address'])) ? 1 : 0;
            $new_dones['is_show_object'] = (isset($dones['is_show_object']) && !empty($dones['is_show_object'])) ? 1 : 0;
            $new_dones['is_show_prevention'] = (isset($dones['is_show_prevention']) && !empty($dones['is_show_prevention'])) ? 1 : 0;



            if ($this->data['active_user']['is_guest'] == 1) {
                $new_dones['fio_jour'] = $this->data['active_user']['auth_fio'];
                $new_dones['position_name_jour'] = $this->data['active_user']['position_name'];
                $new_dones['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $new_dones['creator_name_jour'] = $this->data['active_user']['creator_name'];
                if (isset($this->data['active_user']['id_user_jour']) && !empty($this->data['active_user']['id_user_jour']))
                    $new_dones['id_user_jour'] = $this->data['active_user']['id_user_jour'];
            }


            $id_dones_new = $this->create_model->add_new_dones($new_dones);

            //logs
            $logs['id_user'] = $this->data['active_user']['id_user'];
            if ($this->data['active_user']['is_guest'] == 1) {
                $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
            }
            $logs['id_dones'] = $id_dones_new;
            $logs['is_service'] = 1;
            $logs['id_action'] = Logs_model::ACTION_COPY_SD;
            $logs['date_action'] = date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);


            if ($type_sd == Main_model::TYPE_SD_SIMPLE) {
                redirect('/dones/edit_form_simple/' . $id_dones_new);
            }


            /* live together */
            $this->dones_model->delete_dones_live_together($id_dones_new);
            if(isset($live_together) && !empty($live_together)){
                 foreach ($live_together as $k => $row) {
                    $dones_live_together = array();
                    $dones_live_together['id_dones'] = $id_dones_new;
                    $dones_live_together['fio'] = $row['fio'];
                    $dones_live_together['year_birthday'] = $row['year_birthday'];
                    $dones_live_together['note'] = $row['note'];
                    $dones_live_together['sort'] = $row['sort'];
                    $this->dones_model->add_dones_live_together($dones_live_together);
                 }
            }



            /* ------------ silymchs of dones 1-∞ ------------- */
            if (isset($silymchs) && !empty($silymchs) && $dones['is_not_involved_silymchs'] == 0) {
                foreach ($silymchs as $k => $row) {
                    $dones_silymchs = array();
                    if (isset($row['mark']) && !empty(trim($row['mark']))) {

                        $dones_silymchs['id_dones'] = $id_dones_new;
                        $dones_silymchs['mark'] = trim($row['mark']);
                        $dones_silymchs['id_teh'] = (isset($row['id_teh']) && !empty($row['id_teh'])) ? intval($row['id_teh']) : 0;
                        $dones_silymchs['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                        $dones_silymchs['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                        $dones_silymchs['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? (trim($row['v_ac']) * 1000) : 0;
                        $dones_silymchs['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                        $dones_silymchs['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? $row['time_exit'] : '';
                        $dones_silymchs['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? $row['time_arrival'] : '';
                        $dones_silymchs['time_follow'] = (isset($row['time_follow']) && !empty($row['time_follow'])) ? intval($row['time_follow']) : 0;
                        $dones_silymchs['distance'] = (isset($row['distance']) && !empty($row['distance'])) ? trim($row['distance']) : '';
                        $dones_silymchs['time_end'] = (isset($row['time_end']) && !empty($row['time_end'])) ? $row['time_end'] : '';
                        $dones_silymchs['time_return'] = (isset($row['time_return']) && !empty($row['time_return'])) ? $row['time_return'] : '';
                        $dones_silymchs['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                        $this->create_model->add_new_dones_silymchs($dones_silymchs);
                    }
                }
            }



            /* ------------ innerservice of dones 1-∞ ------------- */
            if (isset($innerservice) && !empty($innerservice) && $dones['is_not_involved_innerservice'] == 0) {
                foreach ($innerservice as $k => $row) {
                    $dones_innerservice = array();
                    if (isset($row['service_id']) && !empty(intval($row['service_id']))) {

                        $dones_innerservice['id_dones'] = $id_dones_new;
                        $dones_innerservice['service_id'] = intval($row['service_id']);
                        $dones_innerservice['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? $row['time_msg'] : '';
                        $dones_innerservice['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? $row['time_arrival'] : '';
                        $dones_innerservice['distance'] = (isset($row['distance']) && !empty($row['distance'])) ? trim($row['distance']) : '';
                        $dones_innerservice['note'] = (isset($row['note']) && !empty($row['note'])) ? trim($row['note']) : '';
                        $dones_innerservice['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                        $id_dones_innerservice_new = $this->create_model->add_new_dones_innerservice($dones_innerservice);
                        //add work innerservice
                        if (isset($row['works']) && !empty($row['works'])) {
                            foreach ($row['works'] as $value) {
                                $arr['id_dones_innerservice'] = $id_dones_innerservice_new;
                                $arr['id_work_innerservice'] = $value;
                                $this->create_model->add_new_dones_innerservice_work($arr);
                            }
                        }
                    }
                }
            }






            /* ------------ informing of dones 1-∞ ------------- */

            if (isset($informing) && !empty($informing) && $dones['is_not_involved_informing'] == 0) {
                foreach ($informing as $k => $row) {
                    $dones_informing = array();
                    if (isset($row['fio']) && !empty(trim($row['fio']))) {

                        $dones_informing['id_dones'] = $id_dones_new;
                        $dones_informing['fio'] = trim($row['fio']);
                        $dones_informing['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? $row['time_msg'] : '';
                        $dones_informing['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? $row['time_exit'] : '';
                        $dones_informing['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? $row['time_arrival'] : '';
                        $dones_informing['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                        $this->create_model->add_new_dones_informing($dones_informing);
                    }
                }
            }
        }


        /* ------------ str of dones 1-∞ ------------- */
        if (isset($str) && !empty($str)) {
            foreach ($str as $k => $row) {
                $dones_str = array();
                if (isset($row['pasp_name']) && !empty(trim($row['pasp_name']))) {

                    $dones_str['id_dones'] = $id_dones_new;
                    $dones_str['id_pasp'] = (isset($row['id_pasp']) && !empty($row['id_pasp'])) ? intval($row['id_pasp']) : 0;
                    $dones_str['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_str['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_str['shtat'] = (isset($row['shtat']) && !empty($row['shtat'])) ? intval($row['shtat']) : 0;
                    $dones_str['vacant'] = (isset($row['vacant']) && !empty($row['vacant'])) ? intval($row['vacant']) : 0;
                    $dones_str['on_list_ch'] = (isset($row['on_list_ch']) && !empty($row['on_list_ch'])) ? intval($row['on_list_ch']) : 0;
                    $dones_str['vacant_ch'] = (isset($row['vacant_ch']) && !empty($row['vacant_ch'])) ? intval($row['vacant_ch']) : 0;
                    $dones_str['face_ch'] = (isset($row['face_ch']) && !empty($row['face_ch'])) ? intval($row['face_ch']) : 0;
                    $dones_str['br_ch'] = (isset($row['br_ch']) && !empty($row['br_ch'])) ? intval($row['br_ch']) : 0;
                    $dones_str['cnt_trip_man'] = (isset($row['cnt_trip_man']) && !empty($row['cnt_trip_man'])) ? intval($row['cnt_trip_man']) : 0;
                    $dones_str['cnt_holiday_man'] = (isset($row['cnt_holiday_man']) && !empty($row['cnt_holiday_man'])) ? intval($row['cnt_holiday_man']) : 0;
                    $dones_str['cnt_ill_man'] = (isset($row['cnt_ill_man']) && !empty($row['cnt_ill_man'])) ? intval($row['cnt_ill_man']) : 0;
                    $dones_str['cnt_naryd'] = (isset($row['cnt_naryd']) && !empty($row['cnt_naryd'])) ? intval($row['cnt_naryd']) : 0;
                    $dones_str['cnt_other_man'] = (isset($row['cnt_other_man']) && !empty($row['cnt_other_man'])) ? intval($row['cnt_other_man']) : 0;
                    $dones_str['gas'] = (isset($row['gas']) && !empty($row['gas'])) ? intval($row['gas']) : 0;
                    $dones_str['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $this->create_model->add_new_dones_str($dones_str);
                }
            }
        }



        /* ------------ str text of dones 1-∞ ------------- */

        if (isset($str_text) && !empty($str_text)) {
            foreach ($str_text as $k => $row) {
                $dones_str_text = array();
                if (isset($row['str_text_podr_name']) && !empty(trim($row['str_text_podr_name']))) {

                    $dones_str_text['id_dones'] = $id_dones_new;
                    $dones_str_text['id_pasp'] = (isset($row['id_pasp']) && !empty($row['id_pasp'])) ? intval($row['id_pasp']) : 0;
                    $dones_str_text['str_text_podr_name'] = (isset($row['str_text_podr_name']) && !empty($row['str_text_podr_name'])) ? trim($row['str_text_podr_name']) : '';
                    $dones_str_text['str_text_description'] = (isset($row['str_text_description']) && !empty($row['str_text_description'])) ? trim($row['str_text_description']) : '';
                    $dones_str_text['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $this->create_model->add_new_dones_str_text($dones_str_text);
                }
            }
        }





        /* ------------ trunks of dones 1-∞ ------------- */

        if (isset($trunks) && !empty($trunks)) {
            foreach ($trunks as $k => $row) {
                $dones_trunks = array();
                if (isset($row['mark']) && !empty(trim($row['mark']))) {

                    $dones_trunks['id_dones'] = $id_dones_new;
                    $dones_trunks['mark'] = trim($row['mark']);
                    $dones_trunks['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_trunks['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_trunks['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? (trim($row['v_ac']) * 1000) : 0;
                    $dones_trunks['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                    $dones_trunks['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? $row['time_arrival'] : '';
                    $dones_trunks['s_fire_arrival'] = (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? trim($row['s_fire_arrival']) : '';
                    $dones_trunks['time_pod'] = (isset($row['time_pod']) && !empty($row['time_pod'])) ? $row['time_pod'] : NULL;
                    $dones_trunks['means_trunks'] = (isset($row['means_trunks']) && !empty($row['means_trunks'])) ? trim($row['means_trunks']) : '';
                    $dones_trunks['water_po_out'] = (isset($row['water_po_out']) && !empty($row['water_po_out'])) ? trim($row['water_po_out']) : '';
                    $dones_trunks['time_loc'] = (isset($row['time_loc']) && !empty($row['time_loc'])) ? $row['time_loc'] : '';
                    $dones_trunks['s_fire_loc'] = (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? trim($row['s_fire_loc']) : '';
                    $dones_trunks['time_likv'] = (isset($row['time_likv']) && !empty($row['time_likv'])) ? $row['time_likv'] : '';
                    $dones_trunks['actions_ls'] = (isset($row['actions_ls']) && !empty($row['actions_ls'])) ? trim($row['actions_ls']) : '';
                    $dones_trunks['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $this->create_model->add_new_dones_trunks($dones_trunks);
                }
            }
        }






        /* ------------ water source of dones 1-∞ ------------- */

        if (isset($water_source) && !empty($water_source)) {
            foreach ($water_source as $k => $row) {
                $dones_water_source = array();
                if (isset($row['water_source_type']) && !empty(intval($row['water_source_type']))) {

                    $dones_water_source['id_dones'] = $id_dones_new;
                    $dones_water_source['water_source_type'] = intval($row['water_source_type']);
                    $dones_water_source['water_source_distance'] = (isset($row['water_source_distance']) && !empty($row['water_source_distance'])) ? trim($row['water_source_distance']) : '';
                    $dones_water_source['water_source_use'] = (isset($row['water_source_use']) && !empty($row['water_source_use'])) ? trim($row['water_source_use']) : '';
                    $dones_water_source['sort'] = (isset($row['sort']) && !empty($row['sort'])) ? intval($row['sort']) : 0;

                    $this->create_model->add_new_dones_water_source($dones_water_source);
                }
            }
        }






        /* ------------ object of dones 1-1 ------------- */
        if (isset($object) && !empty($object)) {
            $dones_object['id_dones'] = $id_dones_new;
            $dones_object['object'] = (isset($object['object']) && !empty($object['object'])) ? trim($object['object']) : '';
            $dones_object['object_office_belong'] = (isset($object['object_office_belong']) && !empty($object['object_office_belong'])) ? intval($object['object_office_belong']) : 0;
            $dones_object['object_house'] = (isset($object['object_house']) && !empty($object['object_house'])) ? intval($object['object_house']) : 0;
            $dones_object['object_floor'] = (isset($object['object_floor']) && !empty($object['object_floor'])) ? trim($object['object_floor']) : '';
            $dones_object['object_size'] = (isset($object['object_size']) && !empty($object['object_size'])) ? trim($object['object_size']) : '';
            $dones_object['object_is_electric'] = (isset($object['object_is_electric']) && !empty($object['object_is_electric'])) ? intval($object['object_is_electric']) : 0;
            $dones_object['object_is_api'] = (isset($object['object_is_api']) && !empty($object['object_is_api'])) ? intval($object['object_is_api']) : 0;
            $dones_object['object_material'] = (isset($object['object_material']) && !empty($object['object_material'])) ? intval($object['object_material']) : 0;
            $dones_object['object_roof'] = (isset($object['object_roof']) && !empty($object['object_roof'])) ? intval($object['object_roof']) : 0;

            $dones_object['api_date'] = $object['api_date'];
            $dones_object['id_api_source'] = $object['id_api_source'];
            $dones_object['is_api_worked'] = $object['is_api_worked'];
            $dones_object['is_api_influence'] = $object['is_api_influence'];

            $dones_object['is_aps'] = $object['is_aps'];
            $dones_object['aps_name'] = $object['aps_name'];
            $dones_object['is_aps_worked'] = $object['is_aps_worked'];
            $dones_object['is_aps_influence'] = $object['is_aps_influence'];


            $this->create_model->add_new_dones_object($dones_object);
        }

        redirect('/dones/edit_form_standart/' . $id_dones_new);
    }

    public function simple_save()
    {

        $post = $this->input->post();
        //print_r($post);exit();
        $dones = array();

        $id_dones = (isset($post['id_dones']) && !empty($post['id_dones'])) ? intval($post['id_dones']) : 0; //id of edit dones


        if ($id_dones != 0) {// edit SD
            $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

            $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, false);
            $statuses_id = array_column($statuses, 'id_action');


            /* ROSN, UGZ, AVIA */
            if ($this->data['active_user']['level'] == 3 &&
                (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA]))) {

                if (($this->data['active_user']['id_local'] !== $this->data['dones']['author_local_id']) && $this->data['active_user']['id_region'] != Main_model::REGION_MINSK) {

                    redirect('creator/catalog');
                    die();
                } elseif ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                    redirect('creator/catalog');
                    die();
                }
            } else {
                /*  author SD = current user
                 * proved umchs and proved rcu and not open update
                 * proved rcu and not open update
                 *          */
                if ($this->data['active_user']['level'] == 3 && (($this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    redirect('creator/catalog');
                    die();
                } elseif ($this->data['active_user']['level'] == 2 && (($this->data['active_user']['id_region'] != $this->data['dones']['author_region_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    redirect('creator/catalog');
                    die();
                }
            }
        }


        /* settings */
        $settings = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $settings = $this->user_model->get_user_settings_options_format($settings);

        $dones['is_copy'] = 0;
        $dones['copy_parent_id'] = 0;

        $dones['specd_date'] = (isset($post['specd_date']) && !empty($post['specd_date'])) ? (\DateTime::createFromFormat('d.m.Y', $post['specd_date'])->format('Y-m-d')) : null;
        $dones['specd_number'] = (isset($post['specd_number']) && !empty($post['specd_number'])) ? trim($post['specd_number']) : '';
        $dones['short_description'] = (isset($post['short_description']) && !empty($post['short_description'])) ? trim($post['short_description']) : '';

        /* who creates/last updates and when */
        if ($id_dones == 0) {//create a new
            $dones['created_by'] = $this->data['active_user']['id_user'];
            $dones['date_insert'] = date("Y-m-d H:i:s");
        }
        $dones['last_updated_by'] = $this->data['active_user']['id_user'];
        $dones['date_last_update'] = date("Y-m-d H:i:s");

        /* official block */
        $dones['specd_vid'] = (isset($post['specd_vid']) && !empty($post['specd_vid'])) ? intval($post['specd_vid']) : 0;

        if ($id_dones == 0) {//create a new
            $dones['official_date_start'] = (isset($post['official_date_start']) && !empty($post['official_date_start'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start'])->format('Y-m-d H:i:s')) : null;
            $dones['official_date_end'] = date("Y-m-d H:i:s"); // if create new dones
        } else {//edit dones
            $dones['official_date_start_edit'] = (isset($post['official_date_start_edit']) && !empty($post['official_date_start_edit'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start_edit'])->format('Y-m-d H:i:s')) : null;
            $dones['official_date_end_edit'] = date("Y-m-d H:i:s");

            if ($this->data['dones']['is_copy'] == 1) {
                $dones['official_date_start'] = $dones['official_date_start_edit'];
                $dones['official_date_end'] = $dones['official_date_end_edit'];
            }
        }

        $dones['official_creator_name'] = (isset($post['official_creator_name']) && !empty($post['official_creator_name'])) ? trim($post['official_creator_name']) : '';
        $dones['official_creator_position'] = (isset($post['official_creator_position']) && !empty($post['official_creator_position'])) ? trim($post['official_creator_position']) : '';
        $dones['official_destination'] = (isset($post['official_destination']) && !empty($post['official_destination'])) ? trim($post['official_destination']) : '';

        $dones['opening_description'] = (isset($post['opening_description']) && !empty($post['opening_description'])) ? trim($post['opening_description']) : '';


        /* description of RIG */
        if (!empty($settings) && isset($settings['is_seconds_show']) && in_array('yes', $settings['is_seconds_show'])) {
            $dones['time_msg'] = (isset($post['time_msg']) && !empty($post['time_msg'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', trim($post['time_msg']))->format('Y-m-d H:i:s')) : NULL;
        } else {
            $dones['time_msg'] = (isset($post['time_msg']) && !empty($post['time_msg'])) ? (\DateTime::createFromFormat('d.m.Y H:i', trim($post['time_msg']))->format('Y-m-d H:i:s')) : NULL;
        }

        $dones['latitude'] = (isset($post['latitude']) && !empty($post['latitude'])) ? trim($post['latitude']) : '';
        $dones['longitude'] = (isset($post['longitude']) && !empty($post['longitude'])) ? trim($post['longitude']) : '';

        $dones['is_show_coords'] = (isset($post['is_show_coords']) && !empty($post['is_show_coords'])) ? 1 : 0;



        $dones['is_test_sd'] = (isset($post['is_test_sd']) && !empty($post['is_test_sd'])) ? 1 : 0;

        $dones['file_doc'] = (isset($post['file_doc']) && !empty($post['file_doc'])) ? $post['file_doc'] : null;

        // type SD
        $dones['type'] = Main_model::TYPE_SD_SIMPLE;

        /* insert/edit dones */
        if ($id_dones == 0) {//create a new
            if ($this->data['active_user']['is_guest'] == 1) {
                $dones['fio_jour'] = $this->data['active_user']['auth_fio'];
                $dones['position_name_jour'] = $this->data['active_user']['position_name'];
                $dones['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $dones['creator_name_jour'] = $this->data['active_user']['creator_name'];
                if (isset($this->data['active_user']['id_user_jour']) && !empty($this->data['active_user']['id_user_jour']))
                    $dones['id_user_jour'] = $this->data['active_user']['id_user_jour'];
            }



            $id_dones_new = $this->create_model->add_new_dones($dones);

            //logs
            $logs['id_user'] = $this->data['active_user']['id_user'];
            if ($this->data['active_user']['is_guest'] == 1) {
                $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
            }
            $logs['id_dones'] = $id_dones_new;
            //$logs['id_action']=self::actions['create_sd'];
            $logs['id_action'] = Logs_model::ACTION_CREATE_SD;
            $logs['date_action'] = date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
        } else {//edit
            $this->create_model->edit_dones($id_dones, $dones);
            $id_dones_new = $id_dones;

            //status
            if ($this->data['active_user']['level'] == Main_model::LEVEL_ID_ROCHS) {//grochs
                //statuses to history
                $history_actions['history_actions'] = array(Logs_model::ACTION_EDIT_SD, Logs_model::ACTION_PROVE_SD_UMCHS, Logs_model::ACTION_PROVE_SD_RCU);
            } else {
                //statuses to history
                $history_actions['history_actions'] = array(Logs_model::ACTION_EDIT_SD);
            }

            $history_actions['id_dones'] = $id_dones_new;
            $this->logs_model->delete_dones_statuses($history_actions);

            //set new status
            $logs['id_user'] = $this->data['active_user']['id_user'];
            if ($this->data['active_user']['is_guest'] == 1) {
                $logs['fio_jour'] = $this->data['active_user']['auth_fio'];
                $logs['position_name_jour'] = $this->data['active_user']['position_name'];
                $logs['rank_name_jour'] = $this->data['active_user']['rank_name'];
                $logs['creator_name_jour'] = $this->data['active_user']['creator_name'];
            }
            $logs['id_dones'] = $id_dones_new;
            $logs['id_action'] = Logs_model::ACTION_EDIT_SD;
            $logs['date_action'] = date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
        }


        /* media */
        $this->dones_model->delete_dones_media($id_dones_new);

        if (isset($post['sd_media']) && !empty($post['sd_media'])) {

            foreach ($post['sd_media'] as $type => $row) {

                if (!empty($row)) {
                    foreach ($row as $file) {
                        $media = [];
                        if (!empty($file)) {
                            $media['id_dones'] = $id_dones_new;
                            $media['file'] = $file;
                            $media['type'] = $type;

                            $this->dones_model->add_dones_media($media);
                        }
                    }
                }
            }
        }

        redirect('/creator/catalog');
    }

    public function edit_form_simple($id_dones = 0)
    {

        $this->data['type_sd'] = Main_model::TYPE_SD_SIMPLE;
        $this->data['title'] = 'Ред спец.донесение (простое)';
        $this->data['active_item_menu'] = 'create';
        $this->data['is_show_btn_search_rig'] = 0; //don't show btn "search rig"

        $this->data['bread_crumb'] = array(array('/dones' => 'Редактировать специальное донесение'),
            array('Простое'));

        /* settings */
        $this->data['settings'] = $this->user_model->get_user_settings_type_sd($this->data['active_user']['id_user'], Main_model::TYPE_SD_STANDART);
        $this->data['settings'] = $this->user_model->get_user_settings_options_format($this->data['settings']);

        $this->data['vid_specd'] = $this->main_model->get_vid_specd();
        $this->data['minirovanie_id']= Main_model::VID_SD_MINIROVANIE;

        $this->data['dones'] = $this->create_model->get_dones_by_id($id_dones);

        $statuses = $this->dones_model->get_statuses_by_id_dones($id_dones, 0, false);
        $statuses_id = array_column($statuses, 'id_action');



        if ($this->session->userdata('can_edit') == 0) {// viewer can see SD
            $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
        } else {


            /* ROSN, UGZ, AVIA */
            if ($this->data['active_user']['level'] == 3 &&
                (in_array($this->data['active_user']['id_organ'], [Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA]))) {

                if (($this->data['active_user']['id_local'] !== $this->data['dones']['author_local_id']) && $this->data['active_user']['id_region'] != Main_model::REGION_MINSK) {
                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                } elseif ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                }
            } elseif ($this->data['active_user']['level'] == Main_model::LEVEL_ID_RCU &&
                $this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) {
                $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
            } else {
                /*  author SD = current user
                 * proved umchs and proved rcu and not open update
                 * proved rcu and not open update
                 *          */
                if ($this->data['active_user']['level'] == 3 && (($this->data['active_user']['id_local'] != $this->data['dones']['author_local_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                } elseif ($this->data['active_user']['level'] == 2 && (($this->data['active_user']['id_region'] != $this->data['dones']['author_region_id']) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_UMCHS, $statuses_id) &&
                    in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)) ||
                    ($this->data['dones']['is_open_update'] == 0 && in_array(Logs_model::ACTION_PROVE_SD_RCU, $statuses_id)))) {

                    $this->data['dones']['is_see'] = 1; //only see form not possible save!!!
                }
            }
        }

        if (isset($this->data['dones']['is_see']) && $this->data['dones']['is_see'] == 1) {
            $this->data['title'] = 'Прсмотр. спец.донесения (простое)';
            //$this->data['is_show_btn_search_rig'] = 0; //not show btn "search rig"

            $this->data['bread_crumb'] = array(array('/' => 'Просмотреть специальное донесение (простое)'),
                array('ID = ' . $id_dones)
            );
        }


        $this->data['is_edit_dones'] = 1; //sign of edit dones
        $this->data['id_dones'] = $id_dones; //ID of dones


        $this->data['dones']['preview_opening_description'] = explode(PHP_EOL, $this->data['dones']['opening_description']);



        //media
        $media = $this->dones_model->get_dones_media($id_dones);
        $this->data['dones']['media'] = $media;


        $this->twig->display('create/simple/form_simple', $this->data);
    }

    public function refresh_str($action = 0)
    {

        $action = intval($action);
        if ($action == 0) {
            echo json_encode(array('is_error' => 1, 'msg' => 'Что-то пошло не так'));
        }



        if ($action == 1) {//prev dateduty
            $dateduty = date("Y-m-d", time() - (60 * 60 * 24));
        } elseif ($action == 2) {//current date=today
            $dateduty = date('Y-m-d');
        }
        //echo $dateduty;


        $ids_pasp = $this->input->post('ids_pasp');

        if (isset($ids_pasp) && !empty($ids_pasp)) {


            $result = $this->getStrByIdsPasp(array_unique($ids_pasp), (isset($dateduty) ? $dateduty : null)); //data for table: shtat, vacant...

            if (isset($result) && !empty($result)) {

                echo json_encode(array('pasp' => $result, 'is_error' => 0));
            } else {
                echo json_encode(array('is_error' => 1, 'msg' => 'Данные по подразделениям не найдены'));
            }
        } else {
            echo json_encode(array('is_error' => 1, 'msg' => 'Необходимо выбрать подразделение'));
        }
    }
}
