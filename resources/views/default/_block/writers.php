<?php if ($data['writers']) : ?>
  <div class="box">
    <?php foreach ($data['writers'] as  $writer) : ?>
      <div class="flex br-bottom">
        <div class="mr15 mt10">
          <?= $writer['sum']; ?>
          <span class="block gray lowercase"><?= __('app.views'); ?></span>
        </div>
        <div class="p15">
          <?= Html::image($writer['avatar'], $writer['login'], 'w50 h50', 'avatar', 'small'); ?>
        </div>
        <div class="mt10">
          <a href="<?= url('profile', ['login' => $writer['login']]); ?>"><?= $writer['login']; ?></a>
          <div class="mr13 gray-600 mr15">
            <?php if ($writer['about']) : ?>
              <?= $writer['about']; ?>
            <?php else : ?>
              ...
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no'), 'icon' => 'bi-info-lg']); ?>
<?php endif; ?>