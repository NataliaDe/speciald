<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once('application/libraries/PHPExcel.php');
require_once('application/libraries/PHPExcel/Writer/Excel5.php');
require_once('application/libraries/PHPExcel/Reader/CSV.php');
require_once('application/libraries/PHPExcel/Reader/Excel5.php');
require_once('application/libraries/PHPExcel/Reader/Excel2007.php');

class Reports extends My_Controller
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
        $this->load->model('main_model');
        $this->load->model('ss_model');
        $this->load->model('reports_model');

        $this->load->model('classif_model');

        $this->load->helper('declination_helper');


        $this->data['regions'] = $this->main_model->get_regions();
        $this->data['locals'] = $this->main_model->get_locals();
        $this->data['organs'] = $this->main_model->get_organs_in_local();
        $this->data['positions'] = $this->main_model->get_positions();
        $this->data['ranks'] = $this->main_model->get_ranks();


        $this->data['active_item_menu'] = 'reports';

        $this->load->library('form_validation');
    }

    public function index()
    {

    }

    public function time_sd_form()
    {

        $this->data['bread_crumb'] = array(array('/reports/time_sd_form' => 'Отчеты'),
            array('Время подготовки СД')
        );
        //$this->data['vid_sd'] = $this->classif_model->get_vid_sd(0);
        $this->data['title'] = 'Отчеты';


        $this->data['regions_cp_list'] = $this->ss_model->set_regions_cp_list_reports();

        $this->twig->display('reports/time_sd/form', $this->data);
    }

    public function time_sd()
    {

        $post = $this->input->post();

        $filter = [];

        $dates_arr = explode(' - ', $post['daterange']);

        $filter['from'] = \DateTime::createFromFormat('d.m.Y', trim($dates_arr[0]))->format('Y-m-d');
        $filter['to'] = \DateTime::createFromFormat('d.m.Y', trim($dates_arr[1]))->format('Y-m-d');

        $filter['id_region_creator'] = (isset($post['id_region']) && !empty($post['id_region'])) ? $post['id_region'] : '';

        $name_creator = 'все';
        if ($filter['id_region_creator'] == Main_model::ORGAN_ID_AVIA || $filter['id_region_creator'] == Main_model::ORGAN_ID_ROSN ||
            $filter['id_region_creator'] == Main_model::ORGAN_ID_UGZ) {



            $filter['id_organ_creator'] = $filter['id_region_creator'];
            $name_creator = $this->ss_model->get_organ_name_by_id($filter['id_organ_creator']);
            unset($filter['id_region_creator']);
        } elseif ($filter['id_region_creator'] == Main_model::REGION_ID_RCU) {


            $filter['id_organ_creator'] = Main_model::ORGAN_ID_RCU;
            $name_creator = $this->ss_model->get_organ_name_by_id($filter['id_organ_creator']);
            unset($filter['id_region_creator']);
        } elseif ($filter['id_region_creator'] != '') {
            $name_creator = $this->ss_model->get_region_name_by_id($filter['id_region_creator']);
            if ($filter['id_region_creator'] != 3)
                $name_creator = $name_creator . ' область';
        }


        $result = $this->reports_model->get_time_sd($filter);


        if (!empty($result)) {
            foreach ($result as $key => $row) {
                if (!empty($row['official_date_start']) && !empty($row['official_date_end'])) {
                    $start = new \DateTime($row['official_date_start']);
                    $end = new \DateTime($row['official_date_end']);
                    $diff = $start->diff($end);

                    $hours = $diff->h;
                    $min = $diff->h;
                    $sec = $diff->s;

                    if ($hours != 0)
                        $result[$key]['create_hours'] = $hours . ' ' . declination_word_by_number($hours, array('час', 'часа', 'часов'));
                    // echo $hours.' '.declination_word_by_number($hours, array('час','часа','часов'));
                    if ($min != 0)
                        $result[$key]['create_min'] = $min . ' ' . declination_word_by_number($min, array('минута', 'минуты', 'минут'));
                    //echo $min.' '.declination_word_by_number($min, array('минута','минуты','минут'));
                    if ($sec != 0)
                        $result[$key]['create_sec'] = $sec . ' ' . declination_word_by_number($sec, array('секунда', 'секунды', 'секунд'));
                    //echo $sec.' '.declination_word_by_number($sec, array('секунда','секунды','секунд'));
                    //echo $row['official_date_start']; echo ' - '.$row['official_date_end']; echo $diff['h'];echo $diff['i'];echo $diff['s'];
                    //  echo '<br>';
                    if($hours == 0 && $min == 0 && $sec == 0 && $row['is_copy'] == 1){
                        $result[$key]['create_text']='шаблон';
                    }
                }
                elseif($row['is_copy'] == 1){
                        $result[$key]['create_text']='шаблон';
                    }
            }
        }



        $this->config->load('storage', TRUE);
        $templates_path = $this->config->item('templates_path', 'storage');
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load($templates_path . '/reports/' . 'time_sd.xlsx');


        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet(0);

        $r = 2;
        $sheet->setCellValue('A' . $r, ('период: с ' . trim($dates_arr[0]) . ' по ' . trim($dates_arr[1])));
        $r++;
        $sheet->setCellValue('A' . $r, ('создатель: ' . $name_creator));
        $r = 9;

        $i = 0;
        if (!empty($result)) {
            foreach ($result as $key => $row) {
                $i++;
                $sheet->setCellValue('A' . $r, $i);
                $sheet->setCellValue('B' . $r, $row['id_dones']);
                $sheet->setCellValue('C' . $r, \DateTime::createFromFormat('Y-m-d', trim($row['specd_date']))->format('d.m.Y'));
                $sheet->setCellValue('D' . $r, $row['specd_number']);
                $sheet->setCellValue('E' . $r, $row['short_description']);
                $sheet->setCellValue('F' . $r, $row['opening_description']);
                $sheet->setCellValue('G' . $r, ($row['full_creator_name'] . (!empty($row['creator_fio']) ? ', ' . $row['creator_fio'] : '')));
                $sheet->setCellValue('H' . $r, ( (isset($row['create_text']) && !empty($row['create_text']) ) ? $row['create_text'] : ((isset($row['create_hours']) ? $row['create_hours'] : '') .
                    (isset($row['create_min']) ? ' ' . $row['create_min'] : '') . (isset($row['create_sec']) ? ' ' . $row['create_sec'] : ''))));
                $r++;
            }
        }



        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Отчет по времени подготовки СД.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        // }
    }
}
