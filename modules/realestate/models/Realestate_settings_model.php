<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Realestate_settings_model extends App_Model
{
    private $table = 'tblre_settings';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_setting($key, $default = '')
    {
        $this->db->where('setting_key', $key);
        $result = $this->db->get($this->table)->row();

        return $result ? $result->setting_value : $default;
    }

    public function get_all_settings()
    {
        $settings = [];
        $results = $this->db->get($this->table)->result();

        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }

        return $settings;
    }

    public function update_setting($key, $value)
    {
        $this->db->where('setting_key', $key);
        $existing = $this->db->get($this->table)->row();

        if ($existing) {
            $this->db->where('setting_key', $key);
            return $this->db->update($this->table, [
                'setting_value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->db->insert($this->table, [
                'setting_key' => $key,
                'setting_value' => $value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function delete_setting($key)
    {
        $this->db->where('setting_key', $key);
        return $this->db->delete($this->table);
    }
}
