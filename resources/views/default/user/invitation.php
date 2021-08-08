<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>

                <?php if ($uid['trust_level'] > 1) { ?>

                    <form method="post" action="/invitation/create">
                        <?php csrf_field(); ?>

                        <div class="boxline">
                            <input id="link" class="form-input" type="email" name="email">
                            <input class="button right" type="submit" name="submit" value="<?= lang('To create'); ?>">
                            <div class="box_h"><?= lang('Enter'); ?> e-mail</div>
                        </div>
                        <?= lang('Invitations left'); ?> <?= 5 - $data['count_invites']; ?>

                    </form>

                    <h3><?= lang('Invited guests'); ?></h3>

                    <?php if (!empty($data['invitations'])) { ?>

                        <?php foreach ($data['invitations'] as $invite) { ?>
                            <?php if ($invite['active_status'] == 1) { ?>
                                <div class="size-13 gray">
                                    <?= user_avatar_img($invite['avatar'], 'small', $invite['login'], 'ava'); ?>
                                    <a href="<?= $invite['login']; ?>"><?= $invite['login']; ?></a>
                                    - <?= lang('registered'); ?>
                                </div>

                                <?php if ($uid['trust_level'] == 5) { ?>
                                    <?= lang('The link was used to'); ?>: <?= $invite['invitation_email']; ?> <br>
                                    <code>
                                        <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/<?= $invite['invitation_code']; ?>
                                    </code>
                                <?php } ?>

                                <span class="size-13 gray"><?= lang('Link has been used'); ?></span>
                            <?php } else { ?>

                                <?= lang('For'); ?> (<?= $invite['invitation_email']; ?>) <?= lang('can send this link'); ?>: <br>

                                <code>
                                    <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/<?= $invite['invitation_code']; ?>
                                </code>

                            <?php } ?>

                            <br><br>
                        <?php } ?>

                    <?php } else { ?>
                        <?= lang('No invitations'); ?>
                    <?php } ?>

                <?php } else { ?>
                    <?= lang('limit_tl_invitation'); ?>.
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>