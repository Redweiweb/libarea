<main class="box w-100">
  <div class="pl20">
    <h1><?= __('app.password_recovery'); ?></h1>
    <form class="form max-w300" id="recover" method="post">
      <?php csrf_field(); ?>

      <fieldset>
        <label for="post_title"><?= __('app.email'); ?></label>
        <input type="email" required="" name="email">
      </fieldset>

      <?= component('captcha'); ?>

      <fieldset>
        <?= Html::sumbit(__('app.reset')); ?>
        <?php if (config('general.invite') == false) : ?>
          <span class="mr5 ml15 text-sm"><a href="<?= url('register'); ?>"><?= __('app.registration'); ?></a></span>
        <?php endif; ?>
        <span class="mr5 ml15 text-sm"><a href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a></span>
      </fieldset>
    </form>
    <p><?= __('app.agree_rules'); ?>.</p>
    <p><?= __('help.recover_info'); ?></p>
  </div>
</main>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('recover.send'),
    'redirect'  => url('login'),
    'success'   => __('msg.new_password_email'),
    'id'        => 'form#recover'
  ]
); ?>