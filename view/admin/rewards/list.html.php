<?php
use Goteo\Library\Text,
    Goteo\Model\Invest;

$filters = $this['filters'];

$emails = Invest::emails(true);
?>
<div class="widget board">
    <h3 class="title"><?php echo Text::_('Filters'); ?></h3>
    <form id="filter-form" action="/admin/rewards" method="get">
        <div style="float:left;margin:5px;">
            <label for="projects-filter"><?php echo Text::_('Project'); ?></label><br />
            <select id="projects-filter" name="project" onchange="document.getElementById('filter-form').submit();">
                <option value=""><?php echo Text::_('All Projects'); ?></option>
            <?php foreach ($this['projects'] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters['project'] === (string) $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        
        <div style="float:left;margin:5px;">
            <label for="name-filter"><?php echo Text::_('Alias ​​/ User Email:');?></label><br />
            <input type="text" id ="name-filter" name="name" value ="<?php echo $filters['name']?>" />
        </div>

        <div style="float:left;margin:5px;">
            <label for="status-filter"><?php echo Text::_('Show by state reward:'); ?></label><br />
            <select id="status-filter" name="status" >
                <option value=""><?php echo Text::_('All'); ?></option>
            <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <br clear="both" />

        <div style="float:left;margin:5px;">
            <input type="submit" value="<?php echo Text::_('Filter'); ?>" />
        </div>
    </form>
    <br clear="both" />
    <a href="/admin/rewards/?reset=filters"><?php echo Text::_('Remove filters'); ?></a>
</div>

<div class="widget board">
<?php if ($filters['filtered'] != 'yes') : ?>
    <p><?php echo Text::_('It\'s NOT necessary to use a filter, there are too many searches.'); ?></p>
<?php elseif (!empty($this['list'])) : ?>
    <table width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo Text::_('cofinancier'); ?></th>
                <th><?php echo Text::_('Project'); ?></th>
                <th><?php echo Text::_('Reward'); ?></th>
                <th><?php echo Text::_('State'); ?></th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['list'] as $reward) : ?>
            <tr>
                <td><a href="/admin/rewards/edit/<?php echo $reward->invest ?>" ><?php echo Text::_('[Edit]'); ?></a></td>
                <td><a href="/admin/users/manage/<?php echo $reward->user ?>" target="_blank" title="<?php echo $reward->name; ?>"><?php echo $reward->email; ?></a></td>
                <td><a href="/admin/projects/?name=<?php echo $this['projects'][$reward->project] ?>" target="_blank"><?php echo Text::recorta($this['projects'][$reward->project], 20); if (!empty($invest->campaign)) echo '<br />('.$this['calls'][$invest->campaign].')'; ?></a></td>
                <td><?php echo $reward->reward_name ?></td>
                <?php if (!$reward->fulfilled) : ?>
                    <td style="color: red;" ><?php echo Text::_('Slope'); ?></td>
                    <td><a href="<?php echo "/admin/rewards/fulfill/{$reward->invest}"; ?>"><?php echo Text::_('[mark fulfilled]'); ?></a></td>
                <?php else : ?>
                    <td style="color: green;" ><?php echo Text::_('Fulfilled'); ?></td>
                    <td><a href="<?php echo "/admin/rewards/unfill/{$reward->invest}"; ?>"><?php echo Text::_('[To mark]'); ?></a></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
<?php else : ?>
    <p><?php echo Text::_('No contributions that match the filters.'); ?></p>
<?php endif;?>
</div>