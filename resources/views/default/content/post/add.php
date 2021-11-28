<main class="col-span-12 mb-col-12 bg-white pt5 pr15 pb5 pl15 create">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    null,
    null,
    Translate::get('add post')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('post.create'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('heading'),
            'type'  => 'text',
            'name'  => 'post_title',
            'value' => null,
            'min'   => 6,
            'max'   => 250,
            'id'    => 'title',
            'help'  => '6 - 250 ' . Translate::get('characters'),
            'red'   => 'red'
          ]
        ],
      ]); ?>

      <?php if (!empty($data['user_blog'])) { ?>
        <?= includeTemplate('/_block/form/select', [
          'uid'         => $uid,
          'data'        => $data['facets'],
          'type'        => 'blog',
          'maximum'     => 1,
          'action'      => 'add',
          'title'       => Translate::get('blogs'),
          'required'    => false,
          'help'        => '...',
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/select', [
        'uid'           => $uid,
        'data'          => $data['facets'],
        'type'          => 'topic',
        'maximum'       => 3,
        'action'        => 'add',
        'title'         => Translate::get('facets'),
        'required'      => true,
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_url')) { ?>
        <div class="mb20 max-w640">
          <label class="block" for="post_title">URL</label>
          <input id="link" class="w-100 h30" type="text" name="post_url" />
          <input id="graburl" readonly="readonly" class="right center mt15 mb15" type="submit_url" name="submit_url" value="<?= Translate::get('to extract'); ?>" />
        </div>
      <?php } ?>

      <div class="mb20 post">
        <div class="input-images"></div>
        <div class="size-14 gray-light-2"><?= Translate::get('format-cover-post'); ?>.</div>
      </div>

      <?= includeTemplate('/_block/editor/editor', [
        'type'      => 'post',
        'height'    => '350px',
        'preview'   => 'vertical',
        'lang'      => $uid['user_lang'],
      ]); ?>

      <?= includeTemplate('/_block/form/field-radio',  [
        'data' => [
          [
            'title' => Translate::get('is this a draft?'),
            'name' => 'post_draft',
            'checked' => 0
          ]
        ],
      ]); ?>

      <?php if ($uid['user_trust_level'] > 0) { ?>
        <?= includeTemplate('/_block/form/select-content-tl', [
          'uid' => $uid,
          'data' => null
        ]); ?>

        <?= includeTemplate('/_block/form/field-radio', [
          'data' => [
            [
              'title' => Translate::get('format Q&A?'),
              'name' => 'post_type',
              'checked' => 0
            ],
            [
              'title' => Translate::get('to close?'),
              'name' => 'closed',
              'checked' => 0
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/field-radio',  [
        'data' => [
          [
            'title' => Translate::get('is this a translation?'),
            'name' => 'translation',
            'checked' => 0
          ],
        ]
      ]); ?>

      <?php if ($uid['user_trust_level'] > 2) { ?>
        <?= includeTemplate('/_block/form/field-radio', [
          'data' => [
            [
              'title' => Translate::get('raise?'),
              'name' => 'top',
              'checked' => 0
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/select', [
        'uid'           => $uid,
        'data'          => [],
        'action'        => 'add',
        'type'          => 'post',
        'title'         => Translate::get('related'),
        'required'      => false,
        'maximum'       => 3,
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <?= sumbit(Translate::get('create')); ?>
    </form>
  </div>
</main>