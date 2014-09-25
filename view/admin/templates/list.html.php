<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;

$filters = $this['filters'];
?>
<div class="widget board">
    <form id="filter-form" action="/admin/templates" method="get">
        <table>
            <tr>
                <td>
                    <label for="group-filter"><?php echo Text::_("Filter grouping:"); ?></label><br />
                    <select id="group-filter" name="group">
                        <option value=""><?php echo Text::_("All clusters"); ?></option>
                    <?php foreach ($this['groups'] as $groupId=>$groupName) : ?>
                        <option value="<?php echo $groupId; ?>"<?php if ($filters['group'] == $groupId) echo ' selected="selected"';?>><?php echo $groupName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="name-filter"><?php echo Text::_("Filter by Name or Subject"); ?></label><br />
                    <input type="text" id ="name-filter" name="name" value ="<?php echo $filters['name']?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="filter" value="<?php echo Text::_("Filter"); ?>">
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['templates'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- editar --></th>
                <th><?php echo Text::_("Template"); ?></th>
                <th><?php echo Text::_("Description"); ?></th>
                <th><!-- traducir --></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this['templates'] as $template) : ?>
            <tr>
                <td><a href="/admin/templates/edit/<?php echo $template->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $template->name; ?></td>
                <td><?php echo $template->purpose; ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/template/edit/<?php echo $template->id; ?>" >[<?php echo Text::_("Translate"); ?>]</a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
   <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>