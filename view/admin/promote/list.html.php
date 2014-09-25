<?php
use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
?>
<a href="/admin/promote/add" class="button"><?php echo Text::_('New Featured'); ?></a>

<div class="widget board">
    <?php if (!empty($this['promoted'])) : ?>
    <table>
        <thead>
            <tr>
                <th></th> <!-- preview -->
                <th><?php echo Text::_("Project"); ?></th> <!-- title -->
                <th><?php echo Text::_("Status"); ?></th> <!-- status -->
                <th><?php echo Text::_("Position"); ?></th> <!-- order -->
                <th><!-- Subir --></th>
                <th><!-- Bajar --></th>
                <th><!-- editar--></th>
                <th><!-- On/Off --></th>
                <th><!-- Traducir--></th>
                <th><!-- Quitar--></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['promoted'] as $promo) : ?>
            <tr>
                <td><a href="/project/<?php echo $promo->project; ?>" target="_blank" title="Preview">[<?php echo Text::_("View"); ?>]</a></td>
                <td><?php echo ($promo->active) ? '<strong>'.$promo->name.'</strong>' : $promo->name; ?></td>
                <td><?php echo $promo->status; ?></td>
                <td><?php echo $promo->order; ?></td>
                <td><a href="/admin/promote/up/<?php echo $promo->id; ?>">[&uarr;]</a></td>
                <td><a href="/admin/promote/down/<?php echo $promo->id; ?>">[&darr;]</a></td>
                <td><a href="/admin/promote/edit/<?php echo $promo->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php if ($promo->active) : ?>
                <a href="/admin/promote/active/<?php echo $promo->id; ?>/off">[<?php echo Text::_("Hide")?>]</a>
                <?php else : ?>
                <a href="/admin/promote/active/<?php echo $promo->id; ?>/on">[<?php echo Text::_("Show")?>]</a>
                <?php endif; ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/promote/edit/<?php echo $promo->id; ?>" >[<?php echo Text::_("Translate")?>]</a></td>
                <?php endif; ?>
                <td><a href="/admin/promote/remove/<?php echo $promo->id; ?>" onclick="return confirm('Are you sure you want to delete this record?');">[<?php echo Text::_("Remove")?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
   <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>