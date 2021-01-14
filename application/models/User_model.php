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
                    'id_user'    => $cookie,
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
        $data['created_by'] = $this->session->userdata('id_user');
        $data['date_create'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
        return $this->db->insert_id();
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
        $data['updated_by'] = $this->session->userdata('id_user');
        $data['date_update'] = date('Y-m-d H:i:s');
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

    public function get_users_free_journal()
    {
        return $this->db->select('*')
                ->from('journal.permissions')
                ->where([
                    'id_user_sd' => null
                ])
                ->get()
                ->result_array();
    }

    public function set_user_sd_to_journal($id_user_journal, $id_user)
    {
        $this->db->set('id_user_sd', $id_user)
            ->where('id', $id_user_journal)
            ->update('journal.user');
    }

    public function get_users_journal_for_edit($id_user_sd)
    {
        return $this->db->select('*')
                ->from('journal.permissions')
                ->where([
                    'id_user_sd' => null
                ])
                ->or_where('id_user_sd', $id_user_sd)
                ->get()
                ->result_array();
    }

    public function get_ids_user_sd_from_journal()
    {
        return $this->db->select('id_user_sd')
                ->from('journal.permissions')
                ->where('id_user_sd is not null')
                ->get()
                ->result_array();
    }

    public function reset_user_sd_in_journal($id_user_sd, $id_user_sd_new)
    {
        $this->db->set('id_user_sd', $id_user_sd_new)
            ->where('id_user_sd', $id_user_sd)
            ->update('journal.user');
    }

    public function get_user_journal_by_user_sd($id_user_sd)
    {
        $res = $this->db->select('id')
            ->from('journal.user')
            ->where('id_user_sd', $id_user_sd)
            ->get()
            ->row_array();

        return $res['id'];
    }

    public function get_settings_list_by_role($role)
    {
        return $this->db->select('*')
                ->from('settings')
                ->group_start()
                ->where('role', $role)
                ->or_where('role', 'all')
                ->group_end()
                ->get()
                ->result_array();
    }

    public function get_settings_options($id_settings)
    {
        return $this->db->select('*')
                ->from('settings_options')
                ->where('id_settings', $id_settings)
                ->get()
                ->result_array();
    }

    public function get_settings_options_users($id_user)
    {
        return $this->db->select('*')
                ->from('settings_options_users')
                ->where('id_user', $id_user)
                ->get()
                ->result_array();
    }

    public function delete_settings_options_users($id_user)
    {
        $this->db->where('id_user', $id_user)->delete('settings_options_users');
    }

    public function save_settings_options_users($data)
    {
        $this->db->insert('settings_options_users', $data);
    }

    public function get_user_settings_type_sd($id_user, $type_sd)
    {
        $this->db->select('s.name as setting_name, s.type as settings_type,so.option');
        $this->db->where('su.id_user', $id_user);
        $this->db->group_start();
        $this->db->where('s.type_sd', $type_sd);
        $this->db->or_where('s.type_sd', 'all');
        $this->db->group_end();
        $this->db->join('settings_options as so', 'su.id_settings_option=so.id', 'left');
        $this->db->join('settings as s', 's.id=so.id_settings', 'left');

        $result = $this->db->get('settings_options_users as su')->result_array();
        return $result;
    }

        public function get_user_settings_format($settings)
    {
        $result = [];

        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $result[$value['settings_type']][] = $value;
            }
        }
        return $result;
    }

            public function get_user_settings_options_format($settings)
    {
        $result = [];

        if (!empty($settings)) {
            foreach ($settings as $key => $value) {
                $result[$value['settings_type']][] = $value['option'];
            }
        }
        return $result;
    }


        public function get_data_user_journal_by_user_sd($id_user_sd)
    {
        $res = $this->db->select('*')
            ->from('journal.user')
            ->where('id_user_sd', $id_user_sd)
            ->get()
            ->row_array();

        return $res;
    }

        public function get_settings_by_region($id_region,$setting_type,$val_setting)
    {
        $this->db->select('s.name as setting_name, s.type as settings_type,so.option');
        $this->db->where('u.id_region', $id_region);
        $this->db->where('s.type', $setting_type);
        $this->db->where('so.option', $val_setting);
        $this->db->join('users as u', 'u.id=su.id_user', 'left');
        $this->db->join('settings_options as so', 'su.id_settings_option=so.id', 'left');
        $this->db->join('settings as s', 's.id=so.id_settings', 'left');

        $result = $this->db->get('settings_options_users as su')->result_array();
        return $result;
    }



}
