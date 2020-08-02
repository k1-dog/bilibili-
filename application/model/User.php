<?php

namespace app\model;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class User extends Model
{
    public $user;
    public $pwd = '该用户不存在或是密码错误~~!~~';

    public function index($user)
    {
        $this->user = $user;
    }
}