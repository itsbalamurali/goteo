<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;

$filters = $this['filters'];
$botones = array(
    'edit' => '[<?php echo Text::_("Edit"); ?>]',
    'remove' => '[Quitar]',
    'up' => '[&uarr;]',
    'down' => '[&darr;]'
);

// ancho de los tds depende del numero de columnas
$cols = count($this['columns']);
$per = 100 / $cols;

?>
<?php if (!empty($this['addbutton'])) : ?>
<a href="<?php echo $this['url'] ?>/add" class="button"><?php echo $this['addbutton'] ?></a>
<?php endif; ?>
<!-- Filtro -->
<?php if (!empty($filters)) : ?>
<div class="widget board">
    <form id="filter-form" action="<?php echo $this['url']; ?>" method="get">
        <?php foreach ($filters as $id=>$fil) : ?>
        <?php if ($fil['type'] == 'select') : ?>
            <label for="filter-<?php echo $id; ?>"><?php echo $fil['label']; ?></label>
            <select id="filter-<?php echo $id; ?>" name="<?php echo $id; ?>" onchange="document.getElementById('filter-form').submit();">
            <?php foreach ($fil['options'] as $val=>$opt) : ?>
                <option value="<?php echo $val; ?>"<?php if ($fil['value'] == $val) echo ' selected="selected"';?>><?php echo $opt; ?></option>
            <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <?php if ($fil['type'] == 'input') : ?>
            <br />
            <label for="filter-<?php echo $id; ?>"><?php echo $fil['label']; ?></label>
            <input name="<?php echo $id; ?>" value="<?php echo (string) $fil['value']; ?>" />
            <input type="submit" name="filter" value="<?php echo Text::_("Search"); ?>">
        <?php endif; ?>
        <?php endforeach; ?>
    </form>
</div>
<?php endif; ?>

<!-- lista -->
<div class="widget board">
<?php if ($filters['filtered'] != 'yes') : ?>
    <p><?php echo Text::_("It's not necessary to use a filter, there are too many searches.");?></p>
<?php elseif (!empty($this['data'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- editar --></th>
                <th><?php echo Text::_("Text"); ?></th>
                <th><?php echo Text::_("Grouping"); ?></th>
                <th><!-- Traducir --></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this['data'] as $item) : ?>
            <tr>
                <td><a href="/admin/texts/edit/<?php echo $item->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $item->text; ?></td>
                <td><?php echo $item->group; ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/texts/edit/<?php echo $item->id; ?>" >[<?php echo Text::_("Translate")?>]</a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>