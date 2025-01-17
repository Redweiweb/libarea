<?= includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]);
$item = $data['item'];
?>
<div id="contentWrapper">
  <div class="mb-none center mt30 w110">
    <?= Html::votes($item, 'item', 'ps', 'bi-heart text-2xl middle', 'block'); ?>
    <div class="pt20">
      <?= Html::favorite($item['item_id'], 'website', $item['tid'], 'ps', 'text-2xl'); ?>
    </div>
  </div>
  <main>
    <div class="box hidden pr15 mb20">
      <h1><?= $item['item_title']; ?>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="text-sm ml5" title="<?= __('web.edit'); ?>" href="<?= url('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        <?php endif; ?>
      </h1>

      <div class="w-100">
        <div class="w-40 mt10 left mb-w-100">
          <?= Html::websiteImage($item['item_domain'], 'thumbs', $item['item_title'], 'preview w-100 box-shadow'); ?>
        </div>
        <div class="w-60 left pl20 mb-w-100 max-w780 mb-p10">
          <?= Content::text($item['item_content'], 'text'); ?>
          <div class="gray mt20 mb15">
            <a class="green" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_url']; ?>">
              <?= Html::websiteImage($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
              <?= $item['item_url']; ?>
            </a>
          </div>

          <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', 'all'); ?>

          <div class="right mr20">
            <?= Html::signed([
              'type'            => 'item',
              'id'              => $item['item_id'],
              'content_user_id' => false, // allow subscription and unsubscribe to the owner 
              'state'           => is_array($data['item_signed']),
            ]); ?>
          </div>
        </div>
      </div>
      <?php if ($item['item_is_soft'] == 1) : ?>
        <h2><?= __('web.soft'); ?></h2>
        <h3><?= $item['item_title_soft']; ?></h3>
        <div class="gray-600">
          <?= Content::text($item['item_content_soft'], 'text'); ?>
        </div>
        <p>
          <i class="bi-github mr5"></i>
          <a target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
            <a target="_blank" href="<?= $item['item_url']; ?>" class="item_cleek" data-id="<?= $item['item_id']; ?>" rel="nofollow noreferrer ugc">
              <?= $item['item_github_url']; ?>
            </a>
        </p>
      <?php endif; ?>

      <?php if ($data['related_posts']) : ?>
        <p>
          <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => true]); ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if ($item['item_close_replies'] == 0) : ?>
      <?php if (Access::trustLevels(config('trust-levels.tl_add_reply'))) : ?>
        <form class="max-w780" action="<?= url('content.create', ['type' => 'reply']); ?>" accept-charset="UTF-8" method="post">
          <?= csrf_field() ?>

          <?php insert('/_block/form/textarea', [
            'title'     => __('web.reply'),
            'type'      => 'text',
            'name'      => 'content',
            'min'       => 5,
            'max'       => 555,
            'help'      => '5 - 555 ' . __('web.characters'),
          ]); ?>

          <input type="hidden" name="item_id" value="<?= $item['item_id']; ?>">
          <?= Html::sumbit(__('web.reply')); ?>
        </form>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($data['tree']) : ?>
      <h2 class="mt10"><?= __('web.answers'); ?></h2>
      <ul class="list-none mt20">
        <?= includeTemplate('/view/default/replys', ['data' => $data]); ?>
      </ul>
    <?php else : ?>
      <?php if ($item['item_close_replies'] == 0) : ?>
        <div class="p20 center gray-600">
          <i class="bi-chat-dots block text-8xl"></i>
          <?= __('web.no_answers'); ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($item['item_close_replies'] == 1) : ?>
      <div class="mt20">
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.closed'), 'icon' => 'bi-door-closed']); ?>
      </div>
    <?php endif; ?>
  </main>
  <aside class="mr20">
    <div class="box box-shadow-all">
      <?php if ($data['similar']) : ?>
        <h3 class="uppercase-box"><?= __('web.recommended'); ?></h3>
        <?php foreach ($data['similar'] as $link) : ?>
          <?= Html::websiteImage($link['item_domain'], 'thumbs', $link['item_title'], 'mr5 w200 box-shadow'); ?>
          <a class="inline mr20 mb15 block text-sm" href="<?= url('website', ['slug' => $link['item_domain']]); ?>">
            <?= $link['item_title']; ?>
          </a>
        <?php endforeach; ?>
      <?php else : ?>
        ....
      <?php endif; ?>
    </div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer'); ?>