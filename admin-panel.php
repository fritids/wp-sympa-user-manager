<div id="sympa-users" class="wrap">
   <h2>Sympa users admin</h2>
   <?php foreach(get_users() as $user): ?>
   <?php echo $user->user_email; ?> 
   <?php endforeach; ?>
</div>

