<?php $n = 0;
foreach ($answer as  $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>
  <?php if ($comment['comment_is_deleted'] == 1) : ?>
    <?php if (Access::author('comment', $comment['comment_user_id'], $comment['date'], 30) === true) : ?>
      <ol class="bg-red-200 text-sm list-none max-w780">
        <li class="pr5" id="comment_<?= $comment['comment_id']; ?>">
          <span class="comm-deletes gray">
            <?= Content::text($comment['content'], 'text'); ?>
            — <?= $comment['login']; ?>
            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right text-sm">
              <?= __('app.recover'); ?>
            </a>
          </span>
        </li>
      </ol>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($comment['comment_is_deleted'] == 0) : ?>
    <ol class="p0 m0 mb15 list-none">
      <li class="content_tree" id="comment_<?= $comment['comment_id']; ?>">
        <div class="max-w780">
          <div class="text-sm flex">
            <a class="gray-600" href="<?= url('profile', ['login' => $comment['login']]); ?>">
              <?= Html::image($comment['avatar'], $comment['login'], 'img-sm', 'avatar', 'small'); ?>
              <span class="mr5 ml5">
                <?= $comment['login']; ?>
              </span>
            </a>
            <?php if ($comment['post_user_id'] == $comment['comment_user_id']) : ?>
              <span class="sky mr5"><i class="bi-mic text-sm"></i></span>
            <?php endif; ?>
            <span class="mr5 ml5 gray-600 lowercase">
              <?= Html::langDate($comment['date']); ?>
            </span>
            <?= insert('/_block/show-ip', ['ip' => $comment['comment_ip'], 'publ' => $comment['comment_published']]); ?>
          </div>
          <a href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
            <?= $comment['post_title']; ?>
          </a>
          <div class="content-body">
            <?= Content::text($comment['content'], 'text'); ?>
          </div>
        </div>
        <div class="text-sm flex">
          <?= Html::votes($comment, 'comment', 'ps', 'bi-heart mr5'); ?>

          <?php if (Access::author('comment', $comment['comment_user_id'], $comment['date'], 30) === true) : ?>
            <a data-post_id="<?= $comment['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray-600 mr10 ml10">
              <?= __('app.edit'); ?>
            </a>
            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray-600 mr5 ml5">
              <?= __('app.remove'); ?>
            </a>
          <?php endif; ?>

          <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
            <a data-post_id="<?= $comment['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray-600 ml15">
              <i title="<?= __('app.report'); ?>" class="bi-flag"></i>
            </a>
          <?php endif; ?>
        </div>
        <div data-insert="<?= $comment['comment_id']; ?>" id="insert_id_<?= $comment['comment_id']; ?>" class="none"></div>
      </li>
    </ol>
  <?php endif; ?>
<?php endforeach; ?>