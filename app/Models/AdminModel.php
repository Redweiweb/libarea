<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class AdminModel extends \MainModel
{
    // Страница участников
    public static function UsersAll()
    {

        $result = XD::select('*')->from(['users'])->orderBy(['id'])->desc()->getSelect();
        //$query = $q->leftJoin(['users_logs'])->on(['id'], '=', ['logs_user_id'])
                 //->groupBy (['logs_user_id'])
                // ->orderBy(['logs_user_id'])->desc();

       // $result = $query->getSelect();  // toString()
     
        return $result;
    }

    // Страница участников
    public static function UsersLogAll($id)
    {
        return XD::select('*')->from(['users_logs'])->where(['logs_user_id'], '=', $id)
                ->orderBy(['id'])->desc()->getSelectOne();

    }
    
    // Получение информации по id
    public static function UserId($uid)
    {
        return XD::select('*')
                ->from(['users'])
                ->where(['id'], '=', $uid)->getSelectOne();
    }

    // Проверка IP на дубликаты
    public static function replayIp($ip)
    {
        if(!$ip) { return 0; }

        $query = XD::select('*')
                ->from(['users'])
                ->where(['reg_ip'], '=', $ip);

        return count($query->getSelect());
    }
    
    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($uid)
    {
        $result = XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)
                ->and(['banlist_status'], '=', 1)->getSelectOne();

        if(!$result) {
            return false;    
        }

        return true;   
    }
    
    
    public static function setBanUser($uid)
    {
        $res = XD::select('*')->from(['users_banlist'])
                ->where(['banlist_user_id'], '=', $uid)->getSelect();
                
        $num = count($res);
     
        if($num != 0) { 
        
            $result = Array();
            foreach($res as $row){
                $status = $row['banlist_status'];
            }  

            if($status == 0) {   
            	// Забанить повторно
                // Проставляем в banlist_int_num 2, что пока означет: возможно > 2
                XD::update(['users_banlist'])->set(['banlist_int_num'], '=', 2, ',', ['banlist_status'], '=', 1)->where(['banlist_user_id'], '=', $uid)->run(); 
                XD::update(['users'])->set(['ban_list'], '=', 1)->where(['id'], '=', $uid)->run();                 
            } else {  
                // Разбанить
                XD::update(['users_banlist'])->set(['banlist_status'], '=', 0)->where(['banlist_user_id'], '=', $uid)->run(); 
                XD::update(['users'])->set(['ban_list'], '=', 0)->where(['id'], '=', $uid)->run();                 
            }
            
        } else {  
          
            // Занесем ip регистрации    
            $user = XD::select('*')->from(['users'])->where(['id'], '=', $uid)->getSelectOne();
            $ip   = $user['reg_ip'];
            
            // Забанить в первый раз
            $date = date("Y-m-d H:i:s");

            XD::insertInto(['users_banlist'], '(', ['banlist_user_id'], ',', ['banlist_ip'], ',', ['banlist_bandate'], ',', ['banlist_int_num'], ',', ['banlist_int_period'], ',', ['banlist_status'], ',', ['banlist_autodelete'], ',', ['banlist_cause'], ')')->values( '(', XD::setList([$uid, $ip, $date, 1, '', 1, 0, '']), ')' )->run();
            
            XD::update(['users'])->set(['ban_list'], '=', 1)->where(['id'], '=', $uid)->run();  
            
        }
        
        return true;   
    }
    
    // Удаленные комментарии
    public static function getCommentsDell() 
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])
                ->where(['comment_del'], '=', 1)->orderBy(['comment_id'])->desc();
        
        return  $query->getSelect();
    }
    
    // Восстановление комментария
    public static function CommentsRecover($id)
    {
         XD::update(['comments'])->set(['comment_del'], '=', 0)
        ->where(['comment_id'], '=', $id)->run();
 
        return true;
    }
    
}