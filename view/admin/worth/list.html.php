<?php

use Goteo\Library\Text;

?>
<div class="widget board">
    <?php if (!empty($this['worthcracy'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- editar --></th>
                <th><?php echo Text::_('Level');?></th>
                <th><?php echo Text::_('Flow');?></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['worthcracy'] as $worth) : ?>
            <tr>
                <td width="5%"><a href="/admin/worth/edit/<?php echo $worth->id; ?>"><?php echo Text::_('[Edit]');?></a></td>
                <td width="15%"><?php echo $worth->name; ?></td>
                <td width="15%"><?php echo $worth->amount; ?> &euro;</td>
                <td></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php else : ?>
    <p><?php echo Text::_('IMPOSSIBLE !!! No records found');?></p>
    <?php endif; ?>
</div>