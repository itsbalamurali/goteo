<?php


use Goteo\Library\Text;

?>
<script type="text/javascript">

jQuery(document).ready(function ($) {

    $('#faq-section').change(function () {
        order = $.ajax({async: false, url: '<?php echo SITE_URL; ?>/ws/get_faq_order/'+$('#faq-section').val()}).responseText;
        $('#faq-order').val(order);
        $('#faq-num').html(order);
    });

});
</script>
<div class="widget board">
    <form method="post" action="/admin/faq">

        <input type="hidden" name="action" value="<?php echo $this['action']; ?>" />
        <input type="hidden" name="id" value="<?php echo $this['faq']->id; ?>" />

        <p>
        <?php if ($this['action'] == 'add') : ?>
            <label for="faq-section"><?php echo Text::_("Section");?>:</label><br />
            <select id="faq-section" name="section">
                <option value="" disabled><?php echo Text::_("Choose section");?></option>
                <?php foreach ($this['sections'] as $id=>$name) : ?>
                <option value="<?php echo $id; ?>"<?php if ($id == $this['faq']->section) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        <?php else : ?>
            <label for="faq-section"><?php echo Text::_("Section");?>: <?php echo $this['sections'][$this['faq']->section]; ?></label><br />
            <input type="hidden" name="section" value="<?php echo $this['faq']->section; ?>" />
        <?php endif; ?>
        </p>

        <p>
            <label for="faq-title"><?php echo Text::_("Title");?>:</label><br />
            <input type="text" name="title" id="faq-title" value="<?php echo $this['faq']->title; ?>" />
        </p>

        <p>
            <label for="faq-description"><?php echo Text::_("Description");?>:</label><br />
            <textarea name="description" id="faq-description" cols="60" rows="10"><?php echo $this['faq']->description; ?></textarea>
        </p>

        <p>
            <label for="faq-order"><?php echo Text::_("Position");?>:</label><br />
            <select name="move">
                <option value="same" selected="selected" disabled><?php echo Text::_("Such as");?></option>
                <option value="up"><?php echo Text::_("Before ");?></option>
                <option value="down"><?php echo Text::_("After ");?></option>
            </select>&nbsp;
            <input type="text" name="order" id="faq-order" value="<?php echo $this['faq']->order; ?>" size="4" />
            &nbsp;de&nbsp;<span id="faq-num"><?php echo $this['faq']->cuantos; ?></span>
        </p>


        <input type="submit" name="save" value="<?php echo Text::_("Save"); ?>" />
    </form>
</div>