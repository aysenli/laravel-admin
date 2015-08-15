<?php

// +----------------------------------------------------------------------
// | date: 2015-07-11
// +----------------------------------------------------------------------
// | SiteModel.php: 会员网址模型
// +----------------------------------------------------------------------
// | Author: yangyifan <yangyifanphp@gmail.com>
// +----------------------------------------------------------------------

namespace App\Model\User;

use App\Model\Admin\BaseModel;

class SiteModel extends BaseModel {

    protected $table    = 'user_site';//定义表名
    protected $guarded  = ['id'];//阻挡所有属性被批量赋值

}