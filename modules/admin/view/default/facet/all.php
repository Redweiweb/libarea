<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box bg-white">
  <?php foreach ($data['types_facets'] as $type) : ?>
    <a class="block mb10" href="<?= url('admin.facets.type', ['type' => $type['type_code']]); ?>">
      <i class="bi-circle green middle mr5"></i>
      <?= __('admin.' . $type['type_lang']); ?>
      <sup class="gray-600"><?= $data['count']['count_' . $type['type_code']]; ?></sup>
    </a>
  <?php endforeach; ?>
</div>
</main>

<?= includeTemplate('/view/default/footer'); ?>