<?php

// +----------------------------------------------------------------------
// | date: 2015-07-04
// +----------------------------------------------------------------------
// | BaseModel.php: 公共模型
// +----------------------------------------------------------------------
// | Author: yangyifan <yangyifanphp@gmail.com>
// +----------------------------------------------------------------------

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

use DB;

use \Session;

class BaseModel extends Model
{


    const SEPARATED         = ','; //分隔符
    const HOTEL_TYPE_1      = 1;//酒店
    const HOTEL_TYPE_2      = 2;//民宿
    const HOTEL_TYPE_3      = 3;//公寓式
    const HOTEL_NOT_EXISTS  = -100;//酒店不存在
    const LANG_ZH           = 'zh';//中文语言包
    const LANG_KOREA        = 'korea';//韩文语言包

    public static $locale   = null;//语言

    /**
     * 搜索
     *
     * @param $map
     * @param $sort
     * @param $order
     * @param $offset
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected static function search($map, $sort, $order, $limit, $offset)
    {
        return [
            'data' => self::mergeData(
                self::multiwhere($map)->
                orderBy($sort, $order)->
                skip($offset)->
                take($limit)->
                get()
            ),
            'count' => self::multiwhere($map)->count(),
        ];
    }

    /**
     * 多条件查询where
     *
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public function scopeMultiwhere($query, $arr)
    {
        if (!is_array($arr)) {
            return $query;
        }

        if (empty($arr)) {
            return $query;
        }

        foreach ($arr as $key => $value) {
            //判断$arr
            if(is_array($value)){
                $value[0] = strtolower($value[0]);
                switch($value[0]){
                    case 'like';
                        $query = $query->where($key, $value[0] ,$value[1]);
                        break;
                    case 'in':
                        $query = $query->whereIn($key, $value[1]);
                        break;
                    case 'between':
                        $query = $query->whereBetween($key, [$value[1][0], $value[1][1]]);
                        break;
                    default:
                        $query = $query->where($key, $value[0], $value[1]);
                        break;
                }
            }else{
                $query = $query->where($key, $value);
            }
        }
        return $query;
    }

    /**
     * 获得全部文章分类--无限极分类（编辑菜单时选项）
     *
     * @descript  递归组合无限极分类，为了编辑页面和增加页面select 展示
     * @param $name 表单name名称
     * @param $id 当前id
     * @return array
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getAllForSchemaOption($name, $id = 0, $first = true)
    {
        $data = $id > 0 ? mergeTreeNode(objToArray(self::where('id', '<>' , $id)->get())) : mergeTreeNode(objToArray(self::all()));
        $first == true && array_unshift($data, ['id' => '0', $name => '顶级分类']);
        return $data;
    }

    /**
     * 获得全部文章分类--无限极分类（编辑菜单时选项）
     *
     * @descript  递归组合无限极分类，为了编辑页面和增加页面select 展示
     * @param $name 表单name名称
     * @param $id 当前id
     * @return array
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getAllParentName($name, $id = 0, $first = true)
    {
        $data = $id > 0 ? mergeTreeNode(objToArray(self::where('id', '<>' , $id)->get())) : mergeTreeNode(objToArray(self::all()));

        $first == true && array_unshift($data, ['id' => '0', $name => '顶级分类']);
        return $data;
    }
    /**
     * 获得全部文章分类--无限极分类（编辑菜单时选项）
     *
     * @descript  递归组合无限极分类，为了编辑页面和增加页面select 展示
     * @param $name 表单name名称
     * @param $id 当前id
     * @return array
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getAllFunctionName($name, $id = 0, $first = true)
    {
        $data = $id > 0 ? mergeTreeNode(objToArray(self::where('id', '<>' , $id)->get())) : mergeTreeNode(objToArray(self::all()));

        $first == true && array_unshift($data, ['id' => '0', $name => '顶级函数']);
        return $data;
    }

    /**
     * 打印最后一条执行sql
     *
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getLastSql($type = false)
    {

        $sql = DB::getQueryLog();
        if ($type == true ) {
            var_dump($sql);die;
        }

        $query = end($sql);
        var_dump($query);die;
    }

    /**
     * 获得当前语言
     *
     * @return string
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getLocale()
    {
        $locale = \Cookie::get('locale');
        return !empty($locale) ? $locale : 'zh';
    }

    /**
     * 更新图片到数据库
     *
     * @param $image_name
     * @param $id
     * @return bool
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function updateImage($image_name, $id)
    {
        if (!empty($image_name) && $id > 0 ) {
            return self::multiwhere( ['id' => $id] )->update([
                static::INPUT_NAME => $image_name,
            ]) > 0 ? true : false;
        }
        return false;
    }

    /**
     * 获得图片资源类型 (默认为)
     *
     * @return mixed|null
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getImageSourceType()
    {
        if (is_null(static::$source_type)) {
            static::$source_type = 'article';
        }
        return static::$source_type;
    }

    /**
     * 获得图片类型
     *
     * @return mixed|null
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function getImageType()
    {
        if (is_null(static::$image_type)) {
            static::$image_type = 'article';
        }
        return static::$image_type;
    }


}

