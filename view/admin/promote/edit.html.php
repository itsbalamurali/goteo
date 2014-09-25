<?php

use Goteo\Library\Text,
    Goteo\Model;

$promo = $this['promo'];

$node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

// proyectos disponibles
// si tenemos ya proyecto seleccionado lo incluimos
$projects = Model\Promote::available($promo->project, $node);
$status = Model\Project::status();

?>
<form method="post" action="/admin/promote">
    <input type="hidden" name="action" value="<?php echo $this['action'] ?>" />
    <input type="hidden" name="order" value="<?php echo $promo->order ?>" />
    <input type="hidden" name="id" value="<?php echo $promo->id; ?>" />

<p>
    <label for="promo-project"><?php echo Text::_("Project");?></label><br />
    <select id="promo-project" name="project">
        <option value="" ><?php echo Text::_("Select the project to highlight"); ?></option>
    <?php foreach ($projects as $project) : ?>
        <option value="<?php echo $project->id; ?>"<?php if ($promo->project == $project->id) echo' selected="selected"';?>><?php echo $project->name . ' ('. $status[$project->status] . ')'; ?></option>
    <?php endforeach; ?>
    </select>
</p>

<?php if ($node == \GOTEO_NODE) : ?>
<p>
    <label for="promo-name"><?php echo Text::_("Title");?>:</label><span style="font-style:italic;"><?php echo Text::_("Maximum 24 Characters"); ?></span><br />
    <input type="text" name="title" id="promo-title" value="<?php echo $promo->title; ?>" maxlength="24" style="width:500px;" />
</p>

<p>
    <label for="promo-description"><?php echo Text::_("Description");?>:</label><span style="font-style:italic;"><?php echo Text::_("Maximum 100 Characters"); ?></span><br />
    <input type="text" name="description" id="promo-description" maxlength="100" value="<?php echo $promo->description; ?>" style="width:750px;" />
</p>
<?php endif; ?>

<p>
    <label><?php echo Text::_("Posted");?>:</label><br />
    <label><input type="radio" name="active" id="promo-active" value="1"<?php if ($promo->active) echo ' checked="checked"'; ?>/> <?php echo Text::_("Yes"); ?></label>
    &nbsp;&nbsp;&nbsp;
    <label><input type="radio" name="active" id="promo-inactive" value="0"<?php if (!$promo->active) echo ' checked="checked"'; ?>/><?php echo Text::_("No"); ?></label>
</p>

    <input type="submit" name="save" value="<?php echo Text::_("Save");?>" />
</form>
