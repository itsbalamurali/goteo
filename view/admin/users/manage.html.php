<?php

use Goteo\Library\Text;

$user = $this['user'];
$roles = $this['roles'];
$langs = $this['langs'];
?>
<div class="widget">
    <table>
        <tr>
            <td width="140px">Username</td>
            <td><a href="/user/profile/<?php echo $user->id ?>" target="_blank"><?php echo $user->name ?></a></td>
        </tr>
        <tr>
            <td>Login Access</td>
            <td><strong><?php echo $user->id ?></strong></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $user->email ?></td>
        </tr>
        <tr>
            <td>Node</td>
            <td><?php echo $this['nodes'][$user->node] ?></td>
        </tr>
        <tr>
            <td>Current roles</td>
            <td>
                <?php
                foreach ($user->roles as $role=>$roleData) {
                    if (in_array($role, array('user', 'superadmin', 'root'))) {
                        echo '['.$roleData->name . ']&nbsp;&nbsp;';
                    } else {
                        // onclick="return confirm('Se le va a quitar el rol de <?php echo $roleData->name ? > a este usuario')"
                        ?>
                        [<a href="/admin/users/manage/<?php echo $user->id ?>/no<?php echo $role ?>" style="color:red;text-decoration:none;"><?php echo $roleData->name ?></a>]&nbsp;&nbsp;
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Roles available</td>
            <td>
                <?php
                foreach ($roles as $roleId=>$roleName) {
                    if (!in_array($roleId, array_keys($user->roles)) && !in_array($roleId, array('root', 'superadmin'))) {
                        // onclick="return confirm('Se le va a dar el rol de <?php echo $roleName ? > a este usuario')"
                        ?>
                        <a href="/admin/users/manage/<?php echo $user->id ?>/<?php echo $roleId ?>" style="color:green;text-decoration:none;">[<?php echo $roleName ?>]</a>&nbsp;&nbsp;
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Account Status</td>
            <td>
                <?php if ($user->active) : ?>
                    <a href="<?php echo "/admin/users/manage/{$user->id}/ban"; ?>" style="color:green;text-decoration:none;font-weight:bold;">Active</a>
                <?php else : ?>
                    <a href="<?php echo "/admin/users/manage/{$user->id}/unban"; ?>" style="color:red;text-decoration:none;font-weight:bold;">Inactive</a>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Visibility</td>
            <td>
                <?php if (!$user->hide) : ?>
                    <a href="<?php echo "/admin/users/manage/{$user->id}/hide"; ?>" style="color:green;text-decoration:none;font-weight:bold;">Visible</a>
                <?php else : ?>
                    <a href="<?php echo "/admin/users/manage/{$user->id}/show"; ?>" style="color:red;text-decoration:none;font-weight:bold;">hidden</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div class="widget board">
    <ul>
        <li><a href="/admin/users/edit/<?php echo $user->id; ?>">[Change email / password]</a></li>
        <li><a href="/admin/users/move/<?php echo $user->id; ?>">[Move to another node]</a></li>
        <li><a href="/admin/users/impersonate/<?php echo $user->id; ?>">[Impersonate]</a></li>
        <?php if (isset($_SESSION['admin_menu']['projects']['options']['accounts'])) : ?>
        <li><a href="/admin/accounts/add/?user=<?php echo $user->id; ?>">[Create contribution]</a></li>
        <?php endif; ?>
        <li><a href="/admin/<?php echo (isset($_SESSION['admin_node'])) ? 'invests' : 'accounts'; ?>/?name=<?php echo $user->email; ?>">[Contributions history]</a></li>
        <li><a href="/admin/sended/?user=<?php echo urlencode($user->email); ?>">[ Shipments history]</a></li>
    </ul>




</div>

<?php if (isset($user->roles['translator'])) : ?>
<div class="widget board">
    <h3>Language Translator</h3>
    <?php if (empty($user->translangs)) : ?><p style="font-weight: bold; color:red;">It has no language assigned!</p><?php endif; ?>
    <form method="post" action="/admin/users/translang">
        <input type="hidden" name="user" value="<?php echo $user->id; ?>" />
        <table>
            <?php foreach ($langs as $lang) :
                $chkckd = (isset($user->translangs[$lang->id])) ? ' checked="checked"' : '';
                ?>
            <tr>
                <td><label><input type="checkbox" name="lang_<?php echo $lang->id; ?>" value="<?php echo $lang->id; ?>"<?php echo $chkckd; ?>/> <?php echo $lang->name; ?></label></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <input type="submit" value="Apply">
    </form>
</div>
<?php endif; ?>
