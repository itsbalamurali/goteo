<?php

use Goteo\Library\Text;

$filters = $this['filters'];

?>
<!-- filtros -->
<?php $the_filters = array(
    'projects' => array (
        'label' => Text::_("Project"),
        'first' => Text::_("All Projects")),
    'users' => array (
        'label' => Text::_("Users"),
        'first' => Text::_("All users")),
    'methods' => array (
        'label' => Text::_("Method of payment"),
        'first' => Text::_("All types")),
    'investStatus' => array (
        'label' => Text::_("State of Contribution "),
        'first' => Text::_("All states")),
    'campaigns' => array (
        'label' => Text::_("Campaign "),
        'first' => Text::_("All campaigns")),
    'review' => array (
        'label' => Text::_("To be reviewed"),
        'first' => Text::_("All")),
); ?>
<a href="/admin/accounts/viewer" class="button"><?php echo Text::_("Scope of Logs"); ?></a>&nbsp;&nbsp;&nbsp;
<div class="widget board">
    <h3 class="title"><?php echo Text::_("Filters"); ?></h3>
    <form id="filter-form" action="/admin/accounts" method="get">
        <input type="hidden" name="filtered" value="yes" />
        <input type="hidden" name="status" value="all" />
        <?php foreach ($the_filters as $filter=>$data) : ?>
        <div style="float:left;margin:5px;">
            <label for="<?php echo $filter ?>-filter"><?php echo $data['label'] ?></label><br />
            <select id="<?php echo $filter ?>-filter" name="<?php echo $filter ?>" onchange="document.getElementById('filter-form').submit();">
                <option value="<?php if ($filter == 'investStatus' || $filter == 'status') echo 'all' ?>"<?php if (($filter == 'investStatus' || $filter == 'status') && $filters[$filter] == 'all') echo ' selected="selected"'?>><?php echo $data['first'] ?></option>
            <?php foreach ($this[$filter] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters[$filter] === (string) $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <?php endforeach; ?>
        <div style="float:left;margin:5px;">
            <label for="date-filter-from"><?php echo Text::_("Date from"); ?></label><br />
            <input type="text" id ="date-filter-from" name="date_from" value ="" />
        </div>
        <div style="float:left;margin:5px;">
            <label for="date-filter-until"><?php echo Text::_("Date to"); ?></label><br />
            <input type="text" id ="date-filter-until" name="date_until" value ="<?php echo date('Y-m-d') ?>" />
        </div>
        <div style="float:left;margin:5px;">
            <input type="submit" value="<?php echo Text::_('Filter'); ?>" />
        </div>
    </form>
    <br clear="both" />
    <a href="/admin/accounts"><?php echo Text::_('Remove filters'); ?></a>
</div>

<div class="widget board">
<?php if ($filters['filtered'] != 'yes') : ?>
    <p><?php echo Text::_("It's not necessary to use a filter, there are too many searches."); ?></p>
<?php elseif (!empty($this['list'])) : ?>
<?php $Total = 0; foreach ($this['list'] as $invest) { $Total += $invest->amount; } ?>
    <p><strong><?php echo Text::_("TOTAL"); ?>:</strong>  <?php echo number_format($Total, 0, '', '.') ?> &euro;</p>
    
    <table width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo Text::_("Contribution ID"); ?></th>
                <th><?php echo Text::_("Date"); ?></th>
                <th><?php echo Text::_("Co-financiers"); ?></th>
                <th><?php echo Text::_("Project"); ?></th>
                <th><?php echo Text::_("Status"); ?></th>
                <th><?php echo Text::_("Method"); ?></th>
                <th><?php echo Text::_("State Contribution"); ?></th>
                <th><?php echo Text::_("Amount"); ?></th>
                <th><?php echo Text::_("Extra"); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this['list'] as $invest) : ?>
            <tr>
                <td><a href="/admin/accounts/details/<?php echo $invest->id ?>">[<?php echo Text::_("Details"); ?>]</a></td>
                <td><?php echo $invest->id ?></td>
                <td><?php echo $invest->invested ?></td>
                <td><?php echo $this['users'][$invest->user] ?></td>
                <td><?php echo $this['projects'][$invest->project]; if (!empty($invest->campaign)) echo '<br />('.$this['campaigns'][$invest->campaign].')'; ?></td>
                <td><?php echo $this['status'][$invest->status] ?></td>
                <td><?php echo $this['methods'][$invest->method] ?></td>
                <td><?php echo $this['investStatus'][$invest->investStatus] ?></td>
                <td><?php echo $invest->amount ?></td>
                <td><?php echo $invest->charged ?></td>
                <td><?php echo $invest->returned ?></td>
                <td>
                    <?php if ($invest->anonymous == 1)  echo Text::_("Anonymous") ?>
                    <?php if ($invest->resign == 1)  echo Text::_("Donation") ?>
                    <?php if (!empty($invest->admin)) echo Text::_("Manual") ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
<?php else : ?>
    <p><?php echo Text::_("No transactions that match the filters."); ?></p>
<?php endif;?>
</div>