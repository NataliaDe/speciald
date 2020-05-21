<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends My_Controller
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
        $this->load->model('user_model');
    }

    public function index()
    {
        $this->data['active_item_menu'] = 'settings';
        $this->data['title'] = 'Настройки';

        $this->data['settings'] = $this->user_model->get_settings_list_by_role($this->data['active_user']['role']);
        if (!empty($this->data['settings'])) {
            foreach ($this->data['settings'] as $key => $value) {
                $this->data['settings'][$key]['options'] = $this->user_model->get_settings_options($value['id']);
            }
        }

        $this->data['settings_options_users'] = $this->user_model->get_settings_options_users($this->data['active_user']['id_user']);

        if (!empty($this->data['settings_options_users']))
            $this->data['user_options'] = array_column($this->data['settings_options_users'], 'id_settings_option');

        //$this->data['settings_options'] = $this->user_model->get_settings_list_by_role($this->data['active_user']['role']);
        $this->twig->display('settings/index', $this->data);
    }

    public function save()
    {
        $post = $this->input->post();
        //print_r($post);

        $this->user_model->delete_settings_options_users($this->data['active_user']['id_user']);



        if (isset($post['id_settings_option']) && !empty($post['id_settings_option'])) {
            foreach ($post['id_settings_option'] as $key => $value) {

                foreach ($value as $row) {
                    $save = [];
                    $save['id_settings_option'] = $row;
                    $save['id_user'] = $this->data['active_user']['id_user'];
                    $save['last_update'] = date('Y-m-d H:i:s');

                    if (!empty($save))
                        $this->user_model->save_settings_options_users($save);

                }
            }
        }
        // exit();
        redirect('/settings');
    }
}
