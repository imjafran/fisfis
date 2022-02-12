<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $allowedFields = ['name', 'email', 'id', 'fb_id', 'fb_access_token', 'access_token', 'username', 'enabled', 'is_admin'];

    protected $createdField  = 'created_at';
}
