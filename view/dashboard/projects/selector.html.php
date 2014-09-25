<?php

?>
<div id="project-selector">
    <?php if (!empty($this['projects'])) : ?>
        <form id="selector-form" name="selector_form" action="<?php echo '/dashboard/'.$this['section'].'/'.$this['option'].'/select'; ?>" method="post">
        <label for="selector"><?php echo Text::_("Project");?></label>
        <select id="selector" name="project" onchange="document.getElementById('selector-form').submit();">
        <?php foreach ($this['projects'] as $project) : ?>
            <option value="<?php echo $project->id; ?>"<?php if ($project->id == $_SESSION['project']->id) echo ' selected="selected"'; ?>><?php echo $project->name; ?></option>
        <?php endforeach; ?>
        </select>
        <!-- un boton para seleccionar si no tiene javascript -->
        </form>
    <?php else : ?>
    <p>Nor have ning &uacute; n project, you can create one <a href="/project/create">here&iacute;</a></p>
    <?php endif; ?>
</div>
