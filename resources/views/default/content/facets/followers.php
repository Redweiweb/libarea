<?php foreach ($users as $user) : ?>
  <a class="flex relative p5 items-center hidden gray-600" href="<?= url('profile', ['login' => $user['login']]); ?>">
    <?= Html::image($user['avatar'], $user['login'], 'img-sm', 'avatar', 'max'); ?>
    <?= $user['login']; ?>
  </a>
<?php endforeach; ?>