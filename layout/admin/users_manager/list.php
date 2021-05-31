<?php
/**
 * Список пользователей
 */

/**
 * @var $users
 * @var $pathToAvatar
 * @var $token
 * @var $roles
 * @var $quantity
 * @var $title
 */

use App\View;
use Illuminate\Pagination\LengthAwarePaginator;
use function Helpers\getDateTime;

?>
<div class="dx-box-5 bg-grey-6 ">
    <div class="container">
        <div class="row align-items-center justify-content-between vertical-gap mnt-30 sm-gap mb-50" data-send-url="/update/userdata/">
            <div class="col-auto">
                <h2 class="h2 mb-0 mt-0"><?= $title ?></h2>
            </div>
            <div class="col pl-30 pr-30 d-none d-sm-block">
                <div class="dx-separator ml-10 mr-10"></div>
            </div>
        </div>
	    <input type="hidden" name="_token" value="<?=$token?>">
        <?php
        (new View('partials.quantity_items', [
            'quantity' => $quantity,
            'items' => [10, 20, 50, 200, 'all']
        ]))->render();
        ?>
		<?php if($users->count() > 0) { ?>
		    <?php foreach ($users as $user) { ?>
		        <div class="dx-ticket-item
		        dx-ticket-new
		        <?=($user->is_activated == true) ? 'dx-ticket-open' : 'dx-ticket-close'?>
		        dx-block-decorated">
		            <span class="dx-ticket-img">
		            <?= (empty($user->avatar)) ? '<img src="/layout/assets/images/avatar-1.png" alt="">' : '<img src="' . $pathToAvatar . $user->avatar . '" alt="">' ?>
		            </span>
		            <span class="dx-ticket-cont">
		                <span class="dx-ticket-name">
		                    <?= htmlspecialchars($user->first_name) . ' ' . htmlspecialchars($user->last_name)?>
		                </span>
		                <ul class="dx-ticket-info">
		                    <li>Заходил в последний раз: <br><?= getDateTime($user->last_login) ?></li>
		                    <?=(!empty($user->posts)) ? '<li>Статей опубликовано: ' . count($user->posts) . '</li>' : ''?>
		                    <li>Комментарии: <?= count($user->comments) ?></li>
		                    <li>
		                        <span class="dx-form-group-inputs">
		                            <select class="custom-select form-control-sm form-control-style-2" data-field="role" data-for-send="<?=$user->id?>" data-method="userChangeRole">
		                            <?php foreach ($roles as $role) { ?>
		                                <option value="<?=$role->id?>" <?=($user->role->code === $role->code) ? 'selected' : ''?> ><?=$role->name?></option>
		                            <?php } ?>
		                            </select>
		                        </span>
		                    </li>
		                </ul>
		            </span>
			        <span class="dx-form-group form-control-sm align-self-center pt-0" >
				        <input type="checkbox"
				                data-toggle="toggle"
				                data-on="Деактивация"
				                data-off="Активация"
				                data-onstyle="danger"
				                data-offstyle="success"
				                data-for-send="<?=$user->id?>" data-method="userChangeActivate" data-field="active_status"
					            <?=($user->is_activated == true) ? 'checked' : ''?>
				        />
				    </span>
		        </div>
		    <?php }?>
        <?php
        if($users instanceof LengthAwarePaginator) {
            (new View('partials.pagination', [
                'paginator' => $users
            ]))->render();
        }
        ?>
        <?php } else { ?>
	    <h5 class="mt-30">Список пользователей пуст</h5>
        <?php } ?>
	    <div id="messageToast"></div>
    </div>
</div>