<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\ConsoleModel;
use SendEmail, Html;

class Console
{
    public static function index()
    {
        $choice  = Request::getPost('type');
        $allowed = ['css', 'topic', 'up', 'tl', 'indexer'];
        if (!in_array($choice, $allowed)) {
            redirect(url('admin.tools'));
        }
        self::$choice();
    }

    public static function topic()
    {
        ConsoleModel::recalculateTopic();

        self::consoleRedirect();
    }

    public static function up()
    {
        $users = ConsoleModel::allUsers();
        foreach ($users as $row) {
            $row['count']   =  ConsoleModel::allUp($row['id']);
            ConsoleModel::setAllUp($row['id'], $row['count']);
        }

        self::consoleRedirect();
    }

    // Если пользователь имеет нулевой уровень доверия (tl) но ему UP >=3, то повышаем до 1
    // If the user has a zero level of trust (tl) but he has UP >=3, then we raise it to 1
    public static function tl()
    {
        $users = ConsoleModel::getTrustLevel(0);
        foreach ($users as $row) {
            if ($row['up_count'] > 2) {
                ConsoleModel::setTrustLevel($row['id'], 1);
            }
        }

        self::consoleRedirect();
    }

    public static function testMail()
    {
        $email  = Request::getPost('mail');
        SendEmail::mailText(1, 'admin.test', ['email' => $email]);

        Html::addMsg(__('admin.completed'), 'success');

        redirect(url('admin.tools'));
    }

    public static function css()
    {
        (new \Modules\Admin\App\Sass)->collect();

        self::consoleRedirect();
    }

    public static function consoleRedirect()
    {
        if (PHP_SAPI != 'cli') {
            Html::addMsg(__('admin.completed'), 'success');
        }
        return true;
    }
}
