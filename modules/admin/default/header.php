<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <title><?= $meta['meta_title']; ?></title>
  <?php getRequestHead()->output(); ?>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
</head>

<body>

  <header class="fixed p0 width-100">
    <div class="header-left p15">
      IP: <?= Request::getRemoteAddress(); ?>
    </div>
    <div class="right p15">
      <a title="<?= lang('Home'); ?>" rel="noreferrer gray" href="/">
        <?= lang('To the website'); ?> <i class="icon-right-open-big"></i>
      </a>
    </div>
  </header>
  <?php includeTemplate('/_block/admin-menu', ['uid' => $uid, 'meta' => $meta]); ?>