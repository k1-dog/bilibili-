<?php


namespace app\controller;
use think\Controller;


class Account extends Controller
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        return $this->face();
    }

    public function face()
    {
        try {
            return $this->view->fetch('account/face');
        } catch (\Exception $e) {
        }
    }
}