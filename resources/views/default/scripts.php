<a class="up_down_btn none mb-none" title="<?= __('app.up'); ?>">&uarr;</a>

<script src="/assets/js/common.js"></script>
<script src="/assets/js/notiflix/notiflix-aio-3.2.5.min.js"></script>
<?php if (UserData::checkActiveUser()) : ?><script src="/assets/js/app.js"></script><?php endif; ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!UserData::checkActiveUser()) : ?>
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
        Notiflix.Report.info(
          '<?= __('app.need_login'); ?>',
          '<?= __('app.login_info'); ?>',
          '<?= __('app.well'); ?>',
        );
      }));
  <?php endif; ?>

  <?= Html::getMsg(); ?>

  <?php if (UserData::getUserScroll()) : ?>
    // Что будет смотреть
    const coolDiv = document.getElementById("scroll");

    // Куда будем подгружать
    const scroll = document.getElementById("scrollArea");

    // Начальная загрузка (первая страница загружается статически)
    let postPage = 2;

    function getPosts(path) {
      fetch(path)
        .then(res => res.text()).then((res) => {
          scroll.insertAdjacentHTML("beforeend", res);
          postPage += 1;
        })
        .catch((e) => alert(e));
    }

    const observer = new IntersectionObserver(entries => {
      const firstEntry = entries[0];
      if (firstEntry.isIntersecting) {
        if (`${postPage}` > 25) return;
        getPosts(`/post/scroll/${postPage}.html`);
      }
    });
    if (coolDiv) {
      observer.observe(coolDiv);
    }
  <?php endif; ?>
</script>

</body>

</html>