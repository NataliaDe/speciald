<?php

class Media extends MY_Controller
{

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


            //$this->load->language('russian');
            $langShort = 'upload';
            $langName = 'russian';
            $this->lang->load($langShort, $langName);

            //TWIG
            //$this->load->library('twig');
            //$this->twig->addGlobal('sitename', 'My Awesome Site');
        } else {
            redirect('auth');
        }
    }

    public function loadApi($type)
    {
//print_r($_FILES);
        if (!empty($_FILES) && isset($type) && !empty($type)) {

            $this->config->load('storage', TRUE);
            $upload_path = $this->config->item('upload_path', 'storage');

            $config = [];

            switch ($type) {


                case 'sd_photo':

                    $config = array(
                        'upload_path'   => $upload_path . '/sd_photo',
                        'overwrite'     => FALSE,
                        'allowed_types' => "jpg|png|jpeg",
                        'encrypt_name'  => TRUE,
                        'max_size'      => 1024000
//                        'max_width'     => 1024,
//                        'max_height'    => 768
                    );
                    break;

                case 'sd_video':
                    $config = array(
                        'upload_path'   => $upload_path . '/sd_video',
                        'overwrite'     => FALSE,
                        'allowed_types' => "mp4|mpeg4",
                        //'max_size' => 1000000,
                        'max_size'      => 10240000000,
                        'encrypt_name'  => true
                    );
                    break;

                                case 'sd_audio':
                    $config = array(
                        'upload_path'   => $upload_path . '/sd_audio',
                        'overwrite'     => FALSE,
                        'allowed_types' => "mp3",
                        //'max_size' => 1000000,
                        'max_size'      => 5120000000,
                        'encrypt_name'  => true
                    );
                    break;


                                case 'sd_doc':

                    $config = array(
                        'upload_path'   => $upload_path . '/sd_doc',
                        'overwrite'     => FALSE,
                        'allowed_types' => "doc|docx",
                        'encrypt_name'  => TRUE,
                        'max_size'      => 1024000
//                        'max_width'     => 1024,
//                        'max_height'    => 768
                    );
                    break;
            }

            // loading config
            if ($config) {

                if (!file_exists($config['upload_path']) && !is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], '0777');
                }

                $this->load->library('upload', $config);
            }

            // loading file
            if ($this->upload->do_upload('file')) {
                $img = $this->upload->data();

                echo json_encode(array('success' => base_url($config['upload_path'] . '/' . $img['file_name']), 'file_name' => $img['file_name']));
            } else {
                //echo json_encode(array('uploaded' => 0, 'fileName' => 'null', 'url' => 'null'));
                echo json_encode(
                    array('error' => $this->upload->display_errors())
                );
                die();
            }
        }
    }
}
