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



    const actions = [
        'create_sd' => 1,
        'edit_sd'   => 2,
        'delete_sd' => 3,
        'prove_sd'  => 4,
        'refuse_sd' => 5,
    ];

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
            $this->load->model('logs_model');

            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();

            $this->data['reasonrig'] = $this->main_model->get_reasonrig();






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

//        $ids_pasp=array(525,529);
//         print_r($this->getStrByIdsPasp(array_unique($ids_pasp)));
//         exit();

        $this->data['title'] = 'Новое спец.донесение';
        $this->data['active_item_menu'] = 'create';
        $this->data['active_item_menu_type_create'] = 'standart';
        $this->data['is_show_btn_search_rig'] = 1;//show btn "search rig"

        $this->data['bread_crumb'] = array('/dones' => 'Создать специальное донесение', 'Стандартное');


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

        $this->data['regions_cp_list'] =$this->ss_model->set_regions_cp_list();



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
            $this->data['rig']['silymchs'] = $this->journal_model->get_silymchs_by_rig_id($id_rig);


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
                'is_data'         => $is_data
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

        $this->data['active_item_menu'] = 'create';
        $this->data['title'] = 'Новое спец.донесение';
        $this->twig->display('create/simple/form_simple', $this->data);
    }

    public function getStrByIdsPasp($ids_pasp)
    {

        $diviz_organ_of_pasp = $this->str_model->get_inf_by_id_pasp($ids_pasp);

//print_r($diviz_organ_of_pasp);
        foreach ($diviz_organ_of_pasp as $key => $row) {

            $str_table[$row['id_pasp']] = $row;

            if ($row['diviz_id'] == 8 || $row['id_organ'] == 5) {//cou or rcu
                /* ch,dateduty, id_card....From table str.maincou */
                if ($row['diviz_id'] == 8) {
                    $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_maincou_by_id_pasp($row['id_pasp'])); //cou
                } elseif ($row['id_organ'] == 5) {
                    $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_mainrcu_by_id_pasp($row['id_pasp'])); //rcu
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
                $diviz_organ_of_pasp[$key] = array_merge($row, $this->str_model->get_main_by_id_pasp($row['id_pasp']));
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
                            $inf = $inf . '- командировка с ' . $trip['date1'] . (((($trip['date2']) != NULL && $trip['date2']) != '00.00.0000') ? (' по ' . $trip['date2']) : '');
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
                            $inf = $inf . '- отпуск с ' . $hol['date1'] . (((($hol['date2']) != NULL && $hol['date2']) != '00.00.0000') ? (' по ' . $hol['date2']) : '');
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
                            $inf = $inf . '- болен с ' . $ill['date1'] . (((($ill['date2']) != NULL && $ill['date2']) != '00.00.0000') ? (' по ' . $ill['date2']) : '');
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
                            $inf = $inf . '- другие причины с ' . $other['date1'] . (((($other['date2']) != NULL && $other['date2']) != '00.00.0000') ? (' по ' . $other['date2']) : '');
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

        $trunks = $this->journal_model->get_trunks_by_id_rig($id_rig); //table trunks

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
//print_r($post);
//echo '<br><br>';
//        echo $post['specd_number'];echo '<br><br>';
        //echo (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start'])->format('Y-m-d H:i:s'));
//exit();
        $dones = array();

        $id_dones = (isset($post['id_dones']) && !empty($post['id_dones'])) ? intval($post['id_dones']) : 0; //id of edit dones

        $dones['specd_date'] = (isset($post['specd_date']) && !empty($post['specd_date'])) ? (\DateTime::createFromFormat('d.m.Y', $post['specd_date'])->format('Y-m-d')) : '';
        $dones['specd_number'] = (isset($post['specd_number']) && !empty($post['specd_number'])) ? trim($post['specd_number']) : '';

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
            $dones['official_date_start'] = (isset($post['official_date_start']) && !empty($post['official_date_start'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start'])->format('Y-m-d H:i:s')) : '';
            $dones['official_date_end'] = date("Y-m-d H:i:s"); // if create new dones
        } else {//edit dones
            $dones['official_date_start_edit'] = (isset($post['official_date_start_edit']) && !empty($post['official_date_start_edit'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['official_date_start_edit'])->format('Y-m-d H:i:s')) : '';
            $dones['official_date_end_edit'] = date("Y-m-d H:i:s");
        }

        $dones['official_creator_name'] = (isset($post['official_creator_name']) && !empty($post['official_creator_name'])) ? trim($post['official_creator_name']) : '';
        $dones['official_creator_position'] = (isset($post['official_creator_position']) && !empty($post['official_creator_position'])) ? trim($post['official_creator_position']) : '';
        $dones['official_destination'] = (isset($post['official_destination']) && !empty($post['official_destination'])) ? trim($post['official_destination']) : '';

        $dones['opening_description'] = (isset($post['opening_description']) && !empty($post['opening_description'])) ? trim($post['opening_description']) : '';

        /* description of RIG */
        $dones['id_rig'] = (isset($post['id_rig_current']) && !empty($post['id_rig_current'])) ? intval($post['id_rig_current']) : 0;
        $dones['time_msg'] = (isset($post['time_msg']) && !empty($post['time_msg'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['time_msg'])->format('Y-m-d H:i:s')) : '';
        $dones['time_loc'] = (isset($post['time_loc']) && !empty($post['time_loc'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['time_loc'])->format('Y-m-d H:i:s')) : '';
        $dones['time_likv'] = (isset($post['time_likv']) && !empty($post['time_likv'])) ? (\DateTime::createFromFormat('d.m.Y H:i:s', $post['time_likv'])->format('Y-m-d H:i:s')) : '';
        $dones['podr_take_msg'] = (isset($post['podr_take_msg']) && !empty($post['podr_take_msg'])) ? trim($post['podr_take_msg']) : '';
        $dones['disp_take_msg'] = (isset($post['disp_take_msg']) && !empty($post['disp_take_msg'])) ? trim($post['disp_take_msg']) : '';
        $dones['address'] = (isset($post['address']) && !empty($post['address'])) ? trim($post['address']) : '';
        $dones['latitude'] = (isset($post['latitude']) && !empty($post['latitude'])) ? trim($post['latitude']) : '';
        $dones['longitude'] = (isset($post['longitude']) && !empty($post['longitude'])) ? trim($post['longitude']) : '';
        $dones['vid_hs_1'] = (isset($post['vid_hs_1']) && !empty($post['vid_hs_1'])) ? intval($post['vid_hs_1']) : 0;
        $dones['vid_hs_2'] = (isset($post['vid_hs_2']) && !empty($post['vid_hs_2'])) ? intval($post['vid_hs_2']) : 0;
        $dones['reason_rig'] = (isset($post['reason_rig']) && !empty($post['reason_rig'])) ? trim($post['reason_rig']) : '';
        $dones['firereason_rig'] = (isset($post['firereason_rig']) && !empty($post['firereason_rig'])) ? trim($post['firereason_rig']) : '';
        $dones['inspector'] = (isset($post['inspector']) && !empty($post['inspector'])) ? trim($post['inspector']) : '';


        /* gdzs block */
        $dones['spec_cnt_gdzs'] = (isset($post['spec_cnt_gdzs']) && !empty($post['spec_cnt_gdzs'])) ? intval($post['spec_cnt_gdzs']) : 0;
        $dones['spec_time_work_gdzs'] = (isset($post['spec_time_work_gdzs']) && !empty($post['spec_time_work_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_work_gdzs'])->format('H:i')) : '';
        $dones['spec_time_work_bef_inj_gdzs'] = (isset($post['spec_time_work_bef_inj_gdzs']) && !empty($post['spec_time_work_bef_inj_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_work_bef_inj_gdzs'])->format('H:i')) : '';
        $dones['spec_time_shtab_gdzs'] = (isset($post['spec_time_shtab_gdzs']) && !empty($post['spec_time_shtab_gdzs'])) ? (\DateTime::createFromFormat('H:i', $post['spec_time_shtab_gdzs'])->format('H:i')) : '';


        /* people data block */
        $dones['people_fio'] = (isset($post['people_fio']) && !empty($post['people_fio'])) ? trim($post['people_fio']) : '';
        $dones['people_phone'] = (isset($post['people_phone']) && !empty($post['people_phone'])) ? trim($post['people_phone']) : '';
        $dones['people_address'] = (isset($post['people_address']) && !empty($post['people_address'])) ? trim($post['people_address']) : '';
        $dones['people_position'] = (isset($post['people_position']) && !empty($post['people_position'])) ? trim($post['people_position']) : '';
        $dones['people_status'] = (isset($post['people_status']) && !empty($post['people_status'])) ? intval($post['people_status']) : 0;
        $dones['people_is_uhet'] = (isset($post['people_is_uhet']) && !empty($post['people_is_uhet'])) ? intval($post['people_is_uhet']) : 0;


        /* detail inf block */
        $dones['detail_inf'] = (isset($post['detail_inf']) && !empty($post['detail_inf'])) ? trim($post['detail_inf']) : '';


        /* prevention block */
        $dones['prevention_time'] = (isset($post['prevention_time']) && !empty($post['prevention_time'])) ? (\DateTime::createFromFormat('d.m.Y', $post['prevention_time'])->format('Y-m-d')) : '';
        $dones['prevention_who'] = (isset($post['prevention_who']) && !empty($post['prevention_who'])) ? trim($post['prevention_who']) : '';
        $dones['prevention_result'] = (isset($post['prevention_result']) && !empty($post['prevention_result'])) ? trim($post['prevention_result']) : '';
        $dones['prevention_events'] = (isset($post['prevention_events']) && !empty($post['prevention_events'])) ? trim($post['prevention_events']) : '';


        /* is involved or no */
        $dones['is_not_involved_silymchs'] = (isset($post['is_not_involved_silymchs']) && !empty($post['is_not_involved_silymchs'])) ? intval($post['is_not_involved_silymchs']) : 0;
        $dones['is_not_involved_innerservice'] = (isset($post['is_not_involved_innerservice']) && !empty($post['is_not_involved_innerservice'])) ? intval($post['is_not_involved_innerservice']) : 0;
        $dones['is_not_involved_informing'] = (isset($post['is_not_involved_informing']) && !empty($post['is_not_involved_informing'])) ? intval($post['is_not_involved_informing']) : 0;
        $dones['is_not_involved_trunks'] = (isset($post['is_not_involved_trunks']) && !empty($post['is_not_involved_trunks'])) ? intval($post['is_not_involved_trunks']) : 0;
        $dones['is_wide_table_trunks'] = (isset($post['is_wide_table_trunks']) && !empty($post['is_wide_table_trunks'])) ? intval($post['is_wide_table_trunks']) : 0;


        /* insert/edit dones */
        if ($id_dones == 0) {//create a new
            $id_dones_new = $this->create_model->add_new_dones($dones);

            //logs
            $logs['id_user']= $this->data['active_user']['id_user'];
            $logs['id_dones']=$id_dones_new;
            $logs['id_action']=self::actions['create_sd'];
            $logs['date_action']=date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
        } else {//edit
            $this->create_model->edit_dones($id_dones, $dones);
            $id_dones_new = $id_dones;
             //logs
            $logs['id_user']= $this->data['active_user']['id_user'];
            $logs['id_dones']=$id_dones_new;
            $logs['id_action']=self::actions['edit_sd'];
            $logs['date_action']=date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);
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
                    $dones_silymchs['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? trim($row['v_ac']) : '';
                    $dones_silymchs['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                    $dones_silymchs['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i', $row['time_exit'])->format('H:i')) : '';
                    $dones_silymchs['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : '';
                    $dones_silymchs['time_follow'] = (isset($row['time_follow']) && !empty($row['time_follow'])) ? intval($row['time_follow']) : 0;
                    $dones_silymchs['distance'] = (isset($row['distance']) && !empty($row['distance'])) ? trim($row['distance']) : '';
                    $dones_silymchs['time_end'] = (isset($row['time_end']) && !empty($row['time_end'])) ? (\DateTime::createFromFormat('H:i', $row['time_end'])->format('H:i')) : '';
                    $dones_silymchs['time_return'] = (isset($row['time_return']) && !empty($row['time_return'])) ? (\DateTime::createFromFormat('H:i', $row['time_return'])->format('H:i')) : '';
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
                    $dones_innerservice['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i', $row['time_msg'])->format('H:i')) : '';
                    $dones_innerservice['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : '';
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
                    $dones_informing['time_msg'] = (isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i', $row['time_msg'])->format('H:i')) : '';
                    $dones_informing['time_exit'] = (isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i', $row['time_exit'])->format('H:i')) : '';
                    $dones_informing['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : '';
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
                    $dones_str['cnt_other_man'] = (isset($row['cnt_other_man']) && !empty($row['cnt_other_man'])) ? intval($row['cnt_other_man']) :0;
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

        if (isset($str_text) && !empty($str_text)) {
            foreach ($str_text as $k => $row) {
                $dones_str_text = array();
                if (isset($row['str_text_podr_name']) && !empty(trim($row['str_text_podr_name']))) {

                    $dones_str_text['id_dones'] = $id_dones_new;
                    $dones_str_text['id_pasp'] = (isset($row['id_pasp']) && !empty($row['id_pasp'])) ? intval($row['id_pasp']) : 0;
                    $dones_str_text['str_text_podr_name'] = (isset($row['str_text_podr_name']) && !empty($row['str_text_podr_name'])) ? trim($row['str_text_podr_name']) : '';
                    $dones_str_text['str_text_description'] = (isset($row['str_text_description']) && !empty($row['str_text_description'])) ?  trim($row['str_text_description']) : '';
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

        if (isset($trunks) && !empty($trunks)) {
            foreach ($trunks as $k => $row) {
                $dones_trunks = array();
                if (isset($row['mark']) && !empty(trim($row['mark']))) {

                    $dones_trunks['id_dones'] = $id_dones_new;
                    $dones_trunks['mark'] = trim($row['mark']);
                    $dones_trunks['pasp_name'] = (isset($row['pasp_name']) && !empty($row['pasp_name'])) ? trim($row['pasp_name']) : '';
                    $dones_trunks['locorg_name'] = (isset($row['locorg_name']) && !empty($row['locorg_name'])) ? trim($row['locorg_name']) : '';
                    $dones_trunks['v_ac'] = (isset($row['v_ac']) && !empty($row['v_ac'])) ? trim($row['v_ac']) : 0;
                    $dones_trunks['man_per_car'] = (isset($row['man_per_car']) && !empty($row['man_per_car'])) ? intval($row['man_per_car']) : 0;
                    $dones_trunks['time_arrival'] = (isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i', $row['time_arrival'])->format('H:i')) : '';
                    $dones_trunks['s_fire_arrival'] = (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? trim($row['s_fire_arrival']) : '';
                    $dones_trunks['time_pod'] = (isset($row['time_pod']) && !empty($row['time_pod'])) ? (\DateTime::createFromFormat('H:i', $row['time_pod'])->format('H:i')) : '';
                    $dones_trunks['means_trunks'] = (isset($row['means_trunks']) && !empty($row['means_trunks'])) ? trim($row['means_trunks']) : '';
                    $dones_trunks['water_po_out'] = (isset($row['water_po_out']) && !empty($row['water_po_out'])) ? trim($row['water_po_out']) : '';
                    $dones_trunks['time_loc'] = (isset($row['time_loc']) && !empty($row['time_loc'])) ? (\DateTime::createFromFormat('H:i', $row['time_loc'])->format('H:i')) : '';
                    $dones_trunks['s_fire_loc'] = (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? trim($row['s_fire_loc']) : '';
                    $dones_trunks['time_likv'] = (isset($row['time_likv']) && !empty($row['time_likv'])) ? (\DateTime::createFromFormat('H:i', $row['time_likv'])->format('H:i')) : '';
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
        $id_object = (isset($post['id_object']) && !empty($post['id_object'])) ? intval($post['id_object']) : 0; //edit id of table object

        if ($id_object == 0) {//add new object
            $this->create_model->add_new_dones_object($object);
        } else {//edit object
            $this->create_model->edit_dones_object($id_object, $object);
        }


         redirect('dones/catalog');
    }




        public function edit_form_standart($id_dones = 0)
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


    public function delete_sd($id_dones = 0)
    {

        if (isset($id_dones) && !empty($id_dones)) {

                        //logs
            $logs['id_user']= $this->data['active_user']['id_user'];
            $logs['id_dones']=$id_dones;
            $logs['id_action']=self::actions['delete_sd'];
            $logs['date_action']=date("Y-m-d H:i:s");
            $this->logs_model->add_logs($logs);

            $this->create_model->delete_dones($id_dones);
        }
    }
}
