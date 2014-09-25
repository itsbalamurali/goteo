<?php

use Goteo\Library\Text,
    Goteo\Library\Content;

$bodyClass = 'admin';

// paginacion
require_once 'library/pagination/pagination.php';

$filter = $this['filter'];
$table  = $this['table'];
$this['filters']['table'] = $table;

$data = Content::getAll($this['filters'], $_SESSION['translator_lang']);

//recolocamos los post para la paginacion
$list = array();
foreach ($data['pending'] as $key=>$item) {
    $item->pendiente = 1;
    $list[] = $item;
}
foreach ($data['ready'] as $key=>$item) {
    $item->pendiente = 0;
    $list[] = $item;
}

$pagedResults = new \Paginated($list, 20, isset($_GET['page']) ? $_GET['page'] : 1);

// valores de filtro
$fields = Content::_fields(); // por tipo de campo
$types = $fields[$table];

// metemos el todos
\array_unshift($types, 'Todos los tipos');

?>
<!-- Filtro -->
<div class="widget board">
    <form id="filter-form" action="/translate/<?php echo $table ?>/list/<?php echo $filter ?>" method="get">
        <input type="hidden" name="table" value="<?php echo $table ?>" />

        <label for="filter-<?php echo $id; ?>">Filtrar por campo:</label>
        <select id="filter-<?php echo $id; ?>" name="<?php echo $id; ?>" onchange="document.getElementById('filter-form').submit();">
        <?php foreach ($types as $val=>$opt) : ?>
            <option value="<?php echo $val; ?>"<?php if ($this['filters']['type'] == $val) echo ' selected="selected"';?>><?php echo $opt; ?></option>
        <?php endforeach; ?>
        </select>
        
        <label for="filter-<?php echo $id; ?>">Buscar texto:</label>
        <input name="<?php echo $id; ?>" value="<?php echo (string) $this['filters']['text']; ?>" />

        <input type="submit" name="filter" value="Buscar">
    </form>
</div>

<!-- lista -->
<?php if (!empty($data)) : ?>
<div class="widget board">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Texto</th>
                <th>Campo</th>
                <th>Id</th>
                <?php if ($table == 'post') echo '<th></th>'; ?>
            </tr>
        </thead>

        <tbody>
        <?php while ($item = $pagedResults->fetchPagedRow()) : ?>
            <tr>
                <td width="5%"><a title="Registro <?php echo $item->id ?>" href='/translate/<?php echo $table ?>/edit/<?php echo $item->id . $filter . '&page=' . $_GET['page'] ?>'>[Edit]</a></td>
                <td width="75%"><?php if ($item->pendiente == 1) echo '* '; ?><?php echo Text::recorta($item->value, 250) ?></td>
                <td><?php echo $item->fieldName ?></td>
                <td><?php echo $item->id ?></td>
                <?php if ($table == 'post') : ?>
                <td><a href="/blog/<?php echo $item->id; ?>?preview=<?php echo $_SESSION['user']->id ?>" target="_blank">[<?php echo Text::_("View"); ?>]</a></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
    <ul id="pagination">
        <?php   $pagedResults->setLayout(new DoubleBarLayout());
                echo $pagedResults->fetchPagedNavigation(str_replace('?', '&', $filter)); ?>
    </ul>
<?php else : ?>
<p>No se han encontrado registros</p>
<?php endif; ?>
