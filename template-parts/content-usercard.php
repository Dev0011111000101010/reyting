<?php
$linked_user = (int) get_post_meta( get_the_ID(), '_linked_user', true );
$user        = get_user_by( 'id', $linked_user ); ?>
<div class="list-usercard">
    <div class="usercard-fio"><a href="#">Иванов Иван Иванович</a></div>
    <div class="usercard-maincat">Кримінальне право</div>
    <div class="usercard-mainprofy">Стаття 185. Крадіжка. Кража</div>
    <div class="usercard-licenses">✅Адвокат</div>
    <div class="usercard-acquittal">0</div>
</div>

<!--<div class="list-usercard-title">-->
<!--    <a href="--><?//= get_author_posts_url($linked_user) ?><!--">--><?//= $user->display_name; ?><!--</a>-->
<!--</div>-->