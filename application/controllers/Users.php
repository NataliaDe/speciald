<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends My_Controller
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
            $this->load->model('user_model');


            $this->data['regions'] = $this->main_model->get_regions();
            $this->data['locals'] = $this->main_model->get_locals();
            $this->data['organs'] = $this->main_model->get_organs_in_local();
            $this->data['positions'] = $this->main_model->get_positions();
            $this->data['ranks'] = $this->main_model->get_ranks();

            $this->data['session_id_user'] = $this->session->userdata('id_user');

            $this->data['active_item_menu'] ='users';

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
        $this->data['all_users'] = $this->user_model->get_all_active_permissions();
        $this->data['title'] = 'Пользователи';
        $this->twig->display('users/index', $this->data);
    }

    //user form add
    public function form_add()
    {

        $this->data['title'] = 'Добавить пользователя';
        $this->twig->display('users/form_add', $this->data);
    }

    //create new user
    public function add()
    {

        $post = $this->input->post();

        if (isset($post) && !empty($post)) {

            $id_user=(isset($post['id_user']) && !empty($post['id_user'])) ? $post['id_user'] : 0;


                $data['id_region'] = $post['id_region'];
                $data['id_local'] = $post['id_local'];
                $data['fio'] = trim($post['fio']);
                $data['creator_name'] = trim($post['creator_name']);
                $data['id_position'] = $post['id_position'];
                $data['id_rank'] = $post['id_rank'];

                $data['login'] = trim($post['login']);
                $data['password'] = trim($post['password']);

                if (isset($post['id_organ']) && $post['id_organ'] == Main_model::ORGAN_ID_RCU) {

                    $data['sub'] = 1;
                    $data['level'] = Main_model::LEVEL_ID_RCU;
                    $data['id_organ'] = $post['id_organ'];
                } elseif (isset($post['id_organ']) && in_array($post['id_organ'], array(Main_model::ORGAN_ID_ROSN, Main_model::ORGAN_ID_UGZ, Main_model::ORGAN_ID_AVIA))) {

                    $data['sub'] = 2;
                    $data['id_organ'] = $post['id_organ'];

                    if ($post['id_region'] == 3 && $post['id_local'] == 123) {
                        //$data['level'] = Main_model::LEVEL_ID_UMCHS;
                        $data['level'] = Main_model::LEVEL_ID_ROCHS;
                    } else {
                        $data['level'] = Main_model::LEVEL_ID_ROCHS;
                    }
                } else {
                    $data['id_organ'] = 0;
                    $data['sub'] = 0;

                    if (isset($post['id_local']) && $post['id_local'] != 0) {
                        $data['level'] = Main_model::LEVEL_ID_ROCHS;
                    } else {
                        $data['level'] = Main_model::LEVEL_ID_UMCHS;
                    }
                }



                $data['can_edit'] = (isset($post['can_edit']) && !empty($post['can_edit'])) ? $post['can_edit'] : 0;

                if ($data['can_edit'] == 1) {
                    $data['role'] = 'creator';
                    $data['is_admin'] = $post['is_admin'];
                } else {
                    $data['role'] = 'viewer';
                    $data['is_admin'] = 0;
                }


                //edit
                if($id_user != 0)
                 $this->user_model->edit_user($id_user,$data);
                    else
            $this->user_model->add_user($data);

            if ($data['can_edit'] == 1) {
                $is_user_for_journal = $this->user_model->get_cnt_users_for_is_guest($data);

                if ($is_user_for_journal == 0) {//add guset for auth from journal
                    unset($data['fio']);
                    unset($data['creator_name']);
                    $data['id_position'] = 0;
                    $data['id_rank'] = 0;
                    $data['is_guest'] = 1;
                    $data['login'] = $data['login'] . '_jour';
                    $data['password'] = $data['password'] . '_jour';

                    $this->user_model->add_user($data);
                }
            }
        }

        redirect('users');
    }

    //check user by login and password us unique?
    public function is_user_unique()
    {

        $login = trim($this->input->post('login', true));
        $password = trim($this->input->post('password', true));

        $id_user = trim($this->input->post('id_user', true));

        if (isset($id_user) && !empty($id_user))
            $is_user = $this->user_model->get_user_by_login_password_exclude($login, $password, $id_user);
        else
            $is_user = $this->user_model->get_user_by_login_password($login, $password);
        if (isset($is_user) && !empty($is_user)) {
            echo json_encode(array('error' => 'Пользователь с таким логином м паролем уже существует'));
        } else {
            echo json_encode(array('success' => 'Данные успешно сохранены'));
        }
    }

    //user form edit
    public function form_edit($id_user = 0)
    {

        if ($id_user != 0) {

            $this->data['user'] = $this->user_model->get_user_by_id($id_user);

            if ($this->data['user']['is_guest'] == 0) {
                $this->data['title'] = 'Редактировать пользователя';
                $this->twig->display('users/form_add', $this->data);
            } else {
                redirect('users');
            }
        } else {
            redirect('users');
        }
    }

    //user guest form edit
    public function form_edit_guest($id_user = 0)
    {

        if ($id_user != 0) {

            $this->data['user'] = $this->user_model->get_user_by_id($id_user);

            if ($this->data['user']['is_guest'] == 1) {
                $this->data['title'] = 'Редактировать пользователя';
                $this->twig->display('users/form_edit_guest', $this->data);
            }
        } else {
            redirect('users');
        }
    }

    //edit guest
    public function edit_user_guest()
    {

        $post = $this->input->post();

        if (isset($post) && !empty($post)) {

            $id_user = (isset($post['id_user']) && !empty($post['id_user'])) ? $post['id_user'] : 0;

            $data['login'] = trim($post['login']);
            $data['password'] = trim($post['password']);

            //edit
            if ($id_user != 0)
                $this->user_model->edit_user($id_user, $data);
        }

        redirect('users');
    }

    // delete user ajax
    public function delete($id_user = 0)
    {
        if (isset($id_user) && !empty($id_user))
             $this->user_model->delete_user( $id_user);

            echo json_encode(array('success' => 'Данные успешно сохранены'));
    }
}
