<?php


use Goteo\Library\Text;

?>
<a href="/admin/banners/add" class="button red"><?php echo Text::_("New banner"); ?></a>

<div class="widget board">
    <?php if (!empty($this['bannered'])) : ?>
    <table>
        <thead>
            <tr>
                <th><?php echo Text::_("Project"); ?></th> <!-- preview -->
                <th><?php echo Text::_("State"); ?></th> <!-- status -->
                <th><?php echo Text::_("Position"); ?></th> <!-- order -->
                <th><!-- <?php echo Text::_("Upload"); ?> --></th>
                <th><!-- <?php echo Text::_("get off"); ?> --></th>
                <th><!-- <?php echo Text::_("Edit"); ?>--></th>
                <th><!-- <?php echo Text::_("Remove"); ?>--></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['bannered'] as $banner) : ?>
            <tr>
                <td><a href="/project/<?php echo $banner->project; ?>" target="_blank" title="Preview"><?php echo $banner->name; ?></a></td>
                <td><?php echo $banner->status; ?></td>
                <td><?php echo $banner->order; ?></td>
                <td><a href="/admin/banners/up/<?php echo $banner->project; ?>">[&uarr;]</a></td>
                <td><a href="/admin/banners/down/<?php echo $banner->project; ?>">[&darr;]</a></td>
                <td><a href="/admin/banners/edit/<?php echo $banner->project; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                <td><a href="/admin/banners/remove/<?php echo $banner->project; ?>">[<?php echo Text::_("Quitar"); ?>]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
   <p><?php echo Text::_("No records found"); ?></p>
    <?php endif; ?>
</div>