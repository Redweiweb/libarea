<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\FacetModel;
use App\Models\Admin\{UserModel, StatsModel};
use Translate, Tpl;

class HomeController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        return Tpl::agRender(
            '/admin/index',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
                'data'  => [
                    'count'             => StatsModel::getCount(),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'last_visit'        => UserModel::getLastVisit(),
                    'bytes'             => $bytes,
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }

    public function css()
    {
        Request::getResources()->addBottomStyles('/assets/css/color-help.css');

        $bg_file = HLEB_GLOBAL_DIRECTORY . '/public/assets/css/color-help.css';
        $bg_array = file_get_contents($bg_file);
        preg_match_all('/\.([\w\d\.-]+)[^{}]*{[^}]*}/', $bg_array, $matches);

        return Tpl::agRender(
            '/admin/css',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
                'data'  => [
                    'type'  => 'Css',
                    'sheet' => 'Css',
                    'bg'    => $matches[1],
                ]
            ]
        );
    }
}
