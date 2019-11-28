<?php
namespace app\service;

class productBackendService
{
    public static function classFormat($data, $pid=0, $level=1)
    {
        return self::formatTree(self::getTree($data,$pid),$level);
    }

    private static function getTree($list, $pid = 0)
    {
        $tree = [];
        if (!empty($list)) {
            //先修改为以id为下标的列表
            $newList = [];
            foreach ($list as $k => $v) {
                if( $v['status'] == 1 ) continue;
                $newList[$v['cls_id']] = $v;
            }
            //然后开始组装成特殊格式
            foreach ($newList as $value) {
                if ($pid == $value['parentid']) {//先取出顶级
//                    $newList[$value['cls_id']]['level'] = 1;
                    $tree[] = &$newList[$value['cls_id']];
                } elseif (isset($newList[$value['parentid']])) {//再判定非顶级的pid是否存在，如果存在，则再pid所在的数组下面加入一个字段items，来将本身存进去
//                    $newList[$value['cls_id']]['level'] = $newList[$value['parentid']]['level']+1;
                    $newList[$value['parentid']]['items'][] = &$newList[$value['cls_id']];
                }
            }
        }
        return $tree;
    }

    private static function formatTree($tree, $level = 1)
    {
        $options = [];
        if (!empty($tree)) {
            foreach ($tree as $key => $value) {
                $options[$value['cls_id']] = $value;
                $options[$value['cls_id']]['level'] = $level;
                $options[$value['cls_id']]['code_sub'] = ltrim(substr($options[$value['cls_id']]['code'],($level-1)*3,3),0);
                unset( $options[$value['cls_id']]['items'] );
                if (isset($value['items'])) {//查询是否有子节点
                    $optionsTmp = self::formatTree($value['items'],$level+1);
                    if (!empty($optionsTmp)) {
                        //$options = array_merge($options, $optionsTmp);
                        $options = $options + $optionsTmp;//用array_merge会导致索引重排
                    }
                }
            }
        }
        return $options;
    }
}