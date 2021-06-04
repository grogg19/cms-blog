<?php
/**
 * Subscribe Partial
 */

use App\Model\User;
/**
 * @var $token;
 * @var User $user
 */

?>
<?php if($user == null) { ?>
	<div class="dx-widget dx-box dx-box-decorated mb-40 subscribe-block" >
		<div class="dx-widget-title">
			Подписаться на рассылку
		</div>
		<div class="dx-widget-subscribe">
			<div class="dx-widget-text">
				<p>Подпишитесь, чтобы получать уведомления о выходе новых статей.</p>
			</div>
			<form class="dx-form dx-form-group-inputs" id="subscribe_form" name="subscribe_form" action="/manage-subscribes/public/subscribe">
				<input type="hidden" name="_token" value="<?= $token ?>" />
				<input type="email" name="emailSubscribe" value="" id="emailSubscribe" aria-describedby="emailHelp" class="form-control form-control-style-2 div-emailSubscribe" placeholder="Ваш Email адрес">
				<button class="dx-btn dx-btn-lg dx-btn-icon" type="button" data-form="subscribe_form"><span class="icon fas fa-paper-plane"></span></button>
			</form>
		</div>
	</div>
<?php } elseif (!isset($user->subscribeEmail->email)) { ?>
	<div class="dx-widget dx-box dx-box-decorated mb-40 subscribe-block" >
		<div class="dx-widget-title">
			Подписаться на рассылку
		</div>
		<div class="dx-widget-subscribe">
			<div class="dx-widget-text">
				<p>Подпишитесь, чтобы получать уведомления о выходе новых статей.</p>
			</div>
			<div class="row">
				<form class="dx-form dx-form-group-inputs text-center col-12" id="subscribe_form" name="subscribe_form" action="/manage-subscribes/admin/subscribe">
					<input type="hidden" name="_token" value="<?= $token ?>" />
					<input type="checkbox" id="subscribe_switch"
							class="text-center"
					        data-toggle="toggle"
					        data-style="ios"
					        data-form="subscribe_form"
					        data-email="<?= $user->email ?>" />
				</form>
			</div>
		</div>
	</div>
<?php } ?>

