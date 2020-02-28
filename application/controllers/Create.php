<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create extends My_Controller
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

            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();

            $this->data['reasonrig'] = $this->main_model->get_reasonrig();


            $this->data['active_item_menu'] = 'create';



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
        $this->twig->display('create/index', $this->data);
    }

    //form standart
    public function form_standart($is_default = 0)
    {

//        $ids_pasp=array(525,529);
//         print_r($this->getStrByIdsPasp(array_unique($ids_pasp)));
//         exit();

        $this->data['title'] = 'Новое спец.донесение';
        $this->data['active_item_menu_type_create'] = 'standart';
        $this->data['bread_crumb']=array('/create'=>'Создать специальное донесение','Стандартное');


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

        $this->setRegionsCpList();



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
                $diviz_organ_of_pasp[$key]['listls'] = $this->str_model->get_listls_in_ch_cou($row['id_pasp'], $current_ch);
                //$diviz_organ_of_pasp[$key]['shtat_ch'] = $this->str_model->get_shtat_in_ch_cou($row['id_pasp'], $current_ch);

                /*  face = all on fields in cou str */
                if($row['id_organ'] == 8){
                $maincou_all_row = $this->str_model->get_all_in_ch_cou($row['id_pasp'], $current_ch);
                }
                elseif($row['id_organ'] == 5){
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

                $diviz_organ_of_pasp[$key]['face'] = $face_cou;


                /* br */
                $is_car = $this->str_model->is_car_in_ch_cou($row['id_pasp'], $current_ch);
                if ($is_car > 0)
                    $count_fio_on_car = 0;
                else {
                    $count_fio_on_car = $this->str_model->br_in_pasp_on_date($row['id_pasp'], $current_dateduty);
                }

                $diviz_organ_of_pasp[$key]['br'] = $count_fio_on_car;
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

    public function setRegionsCpList()
    {
        $regions = $this->ss_model->get_regions();
        $cp_region[] = array('id' => 8, 'name' => 'РОСН');
        $cp_region[] = array('id' => 9, 'name' => 'УГЗ');
        $cp_region[] = array('id' => 12, 'name' => 'Авиация');
        $this->data['regions_cp_list'] = array_merge($regions, $cp_region);
    }


    public function get_grochs_by_region()
    {

        $ids_region= $this->input->post('ids_region');
        $grochs_by_region = $this->str_model->get_locorg_cp_list($ids_region);

            if (!empty($grochs_by_region)) {
                echo json_encode($grochs_by_region);
            }
    }

        public function get_pasp_by_locorg()
    {

        $ids_locorg= $this->input->post('ids_locorg');
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
                echo json_encode(array('cars'=>$cars, 'is_error' => 0));
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
                $row= explode('~', $value);

                $res=array();
                $res['id_car']=(isset($row[0])) ? trim($row[0]) : '';
                $res['mark']=(isset($row[1])) ? trim($row[1]) : '';
                $res['pasp_name']=(isset($row[2])) ? trim($row[2]) : '';
                $res['locorg_name']=(isset($row[3])) ? trim($row[3]) : '';
                $res['v_ac']=(isset($row[4])) ? $row[4] : '';
                $res['man_per_car']=(isset($row[5])) ? $row[5] : '';

                if(!empty($res))
                    $result[]=$res;
            }

            if(isset($result) && !empty($result)){

                echo json_encode(array('cars'=>$result,'is_error' => 0));
            }
            else{
               echo json_encode(array('is_error' => 1, 'msg' => 'Данные по технике не найдены'));
            }


        } else {
            echo json_encode(array('is_error' => 1, 'msg' => 'Данные по технике не найдены'));
        }
    }
}
