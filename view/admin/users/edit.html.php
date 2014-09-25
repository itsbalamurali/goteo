<?php

use Goteo\Library\Text;

$data = $this['data'];
$user = $this['user'];

$roles = $user->roles;
array_walk($roles, function (&$role) { $role = $role->name; });
?>
<!-- <span style="font-style:italic;font-weight:bold;">Atención! Le llegará email de verificación al usuario como si se hubiera registrado.</span> -->
<div class="widget">
    <dl>
        <dt>Username:</dt>
        <dd><?php echo $user->name ?></dd>
    </dl>
    <dl>
        <dt>Login Access:</dt>
        <dd><strong><?php echo $user->id ?></strong></dd>
    </dl>
    <dl>
        <dt>Email:</dt>
        <dd><?php echo $user->email ?></dd>
    </dl>
    <dl>
        <dt>Current Roles:</dt>
        <dd><?php echo implode(', ', $roles); ?></dd>
    </dl>
    <dl>
        <dt>Account Status:</dt>
        <dd><strong><?php echo $user->active ? 'Activa' : 'Inactiva'; ?></strong></dd>
    </dl>

    <form action="/admin/users/edit/<?php echo $user->id ?>" method="post">
        <p>
            <label for="user-email">Email:</label><span style="font-style:italic;">Be valid. They verify that it is not repeated</span><br />
            <input type="text" id="user-email" name="email" value="<?php echo $data['email'] ?>" style="width:500px" maxlength="255"/>
        </p>
        <p>
            <label for="user-password">Password:</label><span style="font-style:italic;">Minimum 6 characters. It will be encrypted and cannot be seen</span><br />
            <input type="text" id="user-password" name="password" value="<?php echo $data['password'] ?>" style="width:500px" maxlength="255"/>
        </p>

        <input type="submit" name="edit" value="Update"  onclick="return confirm('You understand that you are changing critical data account number?');"/><br />
        <span style="font-style:italic;font-weight:bold;color:red;">Attention! They are being replaced directly with entered data, no authorization email will be sent.</span>

    </form>
</div>