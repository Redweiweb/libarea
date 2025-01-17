<?php if ($data['user']['id'] != 1) : ?>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.created_by'); ?></h3>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= url('profile', ['login' => $data['user']['login']]); ?>">
      <?= Html::image($data['user']['avatar'], $data['user']['login'], 'img-base', 'avatar', 'max'); ?>
      <?= $data['user']['login']; ?>
    </a>
  </div>
<?php endif; ?>

<?php if (!empty($data['high_topics'])) : ?>
  <?= insert('/_block/sidebar/topic_block', ['data' => $data['high_topics'], 'lang' => 'upper']); ?>
<?php endif; ?>

<?php if (!empty($data['low_topics'])) : ?>
  <?= insert('/_block/sidebar/topic_block', ['data' => $data['low_topics'], 'lang' => 'subtopics']); ?>
<?php endif; ?>

<?php if (!empty($data['low_matching'])) : ?>
  <?= insert('/_block/sidebar/topic_block', ['data' => $data['low_matching'], 'lang' => 'related']); ?>
<?php endif; ?>