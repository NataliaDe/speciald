<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends My_Controller
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

        $this->load->model('user_model');
        $this->load->model('dones_model');
        $this->load->model('logs_model');
        $this->load->model('create_model');

        $this->data['active_item_menu'] = 'catalog';


        //TWIG
        //$this->load->library('twig');
        //$this->twig->addGlobal('sitename', 'My Awesome Site');
    }

    public function get_sd_media_modal()
    {
        if ($this->input->is_ajax_request()) {

            $id_dones = $this->input->post('id');

            if (isset($id_dones) && !empty($id_dones)) {

                $media = $this->dones_model->get_dones_media($id_dones);
                $dones = $this->create_model->get_dones_by_id($id_dones);

                echo json_encode([
                    'innerHtml' => $this->twig->render('common/catalog/sd_media_modal', [
                        'media' => $media,
                        'dones'=>$dones
                    ]),
                    'success'   => 1
                ]);
            } else {
                echo json_encode([
                    'error' => 'СД не выбрано'
                ]);
            }
        }
    }
}
