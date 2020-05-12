<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classif extends My_Controller
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

        if ($this->session->userdata('is_admin') == 1 && $this->session->userdata('level') == Main_model::LEVEL_ID_RCU) {//superadmin rcu
            $this->load->model('classif_model');


            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();
            $this->data['ranks'] = $this->main_model->get_ranks();

            $this->data['session_id_user'] = $this->session->userdata('id_user');

            $this->data['active_item_menu'] = 'classif';

            $this->load->library('form_validation');

//            //TWIG
//            $this->load->library('twig');
//
//            //$this->twig->addGlobal('sitename', 'My Awesome Site');
//            $this->twig->addGlobal('ORGAN_ID_RCU', 5);
        } else {
            redirect('creator/catalog');
        }
    }

    public function index()
    {

    }

    public function vid_sd()
    {

        $this->data['bread_crumb'] = array(array('/classif/vid_sd' => 'Классификаторы'),
            array('Вид СД')
        );
        $this->data['vid_sd'] = $this->classif_model->get_vid_sd(0);
        $this->data['title'] = 'Классификаторы';
        $this->twig->display('classif/vid_sd/index', $this->data);
    }

    public function form_add_vid_sd()
    {

        $this->data['bread_crumb'] = array(array('/classif/vid_sd' => 'Классификаторы'),
            array('/classif/vid_sd' => 'Вид СД'),
            array('Создать')
        );

        //$this->data['all_users'] = $this->user_model->get_all_active_permissions();
        $this->data['title'] = 'Классификаторы';
        $this->twig->display('classif/vid_sd/form_add_vid_sd', $this->data);
    }

    public function is_unique_vid_sd()
    {
        $post = $this->input->post();
        $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {

            if (!empty($vid['id'])) {
                $is_vid = $this->classif_model->get_vid_sd_by_id($vid['id']);
                if (isset($is_vid) && !empty($is_vid)) {
                    echo json_encode(array('error' => 'Вид с таким ID уже существует'));
                    die();
                }
            }
            if (!empty($vid['name'])) {
                $is_vid_name = $this->classif_model->get_vid_sd_by_name($vid['name']);

                if (isset($is_vid_name) && !empty($is_vid_name))
                    echo json_encode(array('error' => 'Вид с таким названием уже существует'));
                else
                    echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены на форме создания'));
    }

    public function save_vid_sd()
    {
        $post = $this->input->post();
        if (isset($post['id']) && !empty($post['id']))
            $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {
            $vid['created_by'] = $this->session->userdata('id_user');
            $vid['updated_by'] = $this->session->userdata('id_user');
            $vid['date_insert'] = date('Y-m-d H:i:s');
            $vid['last_update'] = date('Y-m-d H:i:s');

            $this->classif_model->add_vid_sd($vid);
        }
        redirect('classif/vid_sd');
    }

    public function edit_vid_sd()
    {
        $post = $this->input->post();
        $vid['id'] = $id = $post['id'];
        $vid['name'] = trim($post['name']);

        if (isset($vid) && !empty($vid) && !empty($vid['name']) && !empty($vid['id'])) {

            $is_vid = $this->classif_model->get_vid_sd_by_id_name($vid['id'], $vid['name']);
            if (isset($is_vid) && !empty($is_vid))
                echo json_encode(array('error' => 'Вид с таким названием уже существует'));
            else {

                $vid['updated_by'] = $this->session->userdata('id_user');
                $vid['last_update'] = date('Y-m-d H:i:s');

                unset($vid['id']);
                $this->classif_model->edit_vid_sd($id, $vid);
                echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }

    public function delete_vid_sd()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $vid['is_delete'] = 1;
        $vid['updated_by'] = $this->session->userdata('id_user');
        $vid['last_update'] = date('Y-m-d H:i:s');


        if (isset($id) && !empty($id)) {

            $this->classif_model->edit_vid_sd($id, $vid);
            echo json_encode(array('success' => 'Вид СД был удален из БД'));
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }

    public function vid_hs()
    {

        $this->data['bread_crumb'] = array(array('/classif/vid_hs' => 'Классификаторы'),
            array('Вид ЧС')
        );
        $this->data['vid_hs'] = $this->classif_model->get_vid_hs_1(0);
        $this->data['title'] = 'Классификаторы';
        $this->twig->display('classif/vid_hs/index', $this->data);
    }

    public function is_unique_vid_hs()
    {
        $post = $this->input->post();
        $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {

            if (!empty($vid['id'])) {
                $is_vid = $this->classif_model->get_vid_hs_1_by_id($vid['id']);
                if (isset($is_vid) && !empty($is_vid)) {
                    echo json_encode(array('error' => 'Вид с таким ID уже существует'));
                    die();
                }
            }
            if (!empty($vid['name'])) {
                $is_vid_name = $this->classif_model->get_vid_hs_1_by_name($vid['name']);

                if (isset($is_vid_name) && !empty($is_vid_name))
                    echo json_encode(array('error' => 'Вид с таким названием уже существует'));
                else
                    echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены на форме создания'));
    }

    public function save_vid_hs()
    {
        $post = $this->input->post();

        if (isset($post['id']) && !empty($post['id']))
            $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {
            $vid['created_by'] = $this->session->userdata('id_user');
            $vid['updated_by'] = $this->session->userdata('id_user');
            $vid['date_insert'] = date('Y-m-d H:i:s');
            $vid['last_update'] = date('Y-m-d H:i:s');

            $this->classif_model->add_vid_hs_1($vid);
        }
        redirect('classif/vid_hs');
    }

    public function edit_vid_hs()
    {
        $post = $this->input->post();
        $vid['id'] = $id = $post['id'];
        $vid['name'] = trim($post['name']);

        if (isset($vid) && !empty($vid) && !empty($vid['name']) && !empty($vid['id'])) {

            $is_vid = $this->classif_model->get_vid_hs_1_by_id_name($vid['id'], $vid['name']);
            if (isset($is_vid) && !empty($is_vid))
                echo json_encode(array('error' => 'Вид с таким названием уже существует'));
            else {

                $vid['updated_by'] = $this->session->userdata('id_user');
                $vid['last_update'] = date('Y-m-d H:i:s');

                unset($vid['id']);
                $this->classif_model->edit_vid_hs($id, $vid);
                echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }

    public function delete_vid_hs()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $vid['is_delete'] = 1;
        $vid['updated_by'] = $this->session->userdata('id_user');
        $vid['last_update'] = date('Y-m-d H:i:s');


        if (isset($id) && !empty($id)) {

            $this->classif_model->edit_vid_hs($id, $vid);
            echo json_encode(array('success' => 'Вид СД был удален из БД'));
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }

        public function type_hs()
    {

        $this->data['bread_crumb'] = array(array('/classif/type_hs' => 'Классификаторы'),
            array('Тип ЧС')
        );
        $this->data['vid_hs'] = $this->classif_model->get_vid_hs_1(0);
        $this->data['type_hs'] = $this->classif_model->get_type_hs(0);
        $this->data['title'] = 'Классификаторы';
        $this->twig->display('classif/type_hs/index', $this->data);
    }



    public function is_unique_type_hs()
    {
        $post = $this->input->post();
        $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);
        $vid['id_vid_hs_1'] = $post['vid_hs_1'];


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {

            if (!empty($vid['id'])) {
                $is_vid = $this->classif_model->get_type_hs_by_id($vid['id']);
                if (isset($is_vid) && !empty($is_vid)) {
                    echo json_encode(array('error' => 'Тип с таким ID уже существует'));
                    die();
                }
            }
            if (!empty($vid['name'])) {
                $is_vid_name = $this->classif_model->get_type_hs_by_name_and_vid($vid['name'],$vid['id_vid_hs_1']);

                if (isset($is_vid_name) && !empty($is_vid_name))
                    echo json_encode(array('error' => 'Тип с таким названием и(или) видом уже существует'));
                else
                    echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены на форме создания'));
    }



    public function save_type_hs()
    {
        $post = $this->input->post();

        if (isset($post['id']) && !empty($post['id']))
            $vid['id'] = $post['id'];
        $vid['name'] = trim($post['name']);
                $vid['id_vid_hs_1'] = $post['vid_hs_1'];


        if (isset($vid) && !empty($vid) && !empty($vid['name'])) {
            $vid['created_by'] = $this->session->userdata('id_user');
            $vid['updated_by'] = $this->session->userdata('id_user');
            $vid['date_insert'] = date('Y-m-d H:i:s');
            $vid['last_update'] = date('Y-m-d H:i:s');

            $this->classif_model->add_type_hs($vid);
        }
        redirect('classif/type_hs');
    }




    public function edit_type_hs()
    {
        $post = $this->input->post();
        $vid['id'] = $id = $post['id'];
        $vid['name'] = trim($post['name']);
        $vid['id_vid_hs_1'] = $post['vid'];

        if (isset($vid) && !empty($vid) && !empty($vid['name']) && !empty($vid['id'])) {

            $is_vid = $this->classif_model->get_type_hs_by_name_and_vid($vid['name'],$vid['id_vid_hs_1']);
            if (isset($is_vid) && !empty($is_vid))
                echo json_encode(array('error' => 'Тип с таким названием уже существует'));
            else {

                $vid['updated_by'] = $this->session->userdata('id_user');
                $vid['last_update'] = date('Y-m-d H:i:s');

                unset($vid['id']);
                $this->classif_model->edit_type_hs($id, $vid);
                echo json_encode(array('success' => 'Данные успешно сохранены'));
            }
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }



    public function delete_type_hs()
    {
        $post = $this->input->post();
        $id = $post['id'];


        $vid['is_delete'] = 1;
        $vid['updated_by'] = $this->session->userdata('id_user');
        $vid['last_update'] = date('Y-m-d H:i:s');


        if (isset($id) && !empty($id)) {

            $this->classif_model->edit_type_hs($id, $vid);
            echo json_encode(array('success' => 'Тип СД был удален из БД'));
        } else
            echo json_encode(array('error' => 'Не все данные заполнены'));
    }
}
