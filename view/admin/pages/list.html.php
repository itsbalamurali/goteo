<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
?>
<?php if (!isset($_SESSION['admin_node'])) : ?>
<a href="/admin/pages/add" class="button"><?php echo Text::_("New Page"); ?></a>
<?php endif; ?>

<div class="widget board">
    <?php if (!empty($this['pages'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- editar --></th>
                <th><?php echo Text::_("Pages"); ?></th>
                <th><?php echo Text::_("Description"); ?></th>
                <th><!-- Abrir --></th>
                <th><!-- Traducir --></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this['pages'] as $page) : ?>
            <tr>
                <td><a href="/admin/pages/edit/<?php echo $page->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $page->name; ?></td>
                <td><?php echo $page->description; ?></td>
                <td><a href="<?php echo $page->url; ?>" target="_blank">[<?php echo Text::_("View"); ?>]</a></td>
                <td>
                <?php if ($translator && $node == \GOTEO_NODE) : ?>
                <a href="/translate/pages/edit/<?php echo $page->id; ?>" >[<?php echo Text::_("Translate")?>]</a>
                <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p> <php echo Text :: _ ("No records found"); ?> </ p>
    <?php endif; ?>
</div>