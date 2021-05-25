<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package referendum
 */

?>
<footer class="text-center">
    <hr>
    <div class="container">
        <a href="<?= get_privacy_policy_url() ?>">Политика конфиденциальности</a> <br>
    </div>
</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
<div class="modal modal-previewfile" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Проверка доступности файла</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<div class="modal modal-warntelegram" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Для получения права на редактирование обратитесь в наш  <a href="https://t.me/joinchat/6Vtvs9dnOQo3MmF">
                    <svg fill="#0088cc" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/>
                    </svg>
                    <span>Telegram</span>
                </a>

            </div>
        </div>
    </div>
</div>
</body>
</html>
