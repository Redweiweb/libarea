<main>
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form method="POST" action="<?= url('setting.change', ['type' => 'avatar']); ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="file-upload mb10" id="file-drag">
        <div class="flex">
          <?= Html::image($data['user']['avatar'], $data['user']['login'], 'img-xl', 'avatar', 'max'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="img-xl">
          <div id="start" class="mt15">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <div class="clear gray mb10">
        <div class="mb5 text-sm"><?= __('app.recommended_size'); ?>: 240x240px (jpg, jpeg, png)</div>
        <?= Html::sumbit(__('app.download')); ?>
      </div>

      <div class="file-upload mt20 mb10" id="file-drag">
        <div class="flex">
          <?php if ($data['user']['cover_art'] != 'cover_art.jpeg') : ?>
            <div class="relative mr15">
              <img class="block br-gray max-w-100" src="<?= Html::coverUrl($data['user']['cover_art'], 'user'); ?>">
              <a class="right text-sm" href="<?= url('setting', ['type' => 'cover_remove']); ?>">
                <?= __('app.remove'); ?>
              </a>
            </div>
          <?php else : ?>
            <div class="block br-gray max-w-100 text-sm gray p20 mr15">
              <?= __('app.no_cover'); ?>...
            </div>
          <?php endif; ?>
          <div id="start">
            <img id="file-image bi-cloud-download" src="/assets/images/1px.jpg" alt="" class="h94">

            <input id="file-upload" type="file" name="cover" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <div class="clear gray mb10">
        <div class="mb5 text-sm"><?= __('app.recommended_size'); ?>: 1920x240px (jpg, jpeg, png)</div>
        <?= Html::sumbit(__('app.download')); ?>
      </div>
    </form>
  </div>
</main>
<aside>
  <div class="box text-sm">
    <?= __('help.avatar_info'); ?>
  </div>
</aside>