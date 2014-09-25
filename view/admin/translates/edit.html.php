<?php

use Goteo\Library\Text,
    Goteo\Library\i18n\Lang;

$project = $this['project'];
$langs = Lang::getAll();

$filters = $this['filters'];
?>
<script type="text/javascript">
function assign() {
    if (document.getElementById('assign-user').value != '') {
        document.getElementById('form-assign').submit();
        return true;
    } else {
        alert('No has seleccionado ningun traductor');
        return false;
    }
}
</script>
<div class="widget">
<?php if ($this['action'] == 'edit') : ?>
    <h3 class="title"><?php echo Text::_('Translators for the project'); ?> <?php echo $project->name ?></h3>
        <!-- asignar -->
        <table>
            <tr>
                <th><?php echo Text::_('Translation'); ?></th>
                <th></th>
            </tr>
            <?php foreach ($project->translators as $userId=>$userName) : ?>
            <tr>
                <td><?php if ($userId == $project->owner) echo '(AUTOR) '; ?><?php echo $userName; ?></td>
                <td><a href="/admin/translates/unassign/<?php echo $project->id; ?>/?user=<?php echo $userId; ?>">[Desasignar]</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <form id="form-assign" action="/admin/translates/assign/<?php echo $project->id; ?>" method="get">
                <td colspan="2">
                    <select id="assign-user" name="user">
                        <option value=""><?php echo Text::_('Select another translator'); ?></option>
                        <?php foreach ($this['translators'] as $user) :
                            if (in_array($user->id, array_keys($project->translators))) continue;
                            ?>
                        <option value="<?php echo $user->id; ?>"><?php if ($user->id == $project->owner) echo '(AUTOR) '; ?><?php echo $user->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><a href="#" onclick="return assign();" class="button"><?php echo Text::_('assign'); ?></a></td>
                </form>
            </tr>
        </table>
        <hr />
        <a href="/admin/translates/close/<?php echo $project->id; ?>" class="button" onclick="return confirm('Seguro que deseas dar por finalizada esta traducción?')"><?php echo Text::_('Close the translation'); ?></a>&nbsp;&nbsp;&nbsp;
        <a href="/admin/translates/send/<?php echo $project->id; ?>" class="button green" onclick="return confirm('Se va a enviar un email automaticamente, ok?')"><?php echo Text::_('Warn the author'); ?></a>
        <hr />
<?php endif; ?>

    <form method="post" action="/admin/translates/<?php echo $this['action']; ?>/<?php echo $project->id; ?>">

        <table>
            <tr>
                <td><?php if ($this['action'] == 'add') : ?>
                    <label for="add-proj"><?php echo Text::_('Project we enable'); ?></label><br />
                    <select id="add-proj" name="project">
                        <option value=""><?php echo Text::_('Select the Project'); ?></option>
                        <?php foreach ($this['availables'] as $proj) : ?>
                            <option value="<?php echo $proj->id; ?>"<?php if ($_GET['project'] == $proj->id) echo ' selected="selected"';?>><?php echo $proj->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else : ?>
                    <input type="hidden" name="project" value="<?php echo $project->id; ?>" />
                <?php endif; ?></td>
                <td><!-- Idioma original -->
                    <label for="orig-lang"><?php echo Text::_('Original language project'); ?></label><br />
                    <select id="orig-lang" name="lang">
                        <?php foreach ($langs as $item) : ?>
                            <option value="<?php echo $item->id; ?>"<?php if ($project->lang == $item->id || (empty($project->lang) && $item->id == 'es' )) echo ' selected="selected"';?>><?php echo $item->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>


       <input type="submit" name="save" value="<?php echo Text::_('Save'); ?>" />
    </form>
</div>