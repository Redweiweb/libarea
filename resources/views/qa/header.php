<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?09');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') : ?> class="dark" <?php endif; ?>>

  <header class="bg-white mt0 mb15">
    <div class="br-bottom mr-auto max-width w-100 pr10 pl15 mb10 mb-none items-center flex">
      <a class="mr20 black text-xs" href="/topics">
        <i class="bi-columns-gap mr5"></i> <?= __('app.topics'); ?>
      </a>
      <a class="mr20 black text-xs" href="/blogs">
        <i class="bi-journals mr5"></i> <?= __('app.blogs'); ?>
      </a>
      <a class="mr20 black text-xs" href="/users">
        <i class="bi-people mr5"></i> <?= __('app.users'); ?>
      </a>
      <a class="mr20 black text-xs" href="/web">
        <i class="bi-link-45deg mr5"></i> <?= __('app.catalog'); ?>
      </a>
      <a class="mr20 black text-xs" href="/search">
        <i class="bi-search mr5"></i> <?= __('app.search'); ?>
      </a>
    </div>

    <div class="wrap items-center flex justify-between">
      <div class="flex items-center" id="find">
        <div class="ml20 flex items-center">
          <a title="<?= __('app.home'); ?>" class="logo ml5" href="/">
            <?= config('meta.name'); ?>
          </a>
        </div>
      </div>

      <?php if (!UserData::checkActiveUser()) : ?>
        <div class="flex right items-center">
          <div id="toggledark" class="header-menu-item mb-none only-icon p10 ml30 mb-ml10">
            <i class="bi-brightness-high gray-600 text-xl"></i>
          </div>
          <?php if (config('general.invite') == false) : ?>
            <a class="w94 gray ml30 mr15 mb-ml10 mb-mr5 block" href="<?= url('register'); ?>">
              <?= __('app.registration'); ?>
            </a>
          <?php endif; ?>
          <a class="w94 btn btn-outline-primary ml20" href="<?= url('login'); ?>">
            <?= __('app.sign_in'); ?>
          </a>
        </div>
      <?php else : ?>
        <div>
          <div class="flex right ml30 mb-ml10 items-center">

            <?= Html::addPost($facet); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml10">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>

            <a class="gray-600 p10 text-xl ml20 mb-ml10" href="<?= url('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationController::setBell(UserData::getUserId()); ?>
              <?php if (!empty($notif)) : ?>
                <?php if ($notif['action_type'] == 1) : ?>
                  <i class="bi-envelope red"></i>
                <?php else : ?>
                  <i class="bi-bell-fill red"></i>
                <?php endif; ?>
              <?php else : ?>
                <i class="bi-bell"></i>
              <?php endif; ?>
            </a>

            <div class="ml45 mb-ml20 relative">
              <div class="trigger">
                <?= Html::image(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mb-pr0', 'avatar', 'small'); ?>
              </div>
              <ul class="dropdown">
                <?= insert('/_block/navigation/menu', ['type' => $type, 'list' => config('navigation/menu.user')]); ?>
              </ul>
            </div>

          </div>
        </div>
      <?php endif;  ?>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">