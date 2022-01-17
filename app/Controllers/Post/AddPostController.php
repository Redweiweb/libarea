<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{SubscriptionModel, ActionModel, WebModel, PostModel, FacetModel};
use Content, UploadImage, Integration, Validation, Slug, URLScraper, Config, Translate, Domains, Tpl;

class AddPostController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма добавление поста
    public function index($type_content)
    {
        if ($type_content == 'post') {
            Request::getResources()->addBottomScript('/assets/js/uploads.js');
        } else {
            if (UserData::checkAdmin()) {
                $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
                if (!$count) redirect('/');
            }
        }

        // https://phphleb.ru/ru/v1/examples/#exampleE10
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
        Request::getResources()->addBottomStyles('/assets/js/editor/toastui-editor.min.css');
        Request::getResources()->addBottomStyles('/assets/js/editor/dark.css');
        Request::getResources()->addBottomScript('/assets/js/editor/toastui-editor-all.min.js');

        // Adding from page topic 
        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        $topic      = FacetModel::getFacet($topic_id, 'id');

        $facets = ['topic' => $topic];
        if ($topic) {
            if ($topic['facet_type'] == 'blog') {
                $facets  = ['blog' => $topic];
                if ($topic['facet_user_id'] != $this->user['id']) redirect('/');
            }
        }

        $puth = $type_content == 'page' ? '/page/add' : '/post/add';

        return Tpl::agRender(
            $puth,
            [
                'meta'      => meta($m = [], Translate::get('add post')),
                'data'  => [
                    'facets'     => $facets,
                    'blog'  => FacetModel::getFacetsUser($this->user['id'], 'blog'),
                    'post_arr'   => PostModel::postRelatedAll(),
                    'type'       => 'add',
                ]
            ]
        );
    }

    // Add post 
    // Добавим пост
    public function create()
    {
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['content']; // для Markdown
        $post_url               = Request::getPost('post_url');
        $post_closed            = Request::getPostInt('closed');
        $post_draft             = Request::getPostInt('post_draft');
        $post_top               = Request::getPostInt('top');
        $post_feature           = Request::getPostInt('post_feature');
        $post_translation       = Request::getPostInt('translation');
        $post_merged_id         = Request::getPostInt('post_merged_id');
        $post_tl                = Request::getPostInt('post_tl');
        $blog_id                = Request::getPostInt('blog_id');

        $post_fields    = Request::getPost() ?? [];

        // Related posts 
        // Связанные посты
        $json_post  = $post_fields['post_select'] ?? [];
        $arr_post   = json_decode($json_post[0], true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);

        // Темы для поста
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        // Используем для возврата
        $redirect = getUrlByName('post.add');
        if ($blog_id > 0) {
            $redirect = getUrlByName('post.add') . '/' . $blog_id;
        }

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($post_content, 'post');

        // Если нет темы
        if (!$topics) {
            addMsg(Translate::get('select topic') . '!', 'error');
            redirect($redirect);
        }

        Validation::Length($post_title, Translate::get('title'), '6', '250', $redirect);
        Validation::Length($post_content, Translate::get('the post'), '6', '25000', $redirect);

        if ($post_url) {
            $site = $this->addUrl($post_url, $post_title);
        }

        // Обложка поста
        $cover  = $_FILES['images'];
        if ($_FILES['images']['name']) {
            $post_img = UploadImage::cover_post($cover, 0, $redirect, $this->user['id']);
        }

        // Получаем SEO поста
        $slug       = new Slug();
        $uri        = $slug->create($post_title);
        $post_slug  = substr($uri, 0, 90);

        $result     = PostModel::getSlug($post_slug);
        if ($result) {
            $post_slug = $post_slug . "-";
        }

        $last_id = PostModel::AddPost(
            [
                'post_title'            => $post_title,
                'post_content'          => Content::change($post_content),
                'post_content_img'      => $post_img ?? '',
                'post_thumb_img'        => $site['og_img'] ?? '',
                'post_related'          => $post_related,
                'post_merged_id'        => $post_merged_id,
                'post_tl'               => $post_tl ?? 0,
                'post_slug'             => $post_slug,
                'post_feature'          => $post_feature,
                'post_type'             => 'post',
                'post_translation'      => $post_translation,
                'post_draft'            => $post_draft,
                'post_ip'               => Request::getRemoteAddress(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->user['id'],
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
                'post_closed'           => $post_closed,
                'post_top'              => $post_top,
            ]
        );

        $url = getUrlByName('post', ['id' => $last_id, 'slug' => $post_slug]);

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('post', $last_id, $url);
        }

        // Получим id блога с формы выбора
        $blog_post  = $post_fields['blog_select'] ?? false;
        if ($blog_post) {
            $blog   = json_decode($blog_post, true);
            $topics = array_merge($blog, $topics);
        }

        // Запишем темы и блог
        $arr = [];
        foreach ($topics as $ket => $row) {
            $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $last_id);

        // Notification (@login). 10 - mentions in post 
        if ($message = Content::parseUser($post_content, true, true)) {
            (new \App\Controllers\NotificationsController())->mention(10, $message, $last_id, $url);
        }

        if (Config::get('general.discord')) {
            if ($post_tl == 0 && $post_draft == 0) {
                Integration::AddWebhook($post_content, $post_title, $url);
            }
        }

        SubscriptionModel::focus($last_id, $this->user['id'], 'post');

        ActionModel::addLogs(
            [
                'log_user_id'       => $this->user['id'],
                'log_user_login'    => $this->user['login'],
                'log_id_content'    => $last_id,
                'log_type_content'  => 'post',
                'log_action_name'   => 'content.added',
                'log_url_content'   => $url,
                'log_date'          => date("Y-m-d H:i:s"),
            ]
        );

        redirect($url);
    }

    // Добавим страницу
    public function createPage()
    {
        // Получаем title и содержание
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['content']; // для Markdown
        $post_url               = Request::getPost('post_url');
        $blog_id                = Request::getPostInt('blog_id');

        // Получим id Блога с формы выбора или Раздел с фасета
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['section_select'] ?? [];
        if ($facet_post) {
            $topics = json_decode($facet_post, true);
        }

        $blog_post  = $post_fields['blog_select'] ?? false;
        if ($blog_post) {
            $blog   = json_decode($blog_post, true);
            $topics = array_merge($blog, $topics ?? []);
        }

        // Используем для возврата
        $redirect = getUrlByName('page.add');
        if ($blog_id > 0) {
            $redirect = getUrlByName('page.add') . '/' . $blog_id;
        }

        if ($this->user['trust_level'] < UserData::REGISTERED_ADMIN) {
            $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
            if (!$count) redirect('/');
        }

        // Если нет темы
        if (!$topics) {
            addMsg(Translate::get('select topic') . '!', 'error');
            redirect($redirect);
        }

        Validation::Length($post_title, Translate::get('title'), '6', '250', $redirect);
        Validation::Length($post_content, Translate::get('the post'), '6', '25000', $redirect);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($post_content, 'page');

        // Получаем SEO поста
        $slug       = new Slug();
        $uri        = $slug->create($post_title);
        $post_slug  = substr($uri, 0, 90);

        $data = [
            'post_title'            => $post_title,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => '',
            'post_thumb_img'        => '',
            'post_related'          => '',
            'post_merged_id'        => 0,
            'post_tl'               => 0,
            'post_slug'             => $post_slug,
            'post_feature'          => 0,
            'post_type'             => 'page',
            'post_translation'      => 0,
            'post_draft'            => 0,
            'post_ip'               => Request::getRemoteAddress(),
            'post_published'        => ($trigger === false) ? 0 : 1,
            'post_user_id'          => $this->user['id'],
            'post_url'              => '',
            'post_url_domain'       => '',
            'post_closed'           => 0,
            'post_top'              => 0,
        ];

        $last_post_id   = PostModel::AddPost($data);
        $facet = FacetModel::getFacet($topics[0]['id'], 'id');
        $url_post       = getUrlByName('page', ['facet' => $facet['facet_slug'], 'slug' => $post_slug]);

        // Запишем темы и блог
        $arr = [];
        foreach ($topics as $ket => $row) {
            $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $last_post_id);

        redirect($url_post);
    }

    public function addUrl($post_url, $post_title)
    {
        // Поскольку это для поста, то получим превью и разбор домена...
        $og_img             = self::grabOgImg($post_url);
        $parse              = parse_url($post_url);
        $url_domain         = $parse['host'];
        $domain             = new Domains($url_domain);
        $post_url_domain    = $domain->getRegisterable();
        $item_url           = $parse['scheme'] . '://' . $parse['host'];

        // Если домена нет, то добавим его
        $item = WebModel::getItemOne($post_url_domain, $this->user['id']);
        if (!$item) {
            // Запишем минимальные данный
            WebModel::add(
                [
                    'item_url'          => $item_url,
                    'item_url_domain'   => $post_url_domain,
                    'item_title_url'    => $post_title,
                    'item_content_url'  => Translate::get('description is formed'),
                    'item_published'    => 0,
                    'item_user_id'      => $this->user['id'],
                    'item_type_url'     => 0,
                    'item_status_url'   => 200,
                ]
            );
        } else {
            WebModel::addItemCount($post_url_domain);
        }

        $site = [
            'og_img' => $og_img,
            'post_url_domain' => $post_url_domain,
        ];

        return $site;
    }

    // Парсинг
    public function grabMeta()
    {
        $url    = Request::getPost('uri');
        $meta   = new URLScraper($url);

        $meta->parse();
        $metaData = $meta->finalize();

        return json_encode($metaData);
    }

    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url)
    {
        $meta = new URLScraper($post_url);
        $meta->parse();
        $metaData = $meta->finalize();

        return UploadImage::thumb_post($metaData->image);
    }

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $info       = Request::getPost('info');
        $status     = preg_split('/(@)/', $info);
        $type_id    = (int)$status[0]; // id конткнта
        $type       = $status[1];      // тип контента

        $allowed = ['post', 'comment', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($type_id, $type);
        if (!accessСheck($info_type, $type, $this->user, 1, 30)) {
            redirect('/');
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $status = 'deleted-' . $type;
        if ($info_type[$type . '_is_deleted'] == 1) {
            $status = 'restored-' . $type;
        }

        $info_post_id = $info_type[$type . '_post_id'];
        if ($type == 'post') {
            $info_post_id = $info_type[$type . '_id'];
        }

        $data = [
            'user_id'       => $this->user['id'],
            'user_tl'       => $this->user['trust_level'],
            'created_at'    => date("Y-m-d H:i:s"),
            'post_id'       => $info_post_id,
            'content_id'    => $info_type[$type . '_id'],
            'action'        => $status,
            'reason'        => '',
        ];

        // TODO: It will be replaced with a shared user log
        // ActionModel::logsAdd($data);

        return true;
    }

    // Рекомендовать пост
    public function recommend()
    {
        $post_id = Request::getPostInt('post_id');

        // Проверка доступа 
        Validation::validTl($this->user['trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        $post = PostModel::getPost($post_id, 'id', $this->user);
        pageError404($post);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }
}
