<?php


use Goteo\Library\Text;

?>
<script type="text/javascript">

jQuery(document).ready(function ($) {

    $('#criteria-section').change(function () {
        order = $.ajax({async: false, url: '<?php echo SITE_URL; ?>/ws/get_criteria_order/'+$('#criteria-section').val()}).responseText;
        $('#criteria-order').val(order);
        $('#criteria-num').html(order);
    });

});
</script>

<div class="widget board">
    <form method="post" action="/admin/criteria">

        <input type="hidden" name="action" value="<?php echo $this['action']; ?>" />
        <input type="hidden" name="id" value="<?php echo $this['criteria']->id; ?>" />

        <p>
        <?php if ($this['action'] == 'add') : ?>
            <label for="criteria-section"><?php echo Text::_('Section:'); ?></label><br />
            <select id="criteria-section" name="section">
                <option value="" disabled><?php echo Text::_('Choose section'); ?></option>
                <?php foreach ($this['sections'] as $id=>$name) : ?>
                <option value="<?php echo $id; ?>"<?php if ($id == $this['criteria']->section) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        <?php else : ?>
            <label for="criteria-section"><?php echo Text::_("Section"); ?> <?php echo $this['sections'][$this['criteria']->section]; ?></label><br />
            <input type="hidden" name="section" value="<?php echo $this['criteria']->section; ?>" />
        <?php endif; ?>
        </p>

        <p>
            <label for="criteria-title"><?php echo Text::_('Title:'); ?></label><br />
            <input type="text" name="title" id="criteria-title" value="<?php echo $this['criteria']->title; ?>" />
        </p>

        <p>
            <label for="criteria-description"><?php echo Text::_('Description:'); ?></label><br />
            <textarea name="description" id="criteria-description" cols="60" rows="10"><?php echo $this['criteria']->description; ?></textarea>
        </p>

        <p>
            <label for="criteria-order"><?php echo Text::_('Position:'); ?></label><br />
            <select name="move">
                <option value="same" selected="selected" disabled><?php echo Text::_('Such as'); ?></option>
                <option value="up"><?php echo Text::_('Before '); ?></option>
                <option value="down"><?php echo Text::_('After '); ?></option>
            </select>&nbsp;
            <input type="text" name="order" id="criteria-order" value="<?php echo $this['criteria']->order; ?>" size="4" />
            &nbsp;de&nbsp;<span id="criteria-num"><?php echo $this['criteria']->cuantos; ?></span>
        </p>


        <input type="submit" name="save" value="<?php echo Text::_("Save"); ?>" />
    </form>
</div>