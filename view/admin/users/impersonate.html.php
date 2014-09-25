<?php

use Goteo\Library\Text;

$user = $this['user'];

$roles = $user->roles;
array_walk($roles, function (&$role) { $role = $role->name; });
?>
<div class="widget">
    <dl>
        <dt>Username</dt>
        <dd><?php echo $user->name ?></dd>
    </dl>
    <dl>
        <dt>Login Access</dt>
        <dd><strong><?php echo $user->id ?></strong></dd>
    </dl>
    <dl>
        <dt>Email</dt>
        <dd><?php echo $user->email ?></dd>
    </dl>
    <dl>
        <dt>Current roles</dt>
        <dd><?php echo implode(', ', $roles); ?></dd>
    </dl>

    <form action="/impersonate" method="post">
        <input type="hidden" name="id" value="<?php echo $user->id ?>" />

        <input type="submit" class="red" name="impersonate" value="Impersonate this user" onclick="return confirm('You're completely sure I understand what you're doing?');" /><br />
        <span style="font-style:italic;font-weight:bold;color:red;">Attention !! With this you will stop being logged as superadmin who you are and you'll have to be logged in as this user with all permissions and restrictions</span>

    </form>
</div>