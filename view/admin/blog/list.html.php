<?php

use Goteo\Library\Text,
    Goteo\Core\ACL;

// paginacion
require_once 'library/pagination/pagination.php';

$translator = ACL::check('/translate') ? true : false;

$filters = $this['filters'];
if (empty($filters['show'])) $filters['show'] = 'all';
$the_filters = '';
foreach ($filters as $key=>$value) {
    $the_filters .= "&{$key}={$value}";
}

$pagedResults = new \Paginated($this['posts'], 10, isset($_GET['page']) ? $_GET['page'] : 1);
?>
<a href="/admin/blog/add" class="button"><?php echo Text::_("New Entry"); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="/admin/blog/reorder" class="button"><?php echo Text::_("Sort the cover"); ?></a>

<div class="widget board">
    <form id="filter-form" action="/admin/blog" method="get">
        <div style="float:left;margin:5px;">
            <label for="show-filter"><?php echo Text::_("Show"); ?>:</label><br />
            <select id="show-filter" name="show" onchange="document.getElementById('filter-form').submit();">
            <?php foreach ($this['show'] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters['show'] == $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <?php if ($filters['show'] == 'updates') : ?>
        <div style="float:left;margin:5px;">
            <label for="blog-filter"><?php echo Text::_("Project"); ?> :</label><br />
            <select id="blog-filter" name="blog" onchange="document.getElementById('filter-form').submit();">
                <option value=""><?php echo Text::_("Any"); ?></option>
            <?php foreach ($this['blogs'] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters['blog'] == $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($filters['show'] == 'entries') : ?>
        <div style="float:left;margin:5px;">
            <label for="blog-filter">Del nodo:</label><br />
            <select id="blog-filter" name="blog" onchange="document.getElementById('filter-form').submit();">
                <option value="">Cualquiera</option>
            <?php foreach ($this['blogs'] as $itemId=>$itemName) : ?>
                <option value="<?php echo $itemId; ?>"<?php if ($filters['blog'] == $itemId) echo ' selected="selected"';?>><?php echo $itemName; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </form>
</div>

<div class="widget board">
    <?php if (!empty($this['posts'])) : ?>
    <table>
        <thead>
            <tr>
                <th><!-- published --></th>
                <th colspan="6"><?php echo Text::_("Title"); ?></th> <!-- title -->
                <th><?php echo Text::_("Date"); ?></th> <!-- date -->
                <th><?php echo Text::_("Author"); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php while ($post = $pagedResults->fetchPagedRow()) : ?>
            <tr>
                <?php $Publicada = Text::_("Published"); ?> 
                <td><?php if ($post->publish) echo '<strong style="color:#20b2b3;font-size:10px;">'.$Publicada.'</strong>'; ?></td>
                <td colspan="6"><?php
                        $style = '';
                        if (isset($this['homes'][$post->id]))
                            $style .= ' font-weight:bold;';
                        if (empty($_SESSION['admin_node']) || $_SESSION['admin_node'] == \GOTEO_NODE) {
                            if (isset($this['footers'][$post->id]))
                                $style .= ' font-style:italic;';
                        }
                            
                      echo "<span style=\"{$style}\">{$post->title}</span>";
                ?></td>
                <td><?php echo $post->fecha; ?></td>
                <td><?php echo $post->user->name . ' (' . $post->owner_name . ')'; ?></td>
            </tr>
            <tr>
                <td><a href="/blog/<?php echo $post->id; ?>?preview=<?php echo $_SESSION['user']->id ?>" target="_blank">[<?php echo Text::_("View"); ?>]</a></td>
                <td>
                <a href="/admin/blog/edit/<?php echo $post->id; ?>">[<?php echo Text::_("Edit"); ?>]</a>
                </td>
                <td><?php if (($post->owner_type == 'node' && $post->owner_id == $node) || $node == \GOTEO_NODE) : ?>
                    <a href="/admin/blog/edit/<?php echo $post->id; ?>">[<?php echo Text::_("Edit"); ?>]</a>
                <?php endif; ?></td>
                <?php 
                    $quitar = Text::_("Remove cover"); 
                    $poner = Text::_("Put on the cover");

                ?>
                <td><?php if (isset($this['homes'][$post->id])) {
                        echo '<a href="/admin/blog/remove_home/'.$post->id.'" style="color:red;">['.$quitar.']</a>';
                    } elseif ($post->publish) {
                        echo '<a href="/admin/blog/add_home/'.$post->id.'" style="color:blue;">['.$poner.']</a>';
                    } ?></td>
                <td><?php if (empty($_SESSION['admin_node']) || $_SESSION['admin_node'] == \GOTEO_NODE) {
                        if (isset($this['footers'][$post->id])) {
                            echo '<a href="/admin/blog/remove_footer/'.$post->id.'" style="color:red;">['.$quitar.']</a>';
                        } elseif ($post->publish) {
                            echo '<a href="/admin/blog/add_footer/'.$post->id.'" style="color:blue;">['.$poner.']</a>';
                        }
                    } ?></td>
                <td>
                <?php if ($translator && $node == \GOTEO_NODE) : ?><a href="/translate/post/edit/<?php echo $post->id; ?>" >[<?php echo Text::_("Translate"); ?>]</a><?php endif; ?>
                <?php if ($node != \GOTEO_NODE && $transNode && ($post->owner_type == 'node' && $post->owner_id == $node)) : ?><a href="/translate/node/<?php echo $node ?>/post/edit/<?php echo $post->id; ?>" target="_blank">[<?php echo Text::_("Translate"); ?>]</a><?php endif; ?>
                </td>
                <td><?php if (!$post->publish && (($post->owner_type == 'node' && $post->owner_id == $_SESSION['admin_node']) || !isset($_SESSION['admin_node']))) : ?>
                    <a href="/admin/blog/remove/<?php echo $post->id; ?>" onclick="return confirm('Are you sure you want to delete this record?');">[<?php echo Text::_("Remove"); ?>]</a>
                <?php endif; ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="9"><hr /></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<ul id="pagination" style="margin-bottom: 10px; padding-left: 150px;">
<?php   $pagedResults->setLayout(new DoubleBarLayout());
        echo $pagedResults->fetchPagedNavigation(str_replace('?', '&', $the_filters)); ?>
</ul>
<?php else : ?>
<p>No records found</p>
<?php endif; ?>




