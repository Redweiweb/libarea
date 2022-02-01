<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="col-span-2 justify-between mb-none">
  <nav class="sticky top-sm">

    <ul class="metismenu p0 m0 list-none text-sm" id="menu">
      <?php foreach (Modules\Admin\App\Navigation::menu() as $cats) {
        $active = '';
        if ($data['type'] == $cats['name']) $active = ' sky-500';
      ?>

        <?php if ($cats['radical']  == 1) { ?>
          <li> <a class="<?= $active; ?>" href="<?= getUrlByName($cats['url']); ?>">
              <i class="<?= $cats['icon']; ?> middle mr10 text-xl"></i>
              <span><?= Translate::get($cats['name']); ?></span>
            </a></li>
        <?php } else { ?>

          <?php if ($cats['parent'] == 0) { ?></li>
            <li><?php } ?>

            <a aria-expanded="true" class="has-arrow<?= $active; ?>" href="#">
              <i class="bi bi-list middle mr10 text-xl"></i>
              <span><?= Translate::get($cats['name']); ?></span>
            </a>

            <?php if ($cats['childs'] > 0) { ?>
              <ul>
                <?php foreach ($cats['childs'] as $cat) { ?>
                  <a class="gray mb5 block<?= $active; ?>" href="<?= getUrlByName($cat['url']); ?>">
                    <i class="bi bi-circle green-600 middle mr5"></i>
                    <span><?= Translate::get($cat['name']); ?></span>
                  </a>
                <?php } ?>
              </ul>
            <?php } ?>

          <?php } ?>

        <?php } ?>
    </ul>

  </nav>
</div>

<main class="col-span-10 mb-col-12">
  <?php if ($data['type'] != 'admin') { ?>
    <div class="bg-white flex flex-row items-center justify-between br-box-gray p15 mb15">
      <p class="m0">
        <a href="<?= getUrlByName('admin'); ?>"><?= Translate::get('admin'); ?></a> /
        <span class="red-500"><?= Translate::get($data['type']); ?></span>
      </p>
      <ul class="flex flex-row list-none m0 p0 center">
        <?php foreach ($menus as $menu) { ?>
          <a class="ml30 mb-mr-5 mb-ml-10 gray<?php if ($menu['id'] == $data['sheet']) { ?> sky-500<?php } ?>" href="<?= $menu['url']; ?>" <?php if ($menu['id'] == $data['sheet']) { ?> aria-current="page" <?php } ?>>
            <i class="<?= $menu['icon']; ?> mr5"></i>
            <span><?= $menu['name']; ?></span>
          </a>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
  <link rel="stylesheet" href="/assets/js/metismenujs/metismenujs.min.css">
  <script src="/assets/js/metismenujs/metismenujs.min.js"></script>
  <script nonce="<?= $_SERVER['nonce']; ?>">
    document.addEventListener("DOMContentLoaded", function(event) {
      new MetisMenu('#menu');
    });
  </script>