<p><?= !empty($message) ? $message : '' ?></p>
<script>
    setTimeout(() => {  location.reload() }, 3000);
</script>
<?php exit(); ?>