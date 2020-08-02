<?php


namespace app\model;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class Userinfo extends Model
{
    public $uInfo;
    public function retrieveUser($user)
    {
        try {
            $this->uInfo = self::where('username', $user)->findOrFail();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
            //----
        } catch (DbException $e) {
        }
        return $this->uInfo;
    }
}