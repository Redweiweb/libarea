<main class="box w-100">
  <div class="pl20">
    <h1><?= __('app.' . $data['sheet']); ?></h1>
    
    <div class="p15 bg-violet max-w300 mb-none right">
       <?= __('auth.mail_confirm'); ?>
    </div>
    
    <form class="max-w300" id="registration">
      <?php csrf_field(); ?>
      <?= component('registration'); ?>
    </form>

    <p><?= __('app.agree_rules'); ?>.</p>
    <p><?= __('help.security_info'); ?></p>
  </div>
</main>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('register.add'),
    'redirect'  => url('login'),
    'success'   => __('msg.check_your_email'),
    'id'        => 'form#registration'
  ]
); ?>