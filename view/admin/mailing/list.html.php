<?php

use Goteo\Library\Text;

$filters = $_SESSION['mailing']['filters'];

?>
<div class="widget board">
    <form id="filter-form" action="/admin/mailing/edit" method="post">

        <table>
            <tr>
                <td>
                    <label for="type-filter"><?php echo Text::_("At"); ?></label><br />
                    <select id="type-filter" name="type">
                    <?php foreach ($this['types'] as $typeId=>$typeName) : ?>
                        <option value="<?php echo $typeId; ?>"<?php if ($filters['type'] == $typeId) echo ' selected="selected"';?>><?php echo $typeName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="project-filter"><?php echo Text::_("Project that contains the name"); ?></label><br />
                    <input id="project-filter" name="project" value="<?php echo $filters['project']?>" style="width:300px;" />
                </td>
                <td>
                    <label for="status-filter"><?php echo Text::_("Status"); ?></label><br />
                    <select id="status-filter" name="status">
                        <option value="-1"<?php if ($filters['status'] == -1) echo ' selected="selected"';?>>Cualquier estado</option>
                    <?php foreach ($this['status'] as $statusId=>$statusName) : ?>
                        <option value="<?php echo $statusId; ?>"<?php if ($filters['status'] == $statusId) echo ' selected="selected"';?>><?php echo $statusName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="method-filter"><?php echo Text::_("Contributed by"); ?></label><br />
                    <select id="method-filter" name="method">
                        <option value=""><?php echo Text::_("Any method"); ?></option>
                    <?php foreach ($this['methods'] as $methodId=>$methodName) : ?>
                        <option value="<?php echo $methodId; ?>"<?php if ($filters['methods'] == $methodId) echo ' selected="selected"';?>><?php echo $methodName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="interest-filter"><?php echo Text::_("Stakeholders in order"); ?></label><br />
                    <select id="interest-filter" name="interest">
                        <option value=""><?php echo Text::_("Anyone/Anything"); ?></option>
                    <?php foreach ($this['interests'] as $interestId=>$interestName) : ?>
                        <option value="<?php echo $interestId; ?>"<?php if ($filters['interest'] == $interestId) echo ' selected="selected"';?>><?php echo $interestName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <label for="name-filter"><?php echo Text::_("Whose name or email contains"); ?></label><br />
                    <input id="name-filter" name="name" value="<?php echo $filters['name']?>" style="width:300px;" />
                </td>
                <td>
                    <label for="role-filter"><?php echo Text::_("That are"); ?></label><br />
                    <select id="role-filter" name="role">
                        <option value=""><?php echo Text::_("Anyone/Anything"); ?></option>
                    <?php foreach ($this['roles'] as $roleId=>$roleName) : ?>
                        <option value="<?php echo $roleId; ?>"<?php if ($filters['role'] == $roleId) echo ' selected="selected"';?>><?php echo $roleName; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3"><input type="submit" name="select" value="<?php echo Text::_("Search for recipients"); ?>"></td>
            </tr>
        </table>




        

    </form>
</div>