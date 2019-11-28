<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-01
 * Time: 11:06
 */

namespace common\menu;

use Yii;

class MenuCore
{
    const ADMIN_ID = 1;

    public static $rule_list = [
        [
            'id' => 1,
            'pid' => 0,
            'route' => 'setting',
            'title' => '设置',
            'icon' => '',
            'type' => 2,
            'condition' => 'mk',
            'order' => 88,
            'tips' => '',
            'is_show' => 1,
            'status' => 1,
            'is_on_show' => 1,
            'is_have_part' => 1,
            'expanded' => false,
            'children' => [
                [
                    'id' => 2,
                    'pid' => 1,
                    'route' => 'shop/set-merchants',
                    'title' => '商户设置',
                    'order' => 1,
                    'is_show' => 1,
                    'status' => 1,
                    'is_on_show' => 0,
                    'is_have_part' => 0,
                    'leaf' => true,
                ],
                [
                    'id' => 3,
                    'pid' => 1,
                    'route' => 'shop/up-password',
                    'title' => '修改密码',
                    'order' => 2,
                    'is_show' => 1,
                    'status' => 1,
                    'is_on_show' => 0,
                    'is_have_part' => 0,
                    'leaf' => true,
                ]
            ],
        ],
        [
            'id' => 10,
            'route' => 'broadcast',
            'title' => '广告',
            'is_show' => 0,
            'status' => 1,
            'children' => [
                [
                    'id' => 11,
                    'route' => 'broadcast/index',
                    'title' => '暂无',
                    'is_show' => 1,
                    'status' => 1,
                ]
            ],
        ],
        [
            'id' => 20,
            'route' => 'coupon',
            'title' => '商品',
            'is_show' => 1,
            'status' => 1,
            'children' => [
                [
                    'id' => 21,
                    'route' => 'goods/list',
                    'title' => '商品管理',
                    'is_show' => 1,
                    'status' => 1,
                    'children' => [
                        [
                            'id' => 211,
                            'route' => 'goods/detail'
                        ],
                        [
                            'id' => 212,
                            'route' => 'goods/edit'
                        ],
                    ]
                ]
            ],
        ],
        [
            'id' => 30,
            'route' => 'coupon',
            'title' => '优惠',
            'is_show' => 1,
            'status' => 1,
            'children' => [
                [
                    'id' => 31,
                    'route' => 'coupon/list',
                    'title' => '优惠券',
                    'is_show' => 1,
                    'status' => 1,
                    'children' => [
                        [
                            'id' => 311,
                            'route' => 'coupon/detail'
                        ],
                        [
                            'id' => 312,
                            'route' => 'coupon/edit'
                        ],
                    ]
                ]
            ],
        ],
        [
            'id' => 40,
            'route' => 'broadcasts',
            'title' => '广告',
            'is_show' => 1,
            'status' => 1,
            'children' => [
                [
                    'id' => 41,
                    'route' => 'broadcasts/list',
                    'title' => '广告列表',
                    'is_show' => 1,
                    'status' => 1,
                    'children' => [
                        [
                            'id' => 411,
                            'route' => 'broadcasts/detail'
                        ],
                        [
                            'id' => 412,
                            'route' => 'broadcasts/edit'
                        ],
                        [
                            'id' => 413,
                            'route' => 'broadcasts/add'
                        ],
                    ]
                ]
            ],
        ],
    ];


    public static $menu_key = [
        1,2
    ];

    public static $role_list = [1];


    public static $_menuCurrentId = [];

    public static function init()
    {
        $route = Yii::$app->request->getQueryParam('r');//当前路由模块 不同项目里可能访问形式不一样 所以倒是这里需要更改
        foreach (self::$rule_list as $k => $item) {
            if(isset($item['children'])) {
                foreach ($item['children'] as $v) {
                    if($v['route'] == $route) {
                        array_push(self::$_menuCurrentId, $item['id'], $v['id']);
                    }elseif(isset($v['children'])) {
                        foreach ($v['children'] as $vc) {
                            if($vc['route'] == $route) {
                                array_push(self::$_menuCurrentId, $item['id'], $v['id'], $vc['id']);
                            }
                        }
                    }
                }
            }
        }

    }
}