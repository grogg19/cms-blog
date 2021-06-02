<?php
/**
 * Subscribe Partial
 */

/**
 * @var $token;
 */
?>
<div class="dx-widget dx-box dx-box-decorated mb-40">
    <div class="dx-widget-title">
        Подписаться на рассылку
    </div>
    <div class="dx-widget-subscribe">
        <div class="dx-widget-text">
            <p>Подпишитесь, чтобы получать уведомления о выходе новых статей.</p>
        </div>
        <form class="dx-form dx-form-group-inputs" id="subscribe_form" name="subscribe_form" action="/subscribe">
	        <input type="hidden" name="_token" value="<?= $token ?>" />
            <input type="email" name="emailSubscribe" value="" id="emailSubscribe" aria-describedby="emailHelp" class="form-control form-control-style-2 div-emailSubscribe" placeholder="Ваш Email адрес">
            <button class="dx-btn dx-btn-lg dx-btn-icon" type="submit" data-form="subscribe_form"><span class="icon fas fa-paper-plane"></span></button>
        </form>
    </div>
</div>
