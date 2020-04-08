<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once('application/libraries/PhpWord/PhpWord.php');
require_once('application/libraries/PhpWord/TemplateProcessor.php');
require_once('application/libraries/PhpWord/Writer/Word2007.php');
require_once('application/libraries/htmltoopenxml/src/Parser.php');
require_once('application/libraries/PhpWord/Element/Field.php');
require_once('application/libraries/PhpWord/Element/Table.php');
require_once('application/libraries/PhpWord/Element/TextRun.php');
require_once('application/libraries/PhpWord/SimpleType/TblWidth.php');

//require_once('application/libraries/PHPExcel/Writer/Excel5.php');

class Export extends My_Controller
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
    const style_cell_center = array('valign' => 'center', 'align' => 'center');
    const style_cell_font = array('size' => 8);
    const style_cell_left = array('align' => 'left');
    const cell_center = array('valign' => 'center', 'align' => 'center');
    const cellColSpan_4 = array('gridSpan' => 4, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_3 = array('gridSpan' => 3, 'valign' => 'center', 'align' => 'center');
    const cellColSpan = array('gridSpan' => 2, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_1 = array('gridSpan' => 1, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_10 = array('gridSpan' => 10, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_12 = array('gridSpan' => 12, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_2 = array('gridSpan' => 2, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_11 = array('gridSpan' => 11, 'valign' => 'center', 'align' => 'center');
    const cellColSpan_9 = array('gridSpan' => 9, 'valign' => 'center', 'align' => 'center');
    const cellTextCentered = array('align' => 'center', 'size' => 8,);
    const cellTextCenteredFont = array('align' => 'center', 'size' => 8);
    const cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'align' => 'center');
    const cellRowContinue = array('vMerge' => 'continue');
    const cellVCenteredCellBTLR = array('valign' => 'center', 'textDirection' => PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR, 'exactHeight' => 10000);
    const cellHCentered = array('align' => 'center', 'size' => 11);

//'spaceAfter' => PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)

    public function __construct()
    {
        parent::__construct();

        $this->re_login();

        $this->load->model('create_model');

        $this->load->model('user_model');
        $this->load->model('dones_model');
        $this->load->model('logs_model');

        $this->data['active_item_menu'] = 'catalog';


        //TWIG
        //$this->load->library('twig');
        //$this->twig->addGlobal('sitename', 'My Awesome Site');
    }

    public function index()
    {

    }

    public function sd_to_word($id_dones = 0)
    {

        if (isset($id_dones) && !empty($id_dones)) {

            $dones = $this->create_model->get_dones_by_id($id_dones);
            $silymchs = $this->create_model->get_dones_silymchs($id_dones);

            $trunks = $this->create_model->get_dones_trunks($id_dones);

            $innerservice = $this->create_model->get_dones_innerservice_name($id_dones);
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


            $blocks = array('silymchs_block'     => 'silymchs_block', 'trunks_block'       => 'trunks_block', 'innerservice_block' => 'innerservice_block'
                , 'informing_block'    => 'informing_block', 'str_block_text'     => 'str_block_text', 'str_block'          => 'str_block', 'str_text_block'     => 'str_text_block');



            /* only start text and end text */
            if(empty($silymchs) && empty($trunks) && empty($innerservice) && empty($informing) && empty($str) && empty($str_text)){

            }
            $name_file = 'standart.docx';

            $this->config->load('storage', TRUE);
            $templates_path = $this->config->item('templates_path', 'storage');

            $documentTemplate = $templates_path . '/' . $name_file;

            $phpWord = new PhpOffice\PhpWord\PhpWord();

            $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor($documentTemplate);

            $parser = new HTMLtoOpenXML\Parser();


            $templateProcessor->setValue('official_creator_name', $dones['official_creator_name']);
            $templateProcessor->setValue('specd_date', ((isset($dones['specd_date']) && !empty($dones['specd_date'])) ? (\DateTime::createFromFormat('Y-m-d', $dones['specd_date'])->format('d.m.Y')) : ''));
            $templateProcessor->setValue('specd_number', $dones['specd_number']);

            $templateProcessor->setValue('short_description', ((isset($dones['short_description']) && !empty(trim($dones['short_description']))) ? trim($dones['short_description']) : 'О чем'));

            /* !!!!!!!!!!!!!!! On future, if we use ckeditor.  Don't delete !!!!!!!!!!!!!!!!!  */
//            $content = '<p>Test<b>test</b><br>test</p>';
//            $parser = new HTMLtoOpenXML\Parser();
//            \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
//            $ooXml = $parser->fromHTML($content);
//            $templateProcessor->setValue('short_description', $ooXml);
//            \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            /*  END !!!!!!!!!!!!!! */

            $open_descr = '';
            $date_msg = ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('d.m.Y')) : '');
            $time_msg = ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('H-i')) : '');

            $opening_description = trim($dones['opening_description']);

            if ($date_msg != '') {
                $open_descr = $date_msg;
            }
            if ($time_msg != '') {
                if (!empty($open_descr))
                    $open_descr = $open_descr . ' в ' . $time_msg;
                else
                    $open_descr = 'в ' . $time_msg;
            }
            if ($opening_description != '') {
                if (!empty($open_descr))
                    $open_descr = $open_descr . ' ' . $opening_description;
                else
                    $open_descr = $opening_description;
            }


//            $templateProcessor->setValue('date_msg', ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('d.m.Y')) : ''));
//            $templateProcessor->setValue('time_msg', ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('H-i')) : ''));
//            $templateProcessor->setValue('opening_description', trim($dones['opening_description']));

            $longitude = (isset($dones['longitude']) && !empty($dones['longitude'])) ? trim($dones['longitude']) : '';
            $latitude = (isset($dones['latitude']) && !empty($dones['latitude'])) ? trim($dones['latitude']) : '';
            if (!empty($longitude) && !empty($latitude)) {
                $coords = $latitude . ', ' . $longitude;
                //$templateProcessor->setValue('coords', ($latitude . ', ' . $longitude));
            } else {
                $coords = 'нет координат';
                //$templateProcessor->setValue('coords', 'нет координат');
            }

            if (!empty($open_descr))
                $open_descr = $open_descr . ' ' . '(' . $coords . ')';
            else
                $open_descr = '(' . $coords . ')';
            $templateProcessor->setValue('opening_description', $open_descr);


            /* ----------- silymchs ---------------- */
            if (isset($dones['is_not_involved_silymchs']) && $dones['is_not_involved_silymchs'] == 0) {//insert table
                $templateProcessor->setValue('silymchs_text', '<w:br/>         К месту вызова были направлены:');

                $arr = [];
                if (!empty($silymchs)) {
                    foreach ($silymchs as $key => $row) {
                        $arr[] = array(
                            'mark_sis'          => $row['mark'] . '' . ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                            'v_ac_sis'          => ((empty($row['v_ac'])) ? '-' : ((strpos($row['v_ac'], '.') === false) ? $row['v_ac'] : str_replace(".", ",", $row['v_ac']))),
                            'man_per_car_sis'   => $row['man_per_car'],
                            'time_exit_sis'     => ((isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_exit'])->format('H-s')) : ''),
                            'time_arrival_sis'  => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-s')) : ''),
                            'time_follow_sis'   => $row['time_follow'],
                            'distance_sis'      => $row['distance'],
                            'time_end_work_sis' => ((isset($row['time_end']) && !empty($row['time_end'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_end'])->format('H-s')) : ''),
                            'time_return_sis'   => ((isset($row['time_return']) && !empty($row['time_return'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_return'])->format('H-s')) : ''),
                        );
                    }
                }

                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3)));


                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cell_center)->addText("Наименование подразделения", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Объем<w:br/>цистерны<w:br/>(тонн)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Кол-во<w:br/>л/с<w:br/>(чел.)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Время<w:br/>выезда', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Время<w:br/>прибытия', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Время<w:br/>следования<w:br/>(мин.)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Расстояние<w:br/>(км)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Время<w:br/>окончания<w:br/>работ', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cell_center)->addText('Время<w:br/>возвращения в<w:br/>подразделение', self::cellTextCenteredFont, self::cellTextCentered);


                if (count($arr) > 0) {
                    foreach ($arr as $key => $row) {
                        $table->addRow();
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cell_center)->addText($row['mark_sis'], self::style_cell_font, self::style_cell_left);
                        $table->addCell(null, self::style_cell_center)->addText($row['v_ac_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['man_per_car_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['time_exit_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['time_arrival_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['time_follow_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['distance_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['time_end_work_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(null, self::style_cell_center)->addText($row['time_return_sis'], self::cellTextCenteredFont, self::cellTextCentered);
                    }
                }

                $templateProcessor->setComplexBlock('silymchs_block', $table);

                unset($blocks['silymchs_block']);
            } else {// ???????????
                $templateProcessor->setValue('silymchs_text', '');
            }


            /* ------------------- trunks -------------------- */
            if (isset($dones['is_not_involved_trunks']) && $dones['is_not_involved_trunks'] == 0) {//insert table
                $arr = [];
                if (!empty($trunks)) {
                    foreach ($trunks as $key => $row) {
                        $arr[] = array(
                            'mark_trunk'           => $row['mark'] . '' . ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                            'v_ac_trunk'           => ((empty($row['v_ac'])) ? '-' : ((strpos($row['v_ac'], '.') === false) ? $row['v_ac'] : str_replace(".", ",", $row['v_ac']))),
                            'man_per_car_trunk'    => $row['man_per_car'],
                            's_fire_arrival_trunk' => (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? $row['s_fire_arrival'] : '-',
                            'time_arrival_trunk'   => ((isset($row['time_arrival_trunk']) && !empty($row['time_arrival_trunk'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival_trunk'])->format('H-s')) : ''),
                            'time_pod_trunk'       => ((isset($row['time_pod']) && !empty($row['time_pod'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_pod'])->format('H-s')) : '-'),
                            'means_trunks_trunk'   => (!empty($row['means_trunks'])) ? $row['means_trunks'] : '-',
                            'water_po_out_trunk'   => ((isset($row['water_po_out']) && !empty($row['water_po_out'])) ? $row['water_po_out'] : '-'),
                            'time_loc_trunk'       => ((isset($row['time_loc']) && !empty($row['time_loc'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_loc'])->format('H-s')) : ''),
                            's_fire_loc_trunk'     => (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? $row['s_fire_loc'] : '-',
                            'time_likv_trunk'      => ((isset($row['time_likv']) && !empty($row['time_likv'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_likv'])->format('H-s')) : ''),
                        );
                    }



                    /* !!!!!!!!!!!!!!!! wide table data */
                    $arr[] = array(
                        'is_wide'            => 1,
                        'mark_trunk'         => 'АЛ',
                        'v_ac_trunk'         => '-',
                        'man_per_car_trunk'  => '4',
                        'time_arrival_trunk' => '08-00',
                        'action_ls_trunk'    => 'Установка лестницы на балкон'
                    );
                    $arr[] = array(
                        'is_wide'            => 1,
                        'mark_trunk'         => 'АШ ЦОУ',
                        'v_ac_trunk'         => '-',
                        'man_per_car_trunk'  => '4',
                        'time_arrival_trunk' => '08-00',
                        'action_ls_trunk'    => 'Возврат после прибытия к месту пожара'
                    );
                }



                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3)));

                $table->addRow();

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cellRowSpan)->addText("Наименование подразделения", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowSpan)->addText('Объем<w:br/>цистерны<w:br/>(тонн)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowSpan)->addText('Кол-во<w:br/>л/с<w:br/>(чел.)', self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan)->addText("Прибытие", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_1)->addText("Подача<w:br/>стволов", self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowSpan)->addText('Средства тушения (кол-во, тип)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowSpan)->addText('Израсходовано воды/ ПО (тонн)', self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan)->addText('Локализация', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_1)->addText('Ликвидация', self::cellTextCenteredFont, self::cellTextCentered);

                /* second row of head */
                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowContinue);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("S пож.<w:br/>(кв.м.)", self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellRowContinue);
//
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("S пож.<w:br/>(кв.м.)", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.43))->addText("Время", self::cellTextCenteredFont, self::cellTextCentered);



                $i = 0;
                $j = 0;
                $cnt_rows = count($arr);

                if ($cnt_rows > 0) {
                    foreach ($arr as $key => $row) {

                        if (isset($row['is_wide']) && $row['is_wide'] == 1) {//wide table
                            $j++;

                            if ($j == 1) {

                                $table->addRow();
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_4)->addText("Специальная и вспомогательная техника", self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));

                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_3)->addText("действия личного состава", self::style_cell_font, self::style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));


                                $table->addRow();
                                $table->addCell(null, self::style_cell_left)->addText($row['mark_trunk'], self::style_cell_font, self::style_cell_left);
                                $table->addCell(null, self::style_cell_center)->addText($row['v_ac_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(null, self::style_cell_center)->addText($row['man_per_car_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(null, self::style_cell_center)->addText($row['time_arrival_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_3)->addText($row['action_ls_trunk'], self::style_cell_font, self::style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            } else {

                                $table->addRow();
                                $table->addCell(null, self::style_cell_left)->addText($row['mark_trunk'], self::style_cell_font, self::style_cell_left);
                                $table->addCell(null, self::style_cell_center)->addText($row['v_ac_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(null, self::style_cell_center)->addText($row['man_per_car_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(null, self::style_cell_center)->addText($row['time_arrival_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), self::cellColSpan_3)->addText($row['action_ls_trunk'], self::style_cell_font, self::style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }
                        } else {

                            $i++;
                            $table->addRow();
                            $table->addCell(null, self::style_cell_left)->addText($row['mark_trunk'], self::style_cell_font, self::style_cell_left);
                            $table->addCell(null, self::style_cell_center)->addText($row['v_ac_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            $table->addCell(null, self::style_cell_center)->addText($row['man_per_car_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            $table->addCell(null, self::style_cell_center)->addText($row['time_arrival_trunk'], self::cellTextCenteredFont, self::cellTextCentered);


                            if ($i == 1)
                                $table->addCell(1000, self::cellRowSpan)->addText($row['s_fire_arrival_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            $table->addCell(null, self::style_cell_center)->addText($row['time_pod_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            $table->addCell(null, self::style_cell_center)->addText($row['means_trunks_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            $table->addCell(null, self::style_cell_center)->addText($row['water_po_out_trunk'], self::cellTextCenteredFont, self::cellTextCentered);

                            if ($i == 1)
                                $table->addCell(1000, self::cellRowSpan)->addText($row['time_loc_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            if ($i == 1)
                                $table->addCell(1000, self::cellRowSpan)->addText($row['s_fire_loc_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            if ($i == 1)
                                $table->addCell(1000, self::cellRowSpan)->addText($row['time_likv_trunk'], self::cellTextCenteredFont, self::cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }
                        }
                    }
                }
                //$templateProcessor->setComplexBlock('trunks_block', $table);
                $templateProcessor->setComplexBlock(array_shift($blocks), $table);

                //unset($blocks['trunks_block']);
            } else {

            }



            /* ----------- innerservice ---------------- */
            $arr = [];
            if (isset($dones['is_not_involved_innerservice']) && $dones['is_not_involved_innerservice'] == 0) {//insert table
                if (!empty($innerservice)) {
                    foreach ($innerservice as $row) {
                        $arr[] = array(
                            'innerservice_name' => (!empty($row['innerservice_name'])) ? $row['innerservice_name'] : '-',
                            'time_msg'          => ((isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_msg'])->format('H-s')) : ''),
                            'time_arrival'      => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-s')) : ''),
                            'distance'          => (!empty($row['distance'])) ? $row['distance'] : '-',
                            'note'              => ((isset($row['note']) && !empty($row['note'])) ? $row['note'] : '-')
                        );
                    }
                }


                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3, 'width' => 8000)));
//                self::cellTextCentered = array('align' => 'center', 'size' => 8,);
//                self::cellTextCenteredFont = array('align' => 'center', 'size' => 8);


                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(25), self::cell_center)->addText("Службы взаимодействия", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::cell_center)->addText('Время<w:br/>сообщения', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::cell_center)->addText('Время<w:br/>прибытия', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::cell_center)->addText('Расстояние<w:br/>(км)', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::cell_center)->addText('Примечание', self::cellTextCenteredFont, self::cellTextCentered);


                if (count($arr) > 0) {
                    foreach ($arr as $key => $row) {
                        $table->addRow();
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(25), self::cell_center)->addText($row['innerservice_name'], self::style_cell_font, self::style_cell_left);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::style_cell_center)->addText($row['time_msg'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::style_cell_center)->addText($row['time_arrival'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::style_cell_center)->addText($row['distance'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(6), self::style_cell_center)->addText($row['note'], self::cellTextCenteredFont, self::cellTextCentered);
                    }
                }

                //$templateProcessor->setComplexBlock('innerservice_block', $table);
                $templateProcessor->setComplexBlock(array_shift($blocks), $table);
            }




            /* ----------- informing ---------------- */
            $arr = [];
            if (isset($dones['is_not_involved_informing']) && $dones['is_not_involved_informing'] == 0) {//insert table
                if (!empty($informing)) {
                    foreach ($informing as $row) {
                        $arr[] = array(
                            'fio'          => (!empty($row['fio'])) ? $row['fio'] : '-',
                            'time_msg'     => ((isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_msg'])->format('H-s')) : ''),
                            'time_exit'    => ((isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_exit'])->format('H-s')) : ''),
                            'time_arrival' => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-s')) : '')
                        );
                    }
                }


                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3, 'width' => 8000)));


                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(21), self::cell_center)->addText("ФИО, должность, звание руководителя органа, подразделения, ответственного", self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::cell_center)->addText('Время<w:br/>сообщения о ЧС', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::cell_center)->addText('Время<w:br/>выезда к месту ЧС', self::cellTextCenteredFont, self::cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::cell_center)->addText('Время<w:br/>прибытия к месту ЧС', self::cellTextCenteredFont, self::cellTextCentered);



                if (count($arr) > 0) {
                    foreach ($arr as $key => $row) {
                        $table->addRow();
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(21), self::cell_center)->addText($row['fio'], self::style_cell_font, self::style_cell_left);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::style_cell_center)->addText($row['time_msg'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::style_cell_center)->addText($row['time_exit'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::style_cell_center)->addText($row['time_arrival'], self::cellTextCenteredFont, self::cellTextCentered);
                    }
                }

                //$templateProcessor->setComplexBlock('informing_block', $table);
                $templateProcessor->setComplexBlock(array_shift($blocks), $table);
            }





            /* ----------- str ---------------- */
            $arr = [];
            if (isset($str) && !empty($str)) {//insert table
                //$templateProcessor->setValue('str_block_text', '         Справочная информация:');
                $end_block = array_shift($blocks);
//                if($end_block == 'str_block_text'){
//
//                    $content='Справочная информация:<br>';
//                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
//                $ooXml = $parser->fromHTML($content);
//                //$templateProcessor->setValue('str_text_block', $ooXml);
//                $templateProcessor->setValue($end_block, $ooXml);
//                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
//                }
//                else{
//                    $templateProcessor->setValue($end_block, '         Справочная информация:');
//                }
//$templateProcessor->setValue($end_block, 'Справочная информация:');
                $title = new PhpOffice\PhpWord\Element\TextRun();
                $title->addText('         Справочная информация:', array('italic' => true, 'size' => 15));
                $templateProcessor->setComplexBlock($end_block, $title);

                foreach ($str as $row) {
                    $arr[] = array(
                        'pasp'            => ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                        'shtat'           => ((isset($row['shtat']) && !empty($row['shtat'])) ? $row['shtat'] : '-'),
                        'vacant'          => ((isset($row['vacant']) && !empty($row['vacant'])) ? $row['vacant'] : '-'),
                        'on_list_ch'      => ((isset($row['on_list_ch']) && !empty($row['on_list_ch'])) ? $row['on_list_ch'] : '-'),
                        'vacant_ch'       => ((isset($row['vacant_ch']) && !empty($row['vacant_ch'])) ? $row['vacant_ch'] : '-'),
                        'face_ch'         => ((isset($row['face_ch']) && !empty($row['face_ch'])) ? $row['face_ch'] : '-'),
                        'br_ch'           => ((isset($row['br_ch']) && !empty($row['br_ch'])) ? $row['br_ch'] : '-'),
                        'cnt_trip_man'    => ((isset($row['cnt_trip_man']) && !empty($row['cnt_trip_man'])) ? $row['cnt_trip_man'] : '-'),
                        'cnt_holiday_man' => ((isset($row['cnt_holiday_man']) && !empty($row['cnt_holiday_man'])) ? $row['cnt_holiday_man'] : '-'),
                        'cnt_ill_man'     => ((isset($row['cnt_ill_man']) && !empty($row['cnt_ill_man'])) ? $row['cnt_ill_man'] : '-'),
                        'cnt_naryd'       => ((isset($row['cnt_naryd']) && !empty($row['cnt_naryd'])) ? $row['cnt_naryd'] : '-'),
                        'cnt_other_man'   => ((isset($row['cnt_other_man']) && !empty($row['cnt_other_man'])) ? $row['cnt_other_man'] : '-'),
                        'gas'             => ((isset($row['gas']) && !empty($row['gas'])) ? $row['gas'] : '-')
                    );
                }



                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3, 'width' => 8000)));


                $table->addRow();

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cellRowSpan)->addText("Наименование подразделения", self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(48), self::cellColSpan_12)->addText('Строевая записка по личному составу', self::cellTextCenteredFont, self::cellTextCentered);


                /* second row of head */
                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cellRowContinue);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(8), self::cellColSpan_2)->addText("Подразделения", self::cellTextCenteredFont, self::cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(40), self::cellColSpan_10)->addText("Дежурной смены", self::cellTextCenteredFont, self::cellTextCentered);

                /* third row of head */
                $table->addRow(PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cellRowContinue);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('По штату', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Вакансия', self::cellTextCenteredFont, self::cellHCentered);


                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('По штату в<w:br/>дежурной смене', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Вакансия в<w:br/>дежурной смене', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Налицо', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('В боевом расчете', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Командировка', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Отпуск', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Больные', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Наряд', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('Другие причины', self::cellTextCenteredFont, self::cellHCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::cellVCenteredCellBTLR)->addText('ГДЗС, чел', self::cellTextCenteredFont, self::cellHCentered);
//


                if (count($arr) > 0) {
                    foreach ($arr as $key => $row) {
                        $table->addRow();
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), self::cell_center)->addText($row['pasp'], self::style_cell_font, self::style_cell_left);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['shtat'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['vacant'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['on_list_ch'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['vacant_ch'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['face_ch'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['br_ch'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['cnt_trip_man'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['cnt_holiday_man'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['cnt_ill_man'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['cnt_naryd'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['cnt_other_man'], self::cellTextCenteredFont, self::cellTextCentered);
                        $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(4), self::style_cell_center)->addText($row['gas'], self::cellTextCenteredFont, self::cellTextCentered);
                    }
                }

                //$templateProcessor->setComplexBlock('str_block', $table);
                $templateProcessor->setComplexBlock(array_shift($blocks), $table);
            }


            /* ----------- str text ------------ */
            if (isset($str_text) && !empty($str_text)) {//insert table
                $content = '';
                foreach ($str_text as $row) {
                    if (!empty($row['str_text_podr_name']) && !empty($row['str_text_description'])) {
                        if ($content == '')
                            $content = '<p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
                        else
                            $content = $content . '<br><p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
                    }
                }

                //$content = '<p><u><b>ПАСЧ-1  Ленинского РОЧС:</b></u><br><i>1 чел. (водитель Головач П.Ч.) – отпуск с 02.01.2019 по 16.01.2019 (приказ нач. РОЧС от 28.12.2018 №74л/с), дежурит старший водитель Губчик П.В. (приказ нач. РОЧС от 28.12.2018 №74л/с).</i></p>';



                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
                $ooXml = $parser->fromHTML($content);
                $templateProcessor->setValue(array_shift($blocks), $ooXml);
                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            }

            if (!empty($blocks)) {
                foreach ($blocks as $value) {
                    $templateProcessor->setValue($value, '');
                }
            }



            $templateProcessor->setValue('detail_inf_block', $dones['detail_inf']);


            $who_sign = '<p>' . $dones['author_position_name'] . '<br>' . strtolower($dones['author_rank_name']) . '</p>';
            $templateProcessor->setValue('position_sign_block', $dones['author_position_name']);

            $rank = mb_strtolower($dones['author_rank_name']);
            $rank = explode(' ', $rank);
            $rank_sign = '';
            foreach ($rank as $k => $value) {
                if ($value == 'вн.сл.')
                    $key = $k;
            }
            if (isset($key)) {
                for ($i = 0; $i < $key; $i++) {
                    if ($rank_sign == '') {
                        $rank_sign = $rank[$i];
                    } else {
                        $rank_sign = $rank_sign . ' ' . $rank[$i];
                    }
                }
            }
            if (!empty($rank_sign))
                $rank_sign = $rank_sign . ' ' . 'внутренней службы';

            $templateProcessor->setValue('rank_sign_block', $rank_sign);

            $templateProcessor->setValue('fio_sign_block', $dones['author_fio']);




            $file_download = 'СД ' . $dones['specd_number'] . '.docx';
            header('Content-Disposition: attachment; filename="' . $file_download . '"');
            $templateProcessor->saveAs('php://output');
        }
    }
}
