<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_by_login_password($login, $password)
    {
        return $this->db->select('*')
                ->from('permissions')
                ->where([
                    'login'    => $login,
                    'password' => $password
                ])
                ->get()
                ->row_array();
    }

    public function get_user_by_login_password_exclude($login, $password, $id_user)
    {
        return $this->db->select('*')
                ->from('permissions')
                ->where([
                    'login'    => $login,
                    'password' => $password
                ])
                ->where_not_in('id_user', $id_user)
                ->get()
                ->row_array();
    }

    public function set_key_cookie($id_user, $key)
    {
        $this->db->set('key_cookie', $key)
            ->where('id', $id_user)
            ->update('users');
    }

    public function delete_key_cookie($id_user)
    {
        $this->db->set('key_cookie', '')
            ->where('id', $id_user)
            ->update('users');
    }

    public function get_user_by_cookie($cookie)
    {
        return $this->db->select('*')
                ->from('permissions')
                ->where([
                    'id_user'         => $cookie,
                    'key_cookie' => $cookie
                ])
                ->get()
                ->row_array();
    }

    public function get_cnt_users_for_is_guest($data)
    {
        $query = $this->db->where('can_edit', 1)
            ->where('id_region', $data['id_region'])
            ->where('id_local', $data['id_local'])
            ->where('sub', $data['sub'])
            ->where('level', $data['level'])
            ->where('id_organ', $data['id_organ'])
            ->where('is_guest', 1)
            ->get('users');

        return $query->num_rows();
    }

    public function add_user($data)
    {
        $data['created_by']=$this->session->userdata('id_user');
        $data['date_create']=date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
    }

    public function get_all_active_permissions()
    {
        return $this->db->select('*')
                ->from('permissions')
                ->where('is_delete', 0)
                ->get()
                ->result_array();
    }

    public function get_user_by_id($id)
    {
        return $this->db->select('*')
                ->from('users')
                ->where([
                    'id' => $id
                ])
                ->get()
                ->row_array();
    }

    public function edit_user($id_user, $data)
    {
        $data['updated_by']=$this->session->userdata('id_user');
        $data['date_update']=date('Y-m-d H:i:s');
        $this->db->update('users', $data, ['id' => $id_user]);
    }

    public function delete_user($id_user)
    {
        $this->db->set('is_delete', 1)
            ->set('deleted_by', $this->session->userdata('id_user'))
            ->set('date_delete', date('Y-m-d H:i:s'))
            ->where('id', $id_user)
            ->update('users');
        //$this->db->where('id', $id_user)->delete('users');
    }


        public function get_permissions_by_user_id($id)
    {
        return $this->db->select('*')
                ->from('permissions')
                ->where([
                    'id_user' => $id
                ])
                ->get()
                ->row_array();
    }


            public function get_journal_permissions_by_user_id($id)
    {
        return $this->db->select('*')
                ->from('journal.permissions')
                ->where([
                    'id_user' => $id
                ])
                ->get()
                ->row_array();
    }
}
