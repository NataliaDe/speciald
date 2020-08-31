<?php

class Media extends MY_Controller
{
    const SIZE_SUM_PHOTO='10000000';// bytes.=10 Mb
    const SIZE_SUM_VIDEO='30000000';// bytes.=30 Mb
    const SIZE_SUM_AUDIO='20000000';// bytes.=20 Mb

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
                        //'max_size'      => 1024000
                        'max_size'      => 2048 //in KB. = 2MB
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
                       // 'max_size'      => 40960000, //40 MB
                         'max_size'      => 15000, //15 MB
                        //   'max_size'      => 35840,
                        'encrypt_name'  => true
                    );
                    break;

                case 'sd_audio':
                    $config = array(
                        'upload_path'   => $upload_path . '/sd_audio',
                        'overwrite'     => FALSE,
                        'allowed_types' => "mp3|wav",
                        //'max_size' => 1000000,
                        'max_size'      => 10000, //kilobytes: 1MB = 1024 kilobytes, 10MB = 10000 kilobytes
                        'encrypt_name'  => true
                    );
                    break;


                case 'sd_doc':

                    $config = array(
                        'upload_path'   => $upload_path . '/sd_doc',
                        'overwrite'     => FALSE,
                        'allowed_types' => "doc|docx",
                        'encrypt_name'  => TRUE,
                       // 'max_size'      => 1024000//1 MB
                        'max_size'      => 2048// in KB. =2MB
//                        'max_width'     => 1024,
//                        'max_height'    => 768
                    );
                    break;


                case 'sd_pdf':

                    $config = array(
                        'upload_path'   => $upload_path . '/sd_pdf',
                        'overwrite'     => FALSE,
                        'allowed_types' => "pdf",
                        'encrypt_name'  => TRUE,
                        //'max_size'      => 1024000
                        'max_size'      => 2048// in KB. =2MB
//                        'max_width'     => 1024,
//                        'max_height'    => 768
                    );
                    break;


                case 'sd_photo_multi':

                    $config = array(
                        'upload_path'   => $upload_path . '/sd_photo',
                        'overwrite'     => FALSE,
                        'allowed_types' => "jpg|png|jpeg",
                        'encrypt_name'  => TRUE
                        //'max_size'      => 1024000
//                        'max_width'     => 1024,
//                        'max_height'    => 768
                    );

                    break;

                                case 'sd_video_multi':
                    $config = array(
                        'upload_path'   => $upload_path . '/sd_video',
                        'overwrite'     => FALSE,
                        'allowed_types' => "mp4|mpeg4",
                        'encrypt_name'  => true
                    );
                    break;

                                case 'sd_audio_multi':
                    $config = array(
                        'upload_path'   => $upload_path . '/sd_audio',
                        'overwrite'     => FALSE,
                        'allowed_types' => "mp3|wav",
                        'encrypt_name'  => true
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

            if ($type == 'sd_photo_multi') {

                $count = count($_FILES['file']['name']);
                $post = $_FILES['file'];
                $arr_photo = [];
                $arr_error = [];


                //check sum size photo
                $size=0;
                for ($i = 0; $i < $count; $i++) {
                    $size += $post['size'][$i];//in bytes
                }

                 if($size>self::SIZE_SUM_PHOTO){
                     $msg= 'Суммарный объем файлов превышает допустимый ('.(self::SIZE_SUM_PHOTO/1000000).' Мб)';
                     $arr_error = array('error' => $msg);
                        echo json_encode($arr_error);
                        die();
                 }


                for ($i = 0; $i < $count; $i++) {

                    $_FILES['file']['name'] = $post['name'][$i];
                    $_FILES['file']['type'] = $post['type'][$i];
                    $_FILES['file']['tmp_name'] = $post['tmp_name'][$i];
                    $_FILES['file']['error'] = $post['error'][$i];
                    $_FILES['file']['size'] = $post['size'][$i];
                    //echo $_FILES['file']['name'];
                    if ($this->upload->do_upload('file')) {
                        $img = $this->upload->data();
                        $arr_photo[] = array('success' => base_url($config['upload_path'] . '/' . $img['file_name']), 'file_name' => $img['file_name']);
                    } else {
                        $arr_error = array('error' => $this->upload->display_errors(),'name_file_error'=>$_FILES['file']['name']);
                        echo json_encode($arr_error);
                        die();
                    }
                }

                if (empty($arr_error)){
                    $res['is_ok']=1;
                    $res['images']=$arr_photo;
                    //$arr_photo['is_ok']=1;
                    echo json_encode($res);
                }

                die();
            }
            elseif ($type == 'sd_video_multi') {

                $count = count($_FILES['file']['name']);
                $post = $_FILES['file'];
                $arr_photo = [];
                $arr_error = [];


                //check sum size photo
                $size=0;
                for ($i = 0; $i < $count; $i++) {
                    $size += $post['size'][$i];//in bytes
                }

                 if($size>self::SIZE_SUM_VIDEO){
                     $msg= 'Суммарный объем файлов превышает допустимый ('.(self::SIZE_SUM_VIDEO/1000000).' Мб)';
                     $arr_error = array('error' => $msg);
                        echo json_encode($arr_error);
                        die();
                 }


                for ($i = 0; $i < $count; $i++) {

                    $_FILES['file']['name'] = $post['name'][$i];
                    $_FILES['file']['type'] = $post['type'][$i];
                    $_FILES['file']['tmp_name'] = $post['tmp_name'][$i];
                    $_FILES['file']['error'] = $post['error'][$i];
                    $_FILES['file']['size'] = $post['size'][$i];
                    //echo $_FILES['file']['name'];
                    if ($this->upload->do_upload('file')) {
                        $img = $this->upload->data();
                        $arr_photo[] = array('success' => base_url($config['upload_path'] . '/' . $img['file_name']), 'file_name' => $img['file_name']);
                    } else {
                        $arr_error = array('error' => $this->upload->display_errors(),'name_file_error'=>$_FILES['file']['name']);
                        echo json_encode($arr_error);
                        die();
                    }
                }

                if (empty($arr_error)){
                    $res['is_ok']=1;
                    $res['images']=$arr_photo;
                    //$arr_photo['is_ok']=1;
                    echo json_encode($res);
                }

                die();
            }
elseif ($type == 'sd_audio_multi') {

                $count = count($_FILES['file']['name']);
                $post = $_FILES['file'];
                $arr_photo = [];
                $arr_error = [];


                //check sum size photo
                $size=0;
                for ($i = 0; $i < $count; $i++) {
                    $size += $post['size'][$i];//in bytes
                }

                 if($size>self::SIZE_SUM_AUDIO){
                     $msg= 'Суммарный объем файлов превышает допустимый ('.(self::SIZE_SUM_AUDIO/1000000).' Мб)';
                     $arr_error = array('error' => $msg);
                        echo json_encode($arr_error);
                        die();
                 }


                for ($i = 0; $i < $count; $i++) {

                    $_FILES['file']['name'] = $post['name'][$i];
                    $_FILES['file']['type'] = $post['type'][$i];
                    $_FILES['file']['tmp_name'] = $post['tmp_name'][$i];
                    $_FILES['file']['error'] = $post['error'][$i];
                    $_FILES['file']['size'] = $post['size'][$i];
                    //echo $_FILES['file']['name'];
                    if ($this->upload->do_upload('file')) {
                        $img = $this->upload->data();
                        $arr_photo[] = array('success' => base_url($config['upload_path'] . '/' . $img['file_name']), 'file_name' => $img['file_name']);
                    } else {
                        $arr_error = array('error' => $this->upload->display_errors(),'name_file_error'=>$_FILES['file']['name']);
                        echo json_encode($arr_error);
                        die();
                    }
                }

                if (empty($arr_error)){
                    $res['is_ok']=1;
                    $res['images']=$arr_photo;
                    //$arr_photo['is_ok']=1;
                    echo json_encode($res);
                }

                die();
            }


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
