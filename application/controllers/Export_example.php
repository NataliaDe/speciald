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

class Export_example extends My_Controller
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

            $innerservice = $this->create_model->get_dones_innerservice($id_dones);
            if (isset($innerservice) && !empty($innerservice)) {//works of each innerservice row
                foreach ($innerservice as $key => $row) {
                    $works = $this->create_model->get_dones_innerservice_work($row['id']);
                    $ids_work = (isset($works) && !empty($works)) ? array_column($works, 'id_work_innerservice') : array();
                    $innerservice[$key]['works'] = $ids_work;
                }
            }

            $name_file = 'standart.docx';

            $this->config->load('storage', TRUE);
            $templates_path = $this->config->item('templates_path', 'storage');

            $documentTemplate = $templates_path . '/' . $name_file;

            $phpWord = new PhpOffice\PhpWord\PhpWord();

            $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor($documentTemplate);

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


            $templateProcessor->setValue('date_msg', ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('d.m.Y')) : ''));
            $templateProcessor->setValue('time_msg', ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('H-i')) : ''));

            $templateProcessor->setValue('opening_description', trim($dones['opening_description']));

            $longitude = (isset($dones['longitude']) && !empty($dones['longitude'])) ? trim($dones['longitude']) : '';
            $latitude = (isset($dones['latitude']) && !empty($dones['latitude'])) ? trim($dones['latitude']) : '';
            if (!empty($longitude) && !empty($latitude)) {
                $templateProcessor->setValue('coords', ($latitude . ', ' . $longitude));
            } else {
                $templateProcessor->setValue('coords', 'нет координат');
            }


            /* ----------- silymchs ---------------- */
            if (isset($dones['is_not_involved_silymchs']) && $dones['is_not_involved_silymchs'] == 0 && !empty($silymchs)) {//insert table
                $templateProcessor->setValue('silymchs_text', '<w:br/>         К месту вызова были направлены:');
                //$templateProcessor->setValue('is_involved_silymchs', 'К месту вызова были направлены:');



                /* example Don't delete !!!!!!!!!!!!!!!!!   */
//$title = new PhpOffice\PhpWord\Element\TextRun();
//$title->addText('This title has been set ', array('bold' => true, 'italic' => true, 'color' => 'blue'));
//$title->addText('dynamically', array('bold' => true, 'italic' => true, 'color' => 'red', 'underline' => 'single'));
//$templateProcessor->setComplexBlock('silymchs_text', $title);
//
//
//
//$inline = new PhpOffice\PhpWord\Element\TextRun();
//$inline->addText('<w:br/>by a red italic text',array('indentation' => array('left' => -125, 'right' => -123)), array('indentation' => array('left' => -125, 'right' => -123)));
//$templateProcessor->setComplexValue('silymchs_text', $inline);
//
//$table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 12, 'borderColor' => 'green', 'width' => 6000)));
//$table = new \PhpOffice\PhpWord\Element\Table();
//$table->addRow();
//$table->addCell(150)->addText('Cell A1');
//$table->addCell(150)->addText('Cell A2');
//$table->addCell(150)->addText('Cell A3');
//$table->addRow();
//$table->addCell(150)->addText('Cell B1');
//$table->addCell(150)->addText('Cell B2');
//$table->addCell(150)->addText('Cell B3');
//$templateProcessor->setComplexBlock('table', $table);
//
//$field = new PhpOffice\PhpWord\Element\Field('DATE', array('dateformat' => 'dddd d MMMM yyyy H:mm:ss'), array('PreserveFormat'));
//$templateProcessor->setComplexValue('field', $field);
                //new page!!!!!!!!!
//                $templateProcessor->setValue('PAGE_BREAK', '</w:t></w:r>'.'<w:r><w:br w:type="page"/></w:r><w:r><w:t>');
                /* END example Don't delete !!!!!!!!!!!!!!!!!  */





                $arr = [];
                foreach ($silymchs as $key => $row) {
                    $arr[] = array(
                        'mark_sis'          => $row['mark'] . '' . ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                        'v_ac_sis'          => ((empty($row['v_ac'])) ? '-' : ((strpos($row['v_ac'], '.') === false) ? $row['v_ac'] : str_replace(".", ",", $row['v_ac']))),
                        'man_per_car_sis'   => $row['man_per_car'],
                        'time_exit_sis'     => ((isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_exit'])->format('H-s')) : ''),
                        'time_arrival_sis'  => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-s')) : ''),
                        'time_follow_sis'   => $row['time_follow'],
                        'time_distance_sis' => $row['distance'],
                        'time_end_work_sis' => ((isset($row['time_end']) && !empty($row['time_end'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_end'])->format('H-s')) : ''),
                        'time_return_sis'   => ((isset($row['time_return']) && !empty($row['time_return'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_return'])->format('H-s')) : ''),
                    );
                }
                //$templateProcessor->cloneRowAndSetValues('mark_sis', $arr);

                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3)));
                $templateProcessor->setComplexBlock('silymchs_block', $table);


            } else {// ???????????
                $templateProcessor->deleteBlock('block_name');
            }


            /* ------------------- trunks -------------------- */
            if (isset($dones['is_not_involved_trunks']) && $dones['is_not_involved_trunks'] == 0 && !empty($trunks)) {//insert table
                $arr = [];
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

                /* wide table data */
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
                //$templateProcessor->cloneRowAndSetValues('mark_trunk', $arr);

                $table = new \PhpOffice\PhpWord\Element\Table((array('borderSize' => 3)));
//                 $cellTextCentered = array('align' => 'center', 'size' => 10,
//                     'indentation' => array('left' => -125, 'right' => -123));
                $cellTextCentered = array('align' => 'center', 'size'  => 8,
                );
                $cellTextCenteredFont = array('align' => 'center', 'size' => 8);

                $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'align' => 'center');
                $cellRowContinue = array('vMerge' => 'continue');
                $cellColSpan = array('gridSpan' => 2, 'valign' => 'center', 'align' => 'center');
                $cellColSpan_1 = array('gridSpan' => 1, 'valign' => 'center', 'align' => 'center');

                $table->addRow();

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), $cellRowSpan)->addText("Наименование подразделения", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowSpan)->addText('Объем<w:br/>цистерны<w:br/>(тонн)', $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowSpan)->addText('Кол-во<w:br/>л/с<w:br/>(чел.)', $cellTextCenteredFont, $cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan)->addText("Прибытие", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_1)->addText("Подача<w:br/>стволов", $cellTextCenteredFont, $cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowSpan)->addText('Средства тушения (кол-во, тип)', $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowSpan)->addText('Израсходовано воды/ ПО (тонн)', $cellTextCenteredFont, $cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan)->addText('Локализация', $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_1)->addText('Ликвидация', $cellTextCenteredFont, $cellTextCentered);

                /* second row of head */
                $table->addRow();
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(5.66), $cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowContinue);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("S пож.<w:br/>(кв.м.)", $cellTextCenteredFont, $cellTextCentered);

                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowContinue);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellRowContinue);
//
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("Время", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43))->addText("S пож.<w:br/>(кв.м.)", $cellTextCenteredFont, $cellTextCentered);
                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.43))->addText("Время", $cellTextCenteredFont, $cellTextCentered);


                $style_cell_center = array('valign' => 'center', 'align' => 'center');
                $style_cell_font = array('size' => 8);
                $style_cell_left = array('align' => 'left');
                $cellColSpan_4 = array('gridSpan' => 4, 'valign' => 'center', 'align' => 'center');
                $cellColSpan_3 = array('gridSpan' => 3, 'valign' => 'center', 'align' => 'center');

                $i = 0;
                $j = 0;
                $cnt_rows = count($arr);

                if ($cnt_rows > 0) {
                    foreach ($arr as $key => $row) {

                        if (isset($row['is_wide']) && $row['is_wide'] == 1) {//wide table
                            $j++;

                            if ($j == 1) {

                                $table->addRow();
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_4)->addText("Специальная и вспомогательная техника", $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));

                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_3)->addText("действия личного состава", $style_cell_font, $style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));


                                $table->addRow();
                                $table->addCell(null, $style_cell_left)->addText($row['mark_trunk'], $style_cell_font, $style_cell_left);
                                $table->addCell(null, $style_cell_center)->addText($row['v_ac_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(null, $style_cell_center)->addText($row['man_per_car_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(null, $style_cell_center)->addText($row['time_arrival_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_3)->addText($row['action_ls_trunk'], $style_cell_font, $style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            } else {

                                $table->addRow();
                                $table->addCell(null, $style_cell_left)->addText($row['mark_trunk'], $style_cell_font, $style_cell_left);
                                $table->addCell(null, $style_cell_center)->addText($row['v_ac_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(null, $style_cell_center)->addText($row['man_per_car_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(null, $style_cell_center)->addText($row['time_arrival_trunk'], $cellTextCenteredFont, $cellTextCentered);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(1.43), $cellColSpan_3)->addText($row['action_ls_trunk'], $style_cell_font, $style_cell_left);
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }
                        } else {

                            $i++;
                            $table->addRow();
                            $table->addCell(null, $style_cell_left)->addText($row['mark_trunk'], $style_cell_font, $style_cell_left);
                            $table->addCell(null, $style_cell_center)->addText($row['v_ac_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            $table->addCell(null, $style_cell_center)->addText($row['man_per_car_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            $table->addCell(null, $style_cell_center)->addText($row['time_arrival_trunk'], $cellTextCenteredFont, $cellTextCentered);


                            if ($i == 1)
                                $table->addCell(1000, $cellRowSpan)->addText($row['s_fire_arrival_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            $table->addCell(null, $style_cell_center)->addText($row['time_pod_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            $table->addCell(null, $style_cell_center)->addText($row['means_trunks_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            $table->addCell(null, $style_cell_center)->addText($row['water_po_out_trunk'], $cellTextCenteredFont, $cellTextCentered);

                            if ($i == 1)
                                $table->addCell(1000, $cellRowSpan)->addText($row['time_loc_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            if ($i == 1)
                                $table->addCell(1000, $cellRowSpan)->addText($row['s_fire_loc_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }

                            if ($i == 1)
                                $table->addCell(1000, $cellRowSpan)->addText($row['time_likv_trunk'], $cellTextCenteredFont, $cellTextCentered);
                            else {
                                $table->addCell(1000, array('vMerge' => 'continue'));
                            }
                        }
                    }
                }
            }


            $templateProcessor->setComplexBlock('table', $table);


            $file_download = 'СД ' . $dones['specd_number'] . '.docx';
            header('Content-Disposition: attachment; filename="' . $file_download . '"');
            $templateProcessor->saveAs('php://output');
        }
    }
}
