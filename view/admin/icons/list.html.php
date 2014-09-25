<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
$filters = $this['filters'];
?>
<div class="widget board">
    <form id="groupfilter-form" action="/admin/icons" method="get">
        <label for="group-filter"> <?php echo Text::_("Show rates for:"); ?></label>
        <select id="group-filter" name="group" onchange="document.getElementById('groupfilter-form').submit();">
            <option value=""><?php echo Text::_("All"); ?></option>
        <?php foreach ($this['groups'] as $groupId=>$groupName) : ?>
            <option value="<?php echo $groupId; ?>"<?php if ($filters['group'] == $groupId) echo ' selected="selected"';?>><?php echo $groupName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['icons'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- editar --></th>
                <th> <?php echo Text::_("Number"); ?></th> <!-- name -->
                <th>Tooltip</th> <!-- descripcion -->
                <th><?php echo Text::_("Grouping"); ?></th> <!-- group -->
                <th><!-- Traducir--></th>
<!--                        <th> Remove </th>  -->
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['icons'] as $icon) : ?>
            <tr>
                <td><a href="/admin/icons/edit/<?php echo $icon->id; ?>"><?php echo Text::_("[Edit]"); ?></a></td>
                <td><?php echo $icon->name; ?></td>
                <td><?php echo $icon->description; ?></td>
                <td><?php echo !empty($icon->group) ? $this['groups'][$icon->group] : Text::_('Both'); ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/icon/edit/<?php echo $icon->id; ?>" ><?php echo Text::_("[Translate]"); ?></a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>