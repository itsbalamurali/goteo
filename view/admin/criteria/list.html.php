<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
$filters = $this['filters'];
?>
<a href="/admin/criteria/add" class="button"><?php echo Text::_('Add Criteria'); ?></a>

<div class="widget board">
    <form id="sectionfilter-form" action="/admin/criteria" method="get">
        <label for="section-filter"><?php echo Text::_('Show the section criteria:'); ?></label>
        <select id="section-filter" name="section" onchange="document.getElementById('sectionfilter-form').submit();">
        <?php foreach ($this['sections'] as $sectionId=>$sectionName) : ?>
            <option value="<?php echo $sectionId; ?>"<?php if ($filters['section'] == $sectionId) echo ' selected="selected"';?>><?php echo $sectionName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['criterias'])) : ?>
    <table>
        <thead>
            <tr>
                <td><!-- Edit --></td>
                <th><?php echo Text::_('Title'); ?></th> <!-- title -->
                <th><?php echo Text::_('Position'); ?></th> <!-- order -->
                <td><!-- Move up --></td>
                <td><!-- Move down --></td>
                <th><!-- Traducir--></th>
                <td><!-- Remove --></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['criterias'] as $criteria) : ?>
            <tr>
                <td><a href="/admin/criteria/edit/<?php echo $criteria->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $criteria->title; ?></td>
                <td><?php echo $criteria->order; ?></td>
                <td><a href="/admin/criteria/up/<?php echo $criteria->id; ?>">[&uarr;]</a></td>
                <td><a href="/admin/criteria/down/<?php echo $criteria->id; ?>">[&darr;]</a></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/criteria/edit/<?php echo $criteria->id; ?>" >[<?php echo Text::_("Translate"); ?>]</a></td>
                <?php endif; ?>
                <td><a href="/admin/criteria/remove/<?php echo $criteria->id; ?>" onclick="return confirm('<?php echo Text::_('Are you sure you want to delete this search?'); ?>');">[<?php echo Text::_("Remove"); ?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_('No records found'); ?></p>
    <?php endif; ?>
</div>