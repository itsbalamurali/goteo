<?php


use Goteo\Library\Text;

?>
<div class="widget">
    <form id="filter-form" action="/admin/invests/add" method="post">
        <p>
            <label for="invest-amount"><?php echo Text::_("Importe:"); ?></label><br />
            <input type="text" id="invest-amount" name="amount" value="" />
        </p>
        <p>
            <label for="invest-user"><?php echo Text::_("Usuario"); ?>:</label><br />
            <select id="invest-user" name="user">
                <option value=""><?php echo Text::_("Seleccionar usuario que hace el aporte"); ?></option>
            <?php foreach ($this['users'] as $userId=>$userName) : ?>
                <option value="<?php echo $userId; ?>"><?php echo $userName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="invest-project"><?php echo Text::_("Proyecto:"); ?></label><br />
            <select id="invest-project" name="project">
                <option value=""><?php echo Text::_("Seleccionar el proyecto al que se aporta"); ?></option>
            <?php foreach ($this['projects'] as $projectId=>$projectName) : ?>
                <option value="<?php echo $projectId; ?>"><?php echo $projectName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="invest-anonymous"><?php echo Text::_("Aporte anónimo:"); ?></label><br />
            <input id="invest-anonymous" type="checkbox" name="anonymous" value="1">
        </p>

        <input type="submit" name="add" value="<?php echo Text::_("Generar aporte"); ?>" />

    </form>
</div>