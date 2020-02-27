<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_cookie($n, $titles)
{
    $cookie = $this->input->cookie('key_cookie_speciald', true);

    $id = $this->session->userdata('id_user');

    if (!isset($id) || empty($id)) {

        if (isset($cookie) && !empty($cookie)) {

        }
    }
}
