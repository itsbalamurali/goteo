<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

$translator = ACL::check('/translate') ? true : false;
?>
<a href="/admin/glossary/add" class="button"><?php echo Text::_("New term"); ?></a>

<div class="widget board">
    <?php if (!empty($this['posts'])) : ?>
    <table>
        <thead>
            <tr>
                <td><!-- <?php echo Text::_("Edit"); ?> --></td>
                <th><?php echo Text::_("Títle"); ?></th> <!-- title -->
                <th><!-- <?php echo Text::_("Traducir"); ?>--></th>
                <td><!-- Remove --></td>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['posts'] as $post) : ?>
            <tr>
                <td><a href="/admin/glossary/edit/<?php echo $post->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><?php echo $post->title; ?></td>
                <?php if ($translator) : ?>
                <td><a href="/translate/glossary/edit/<?php echo $post->id; ?>" >[<?php echo Text::_("Translate"); ?>]</a></td>
                <?php endif; ?>
                <td><a href="/admin/glossary/remove/<?php echo $post->id; ?>" onclick="return confirm('<?php echo Text::_("Are you sure you want to delete this search?"); ?>');">[<?php echo Text::_("Quit"); ?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>