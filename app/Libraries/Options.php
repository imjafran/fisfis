<?php

trait Options
{     
    public function get_option($name = '', $default = null)
    {
        if(empty($name)) return false;
        $option = $this->db->table('options')->getWhere(['name' => $name])->getRow();
        if($option){
            $value = $option->value;
            try {
                $value = unserialize($value);
            } catch(\Exception $e) {}

            return $value;
        }
        return $default;
    }    

    public function update_option($name = '', $value = null)
    {
        if(empty($name)) return false;
        if(is_array($value) || is_object($value)) {
            $value = serialize($value);
        }

        $found = $this->db->table('options')->getWhere(['name' => $name])->getRow();
        if($found) {
            $updated = $this->db->table('options')->set('value', $value)->where('name', $name)->update();
        } else {
            $updated = $this->add_option($name, $value);
        }
        
        
        return $updated ?? false;
    }    

    public function add_option($name = '', $value = null)
    {
        if(empty($name)) return false;
        if(is_array($value) || is_object($value)) {
            $value = serialize($value);
        }
        
        $inserted = $this->db->table('options')->insert([
            'name' => $name,
            'value' => $value,
        ]);
        return $inserted ?? false;
    }    
}