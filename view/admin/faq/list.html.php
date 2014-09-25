<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
$filters = $this['filters'];
?>
<a href="/admin/faq/add/?filter=" class="button red"><?php echo Text::_("Add question");?></a>

<div class="widget board">
    <form id="sectionfilter-form" action="/admin/faq" method="get">
        <label for="section-filter"><?php echo Text::_("Show questions from:");?></label>
        <select id="section-filter" name="section" onchange="document.getElementById('sectionfilter-form').submit();">
        <?php foreach ($this['sections'] as $sectionId=>$sectionName) : ?>
            <option value="<?php echo $sectionId; ?>"<?php if ($filters['section'] == $sectionId) echo ' selected="selected"';?>><?php echo $sectionName; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['faqs'])) : ?>
    <table>
        <thead>
            <tr>
                <td><!-- Edit --></td>
                <th><?php echo Text::_("Title");?></th> <!-- title -->
                <th><?php echo Text::_("Position");?></th> <!-- order -->
                <td><!-- Move up --></td>
                <td><!-- Move down --></td>
                <td><!-- Traducir--></td>
                <td><!-- Remove --></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['faqs'] as $faq) : ?>
            <tr>
                <td><a href="/admin/faq/edit/<?php echo $faq->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $faq->title; ?></td>
                <td><?php echo $faq->order; ?></td>
                <td><a href="/admin/faq/up/<?php echo $faq->id; ?>">[&uarr;]</a></td>
                <td><a href="/admin/faq/down/<?php echo $faq->id; ?>">[&darr;]</a></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/faq/edit/<?php echo $faq->id; ?>" >[<?php echo Text::_("Translate")?>]</a></td>
                <?php endif; ?>
                <td><a href="/admin/faq/remove/<?php echo $faq->id; ?>" onclick="return confirm('Are you sure you want to delete this record?');">[<?php echo Text::_("Remove"); ?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_("No records found");?></p>
    <?php endif; ?>
</div>