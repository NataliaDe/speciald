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

class Daily_test extends My_Controller
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
    const header_style_cell_size = array('size' => 15);
    //const header_style_cell_font =array('space' => array('line' => 28, 'rule' => 'exact'));
    //'spaceAfter' => 0,
    const header_style_cell_font = array('spaceAfter'      => 0, 'spacing'         => 280,
        'spacingLineRule' => 'exact');
    const start_descr_font = array('spaceAfter' => 0, 'spacing' => 0,'align'=>'both');

    const sign_style_cell_size = array('size' => 15);
    const sign_style_cell_font = array('spaceAfter'      => 0, 'spacing'         => 280,
        'spacingLineRule' => 'exact','align'=>'right');

//'spaceAfter' => PhpOffice\PhpWord\Shared\Converter::pointToTwip(6)

    public function __construct()
    {
        parent::__construct();

        $this->re_login();

        $this->load->model('create_model');
        $this->load->model('main_model');

        $this->load->model('user_model');
        $this->load->model('dones_model');
        $this->load->model('logs_model');

        $this->data['active_item_menu'] = 'catalog';

        $this->load->helper('floor_by_number_helper');


        //TWIG
        //$this->load->library('twig');
        //$this->twig->addGlobal('sitename', 'My Awesome Site');
    }

    public function index()
    {

    }

    public function export()
    {

//exit();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');

        /* DON'T CHECK WORDS */

        $phpWord->getSettings()->setThemeFontLang(new PhpOffice\PhpWord\Style\Language(PhpOffice\PhpWord\Style\Language::RU_RU));
        $phpWord->getSettings()->setHideGrammaticalErrors(TRUE);



        //$phpWord->getSettings()->setAutoHyphenation(true);




        $phpWord->addParagraphStyle(
            'leftTab', array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('left', 9090)))
        );

        /* HEADER */

        $section = $phpWord->addSection(
            array('marginLeft'   => PhpOffice\PhpWord\Shared\Converter::cmToTwip(1), 'marginRight'  => PhpOffice\PhpWord\Shared\Converter::cmToTwip(1),
                'marginTop'    => PhpOffice\PhpWord\Shared\Converter::cmToTwip(1), 'marginBottom' => PhpOffice\PhpWord\Shared\Converter::cmToTwip(1))
        );


        $section->addText('Министерство по чрезвычайным ситуациям Республики Беларусь',  array('size' => 15,'bold'=>TRUE,'underline' => 'single'), array('spaceAfter'      => 0,'lineHeight'=>'1.0','align'=>'right'));
//$section->addTextBreak(1, self::header_style_cell_size, self::header_style_cell_font);

$this->config->load('storage', TRUE);
            $templates_path = $this->config->item('templates_path', 'storage');

            $map_daily = $templates_path . '/' . 'map_daily.jpg';
            $star = $templates_path . '/' . 'star_mchs.jpg';
            $map_minsk = $templates_path . '/' . 'map_minsk_5.png';
            $teh_hs = $templates_path . '/' . 'teh_hs.png';
            $nature_hs = $templates_path . '/' . 'nature_hs.png';
            $fire_hs = $templates_path . '/' . 'fire_hs.png';
            $man_dead = $templates_path . '/' . 'man_dead.png';
            $man_save = $templates_path . '/' . 'man_save.png';
            $man_save_map = $templates_path . '/' . 'man_save_map.png';

             $section->addImage($star,array( 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.90)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.90)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.2)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.3)),
    'wrappingStyle' => 'infront'));

             $section->addTextBreak(2, array('size' => 15), array('spaceAfter'      => 0,'lineHeight'=>'1.0','align'=>'right'));



                     $table = $section->addTable(array('width'      => PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.28),
            'marginLeft' => PhpOffice\PhpWord\Shared\Converter::cmToTwip(0),'align'=>'center'
        ));

                      $table->addRow(PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.86));
                      $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.28), array('align'=>'center','valign'=>'center'))->addText('Информация о чрезвычайных ситуациях №123', array('size' => 15,'bold'=>TRUE,'valign'=>'center'),  array('spaceAfter'      => 0,'lineHeight'=>'1.0','align'=>'center','valign'=>'center'));
                      $table->addRow();
                      $table->addCell(PhpOffice\PhpWord\Shared\Converter::cmToTwip(16.28),array('align'=>'center','valign'=>'center'))->addText('с 06-00 часов 01 мая 2020 г. до 06-00 часов 02 мая 2020 г.', array('size' => 15),  array('spaceAfter'      => 0, 'lineHeight'=>'1.0','align'=>'center'));



              $section->addImage($map_minsk,array( 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(4.80)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.50)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(10.10)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.90)),
    'wrappingStyle' => 'infront'));







//$section->addTextBreak(1,  array('size' => 10),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
 $section->addText('                                                                                                                                                                                         г. Минск',  array('size' => 10,'bold'=>TRUE),  array('spaceAfter'      => 0,'lineHeight'=>'1.0'));




 //$section->addTextBreak(1,  array('size' => 10),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
 $section->addText('1. Ситуационная карта',  array('size' => 15,'bold'=>TRUE), array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'center'));

                                                                            $section->addImage($map_daily,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(13.25)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(11.50)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.20)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.50)),
    'wrappingStyle' => 'behind'));


 $section->addText('        Условные обозначения:',  array('size' => 11,'bold'=>TRUE),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));

               $section->addImage($teh_hs,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.70)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.73)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.405)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.17)),
    'wrappingStyle' => 'infront'));
               $section->addText('      крупная ЧС техногенного характера',  array('size' => 10,'spaceAfter'      => 0),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addTextBreak(1,  array('size' => 8),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addText('      крупная ЧС природного характера',  array('size' => 10), array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addTextBreak(1,  array('size' => 8),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addText('      крупные пожары',  array('size' => 10), array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addTextBreak(1,  array('size' => 8),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addText('      погибло в ЧС',  array('size' => 10), array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addTextBreak(1,  array('size' => 8),  array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));
               $section->addText('      спасено при ликвидации ЧС<w:br/>      прочих инцидентах',  array('size' => 10), array('spaceAfter'      => 0,'lineHeight'=>'1.0', 'align'=>'left'));

                              $section->addImage($nature_hs,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.70)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.59)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.405)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2.44)),
    'wrappingStyle' => 'infront'          ));
    //

                                             $section->addImage($fire_hs,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.56)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.62)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.305)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2.41)),
    'wrappingStyle' => 'infront'));


                                                            $section->addImage($man_dead,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.56)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.62)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.305)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2.38)),
    'wrappingStyle' => 'infront'));


                                                                           $section->addImage($man_save,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.56)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.62)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.305)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2.28)),
    'wrappingStyle' => 'infront'));


                                                                           /* on map */
                                                            $section->addImage($man_save_map,array('size' => 5, 'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.56)),
    'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.62)),
                 'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(4.50)),
    'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2.65)),
    'wrappingStyle' => 'infront'));








        $rows = 1;
        $cols = 2;




        /* NAME */

      //  $section->addText('jjj', self::header_style_cell_size, self::header_style_cell_font);

     //   $section->addTextBreak(1, self::header_style_cell_size, self::header_style_cell_font);

        /* DATE AND NUMBER SD */

       // $section->addText($date . ' № ' . $number, self::header_style_cell_size, self::header_style_cell_font);

        //$section->addTextBreak(1, self::header_style_cell_size, self::header_style_cell_font);

        /* SHORT DESCRIPTION */


       // $section->addTextBreak(2, self::header_style_cell_size, self::header_style_cell_font);






            //$section->addText('          ' . trim($dones['object_word']), self::header_style_cell_size, self::start_descr_font);
           // $section->addTextBreak(1, self::header_style_cell_size, self::header_style_cell_font);


       $file_download='test.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file_download . '"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Type: application/msword");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("php://output");
    }

    public function ct_1_to_word($id_dones)
    {
        $dones = $this->create_model->get_dones_by_id($id_dones);
        $type_sd = $dones['type'];


            $this->config->load('storage', TRUE);
            $templates_path = $this->config->item('templates_path', 'storage');

            $name_file = 'ct_1_new.docx';
            $documentTemplate = $templates_path . '/' . $name_file;

            $phpWord = new PhpOffice\PhpWord\PhpWord();

            $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor($documentTemplate);

            $parser = new HTMLtoOpenXML\Parser();


            $templateProcessor->setValue('official_creator_name', $dones['official_creator_name']);
            $templateProcessor->setValue('specd_date', ((isset($dones['specd_date']) && !empty($dones['specd_date'])) ? (\DateTime::createFromFormat('Y-m-d', $dones['specd_date'])->format('d.m.Y')) : ''));
            $templateProcessor->setValue('ct_1_goal_rig', $dones['ct_1_goal_rig']);
            $templateProcessor->setValue('time_msg', ((isset($dones['time_msg']) && !empty($dones['time_msg'])) ? (\DateTime::createFromFormat('Y-m-d H:i:s', $dones['time_msg'])->format('d.m.Y H-i')) : ''));
            $templateProcessor->setValue('address', trim($dones['address']));
            $templateProcessor->setValue('ct_1_object', trim($dones['ct_1_object']));
            $templateProcessor->setValue('ct_1_applicant', trim($dones['ct_1_applicant']));
            $templateProcessor->setValue('opening_description', trim($dones['opening_description']));
            $templateProcessor->setValue('ct_1_silymchs', trim($dones['ct_1_silymchs']));
            $templateProcessor->setValue('ct_1_senior', trim($dones['ct_1_senior']));
            $templateProcessor->setValue('ct_1_innerservice', trim($dones['ct_1_innerservice']));
            $templateProcessor->setValue('is_opg', (($dones['is_opg'] == 1)) ? 'да' : 'нет');
            $templateProcessor->setValue('opg_text', trim($dones['opg_text']));
            $templateProcessor->setValue('ct_1_arrival_situation', trim($dones['ct_1_arrival_situation']));
            $templateProcessor->setValue('ct_1_come_in', trim($dones['ct_1_come_in']));
            $templateProcessor->setValue('ct_1_taken_measures', trim($dones['ct_1_taken_measures']));
            $templateProcessor->setValue('ct_1_affected', trim($dones['ct_1_affected']));
            $templateProcessor->setValue('ct_1_effects', trim($dones['ct_1_effects']));
            $templateProcessor->setValue('ct_1_note', trim($dones['ct_1_note']));

            $templateProcessor->setValue('ct_1_position_sign', trim($dones['ct_1_position_sign']));
            $templateProcessor->setValue('ct_1_podr_sign', trim($dones['ct_1_podr_sign']));
            $templateProcessor->setValue('ct_1_rank_sign', trim($dones['ct_1_rank_sign']));
            $templateProcessor->setValue('ct_1_fio_sign', trim($dones['ct_1_fio_sign']));






        $d = (isset($dones['specd_date']) && !empty($dones['specd_date'])) ? (\DateTime::createFromFormat('Y-m-d', $dones['specd_date'])->format('d.m.Y')) : '';
        $addr_parts = explode(',', $dones['address']);
        $addr_loc = (isset($addr_parts[0]) && !empty($addr_parts[0])) ? $addr_parts[0] : '';
        $vid= mb_strtolower($dones['vid_specd_name']);

        if ($d != '' && $addr_loc != '' && $vid != '' ){
            $file_download = $d . ' ' . $addr_loc.' ('.$vid.')';
            $mb_str_len = mb_strlen($file_download, 'utf-8');
            if ($mb_str_len >= 200) {// обрезать текст
                $file_download = mb_substr($file_download, 0, 200, 'utf-8');
            } else {
                $file_download = $file_download;
            }
            $file_download = $file_download . '.docx';
        }
        else
            $file_download = 'СД ' . $dones['specd_number'] . '.docx';



        header('Content-Disposition: attachment; filename="' . $file_download . '"');
            $templateProcessor->saveAs('php://output');

        //echo $id_dones;
    }

    public function sd_to_word_old($id_dones = 0)
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


//                            foreach ($str_text as $row) {
//                    if (!empty($row['str_text_podr_name']) && !empty($row['str_text_description'])) {
//                       $a= explode(PHP_EOL, $row['str_text_description']);
//
//
//                       foreach ($a as $value) {
//                           echo trim($value);
//                       }
//
//                         print_r($a);                         echo '<br>';
//                    }
//                }
//                foreach ($str_text as $row) {
//                    if (!empty($row['str_text_podr_name']) && !empty($row['str_text_description'])) {
//                        if ($content == '')
//
//                            $content = '<p><i><u><b>' . $row['str_text_podr_name'] . '</b></u></i><br>' . (htmlspecialchars ($row['str_text_description'])).  '</p>';
//                           // $content = '<p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
//                        else
//                           // $content = $content . '<br><p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
//                            $content = $content . '<br><p><i><u><b>' . $row['str_text_podr_name'] . '</b></u></i><br>' . (htmlspecialchars ($row['str_text_description'])) . '</p>';
//                    }
//                }
//                echo $content;
            // exit();

            $blocks = array('silymchs_block'     => 'silymchs_block', 'trunks_block'       => 'trunks_block', 'innerservice_block' => 'innerservice_block'
                , 'informing_block'    => 'informing_block', 'str_block_text'     => 'str_block_text', 'str_block'          => 'str_block', 'str_text_block'     => 'str_text_block');



            /* only start text and end text */
            if ($dones['is_not_involved_silymchs'] == 1 && $dones['is_not_involved_innerservice'] == 1 &&
                $dones['is_not_involved_informing'] == 1 && $dones['is_not_involved_trunks'] == 1 && $dones['is_not_involved_str'] == 1) {
                $name_file = 'empty_table_standart.docx';

                $blocks = array();
            } else {
                $name_file = 'standart.docx';
            }


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
                            'time_exit_sis'     => ((isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_exit'])->format('H-i')) : ''),
                            'time_arrival_sis'  => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-i')) : ''),
                            'time_follow_sis'   => (isset($row['time_follow']) && !empty($row['time_follow'])) ? $row['time_follow'] : '-',
                            'distance_sis'      => (isset($row['distance']) && !empty($row['distance'])) ? $row['distance'] : '-',
                            'time_end_work_sis' => ((isset($row['time_end']) && !empty($row['time_end'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_end'])->format('H-i')) : ''),
                            'time_return_sis'   => ((isset($row['time_return']) && !empty($row['time_return'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_return'])->format('H-i')) : ''),
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
                $wide_table = [];
                if (!empty($trunks)) {
                    foreach ($trunks as $key => $row) {

                        if ($dones['is_wide_table_trunks'] == 1 && isset($row['actions_ls']) && !empty($row['actions_ls'])) {

                            $wide_table[] = array(
                                'is_wide'              => 1,
                                'mark_trunk'           => $row['mark'] . '' . ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                                'v_ac_trunk'           => ((empty($row['v_ac'])) ? '-' : ((strpos($row['v_ac'], '.') === false) ? $row['v_ac'] : str_replace(".", ",", $row['v_ac']))),
                                'man_per_car_trunk'    => $row['man_per_car'],
                                's_fire_arrival_trunk' => (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? $row['s_fire_arrival'] : '-',
                                'time_arrival_trunk'   => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-i')) : ''),
                                'time_loc_trunk'       => ((isset($row['time_loc']) && !empty($row['time_loc'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_loc'])->format('H-i')) : ''),
                                's_fire_loc_trunk'     => (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? $row['s_fire_loc'] : '-',
                                'time_likv_trunk'      => ((isset($row['time_likv']) && !empty($row['time_likv'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_likv'])->format('H-i')) : ''),
                                'action_ls_trunk'      => (isset($row['actions_ls']) && !empty($row['actions_ls']) && $row['actions_ls'] != '-') ? $row['actions_ls'] : ''
                            );
                        } else {
                            $arr[] = array(
                                'mark_trunk'           => $row['mark'] . '' . ((!empty($row['pasp_name'])) ? (' ' . $row['pasp_name']) : '') . '' . ((!empty($row['locorg_name'])) ? (' ' . $row['locorg_name']) : ''),
                                'v_ac_trunk'           => ((empty($row['v_ac'])) ? '-' : ((strpos($row['v_ac'], '.') === false) ? $row['v_ac'] : str_replace(".", ",", $row['v_ac']))),
                                'man_per_car_trunk'    => $row['man_per_car'],
                                's_fire_arrival_trunk' => (isset($row['s_fire_arrival']) && !empty($row['s_fire_arrival'])) ? $row['s_fire_arrival'] : '-',
                                'time_arrival_trunk'   => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-i')) : ''),
                                'time_pod_trunk'       => ((isset($row['time_pod']) && !empty($row['time_pod'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_pod'])->format('H-i')) : '-'),
                                'means_trunks_trunk'   => (!empty($row['means_trunks'])) ? $row['means_trunks'] : '-',
                                'water_po_out_trunk'   => ((isset($row['water_po_out']) && !empty($row['water_po_out'])) ? ((strpos($row['water_po_out'], '.') === false) ? $row['water_po_out'] : str_replace(".", ",", $row['water_po_out'])) : '-'),
                                'time_loc_trunk'       => ((isset($row['time_loc']) && !empty($row['time_loc'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_loc'])->format('H-i')) : ''),
                                's_fire_loc_trunk'     => (isset($row['s_fire_loc']) && !empty($row['s_fire_loc'])) ? $row['s_fire_loc'] : '-',
                                'time_likv_trunk'      => ((isset($row['time_likv']) && !empty($row['time_likv'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_likv'])->format('H-i')) : ''),
                            );
                        }
                    }



                    /* !!!!!!!!!!!!!!!! wide table data */
                    $arr = array_merge($arr, $wide_table);
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
                            'time_msg'          => ((isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_msg'])->format('H-i')) : ''),
                            'time_arrival'      => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-i')) : ''),
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
                            'time_msg'     => ((isset($row['time_msg']) && !empty($row['time_msg'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_msg'])->format('H-i')) : ''),
                            'time_exit'    => ((isset($row['time_exit']) && !empty($row['time_exit'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_exit'])->format('H-i')) : ''),
                            'time_arrival' => ((isset($row['time_arrival']) && !empty($row['time_arrival'])) ? (\DateTime::createFromFormat('H:i:s', $row['time_arrival'])->format('H-i')) : '')
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
            if (isset($str) && !empty($str) && $dones['is_not_involved_str'] == 0) {//insert table
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
            $content = '';
            if (isset($str_text) && !empty($str_text) && $dones['is_not_involved_str'] == 0) {//insert table
                foreach ($str_text as $row) {
                    if (!empty($row['str_text_podr_name']) && !empty($row['str_text_description'])) {

                        $a = explode(PHP_EOL, $row['str_text_description']);
                        $man = '';
                        foreach ($a as $value) {
                            if (empty($man))
                                $man = trim($value);
                            else
                                $man = $man . '<br/>' . trim($value);
                        }


                        $pasp = '<br/><br/>' . '<u><b><i>' . trim($row['str_text_podr_name']) . '</i></b></u>' . '<br/>';
                        $res = $pasp . $man;
                        if ($content == '')
//$content = "<span class=\"label\">$man</span>";
                            $content = "<meta charset='UTF-8' /><span class=\"label\">$res</span>";
                        //$content = '<i><u><b>'. trim($row['str_text_podr_name']).'</b></u></i><br>' .  '';
                        // $content = '<p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
                        else
                            $content = $content . "<meta charset='UTF-8' /><span class=\"label\">$res</span>";
                        // $content = $content . '<br><p><u><b>' . $row['str_text_podr_name'] . '</b></u><br><i>' . $row['str_text_description'] . '</i></p>';
                        //$content = $content . '<br><i><u><b>' . $row['str_text_podr_name'] . '</b></u></i><br>'  . '';
                    }
                }

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
