<?php

use Goteo\Library\Text;

$filters = $this['filters'];
?>
<a href="/admin/translates/add" class="button"><?php echo Text::_('New project to translate'); ?></a>

<div class="widget board">
<form id="filter-form" action="/admin/translates" method="get">
    <label for="owner-filter"><?php echo Text::_('Projects User'); ?></label>
    <select id="owner-filter" name="owner" onchange="document.getElementById('filter-form').submit();">
        <option value=""><?php echo Text::_('All Producers'); ?></option>
    <?php foreach ($this['owners'] as $ownerId=>$ownerName) : ?>
        <option value="<?php echo $ownerId; ?>"<?php if ($filters['owner'] == $ownerId) echo ' selected="selected"';?>><?php echo $ownerName; ?></option>
    <?php endforeach; ?>
    </select>

    <label for="translator-filter"><?php echo Text::_('Assigned to translator:'); ?></label>
    <select id="translator-filter" name="translator" onchange="document.getElementById('filter-form').submit();">
        <option value=""><?php echo Text::_('All translators'); ?></option>
    <?php foreach ($this['translators'] as $translator) : ?>
        <option value="<?php echo $translator->id; ?>"<?php if ($filters['translator'] == $translator->id) echo ' selected="selected"';?>><?php echo $translator->name; ?></option>
    <?php endforeach; ?>
    </select>
</form>
</div>

<!-- proyectos con la traducción activa -->
<?php if (!empty($this['projects'])) : ?>
        <div class="widget board">
            <table>
                <thead>
                    <tr>
                        <th width="5%"><!-- editar y asignar --></th>
                        <th width="55%"><?php echo Text::_('Project'); ?></th> <!-- edit -->
                        <th width="30%"><?php echo Text::_('Creator'); ?></th>
                        <th width="10%"><?php echo Text::_('language'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this['projects'] as $project) : ?>
                    <tr>
                        <td><a href="/admin/translates/edit/<?php echo $project->id; ?>">[<?php echo Text::_("Edit"); ?>]</a></td>
                        <td><a href="/project/<?php echo $project->id; ?>" target="_blank" title="Preview"><?php echo $project->name; ?></a></td>
                        <td><?php echo $project->user->name; ?></td>
                        <td><?php echo $project->lang; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
            
        </div>
<?php else : ?>
<p><?php echo Text::_('No records found'); ?></p>
<?php endif; ?>