<?php

use Goteo\Library\Text,
    Goteo\Core\View;

// paginacion
require_once 'library/pagination/pagination.php';

$filters = $this['filters'];
$templates = $this['templates'];
$the_filters = '';
foreach ($filters as $key => $value) {
    $the_filters .= "&{$key}={$value}";
}

$pagedResults = new \Paginated($this['sended'], 20, isset($_GET['page']) ? $_GET['page'] : 1);
?>
<div class="widget board">
    <form id="filter-form" action="/admin/sended" method="get">
        <div style="float:left;margin:5px;">
            <label for="user-filter"><?php echo Text::_("ID, name or email of the recipient"); ?></label><br />
            <input id="user-filter" name="user" value="<?php echo $filters['user']; ?>" style="width:300px;"/>
        </div>

        <div style="float:left;margin:5px;">
            <label for="template-filter"><?php echo Text::_("Template"); ?></label><br />
            <select id="template-filter" name="template" onchange="document.getElementById('filter-form').submit();" >
                <option value=""><?php echo Text::_("All Templates"); ?></option>
                <?php foreach ($templates as $templateId => $templateName) : ?>
                    <option value="<?php echo $templateId; ?>"<?php if ($filters['template'] == $templateId)
                    echo ' selected="selected"'; ?>><?php echo $templateName; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <br clear="both" />


        <div style="float:left;margin:5px;" id="date-filter-from">
            <label for="date-filter-from"><?php echo Text::_("From Date"); ?></label><br />
<?php echo new View('library/superform/view/element/datebox.html.php', array('value' => $filters['date_from'], 'id' => 'date-filter-from', 'name' => 'date_from')); ?>
        </div>
        <div style="float:left;margin:5px;" id="date-filter-until">
            <label for="date-filter-until"><?php echo Text::_("To Date"); ?></label><br />
<?php echo new View('library/superform/view/element/datebox.html.php', array('value' => $filters['date_until'], 'id' => 'date-filter-until', 'name' => 'date_until')); ?>
        </div>
        <div style="float:left;margin:5px;">
            <input type="submit" name="filter" value="<?php echo Text::_("Filter"); ?>">
        </div>

    </form>
</div>

<div class="widget board">
    <?php if ($filters['filtered'] != 'yes') : ?>
        <p><?php echo Text::_("It's not necessary to use a filter, there are too many searches."); ?></p>
<?php elseif (!empty($this['sended'])) : ?>
        <table>
            <thead>
                <tr>
                    <th width="5%"><!-- Si no ves --></th>
                    <th width="45%"><?php echo Text::_("Recipient"); ?></th>
                    <th width="35%"><?php echo Text::_("Template"); ?></th>
                    <th width="15%"><?php echo Text::_("Date"); ?></th>
                    <th><!-- reenviar --></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($send = $pagedResults->fetchPagedRow()) :
                    $link = SITE_URL . '/mail/' . base64_encode(md5(uniqid()) . '¬' . $send->email . '¬' . $send->id) . '/?email=' . urlencode($send->email);
                    ?>
                    <tr>
                        <td><a href="<?php echo $link; ?>" target="_blank">[<?php echo Text::_("link"); ?>]</a></td>
                        <td><a href="/admin/users/?name=<?php echo urlencode($send->email) ?>"><?php echo $send->email; ?></a></td>
                        <td><?php echo $templates[$send->template]; ?></td>
                        <td><?php echo $send->date; ?></td>
                        <td><!-- <a href="#" target="_blank">[Reenviar]</a> --></td>
                    </tr>
    <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <ul id="pagination">
    <?php $pagedResults->setLayout(new DoubleBarLayout());
    echo $pagedResults->fetchPagedNavigation(str_replace('?', '&', $the_filters)); ?>
    </ul>
<?php else : ?>
   <p><?php echo Text::_("No records found"); ?></p>
<?php endif; ?>