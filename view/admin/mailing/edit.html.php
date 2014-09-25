<?php


use Goteo\Library\Text,
    Goteo\Library\Template;

//$templates = Template::getAllMini();
$templates = array(
    '11' => Text::_("Base"),
    '27' => Text::_("Contact technical support")
);
// lista de destinatarios segun filtros recibidos, todos marcados por defecto
?>
<script type="text/javascript">
jQuery(document).ready(function ($) {

    $('#template_load').click(function () {
       if (confirm(Text::_("The subject and the actual content substiruira by the one in the template. We continue?"))) {

           if ($('#template').val() == '0') {
            $('#mail_subject').val('');
            $('#mail_content').html('');
           }
            content = $.ajax({async: false, url: '<?php echo SITE_URL; ?>/ws/get_template_content/'+$('#template').val()}).responseText;
            var arr = content.split('#$#$#');
            $('#mail_subject').val(arr[0]);
            $('#mail_content').val(arr[1]);
        }
    });

});
</script>
<div class="widget">
    <p><?php echo Text::_("N in the content, the following variables were replaced & aacute:"); ?></p>
    <ul>
        <li><strong>%USERID%</strong><?php echo Text::_("To access the id of the recipient"); ?></li>
        <li><strong>%USEREMAIL%</strong><?php echo Text::_(" For the email recipient"); ?></li>
        <li><strong>%USERNAME%</strong><?php echo Text::_(" For the name of the recipient"); ?></li>
        <li><strong>%SITEURL%</strong><?php echo Text::_(" For the url of this platform "); ?>(<?php echo SITE_URL ?>)</li>
        <?php if ($this['filters']['type'] == 'owner' || $this['filters']['type'] == 'investor') : ?>
            <li><strong>%PROJECTID%</strong><?php echo Text::_(" For the id of the project"); ?></li>
            <li><strong>%PROJECTNAME%</strong><?php echo Text::_(" For the project name"); ?></li>
            <li><strong>%PROJECTURL%</strong><?php echo Text::_(" For project url"); ?></li>
        <?php endif; ?>
    </ul>
</div>
<div class="widget">
    <p><?php echo Text::_("We will communicate with ") . $_SESSION['mailing']['filters_txt']; ?></p>
    <form action="/admin/mailing/send" method="post">
    <dl>
        <dt><?php echo Text::_("Select template:"); ?></dt>
        <dd>
            <select id="template" name="template" >
                <option value="0"><?php echo Text::_("No template"); ?></option>
            <?php foreach ($templates as $templateId=>$templateName) : ?>
                <option value="<?php echo $templateId; ?>"><?php echo $templateName; ?></option>
            <?php endforeach; ?>
            </select>
            <input type="button" id="template_load" value="<?php echo Text::_("Load"); ?>" />
        </dd>
    </dl>
    <dl>
        <dt><?php echo Text::_("Subject:"); ?></dt>
        <dd>
            <input id="mail_subject" name="subject" value="<?php echo $_SESSION['mailing']['subject']?>" style="width:500px;"/>
        </dd>
    </dl>
    <dl>
        <dt><?php echo Text::_("Contents: (html code; linebreaks should be with &lt;br /&gt;)"); ?></dt>
        <dd>
            <textarea id="mail_content" name="content" cols="100" rows="10"></textarea>
        </dd>
    </dl>
    <dl>
        <dt><?php echo Text::_("List recipients:"); ?></dt>
        <dd>
            <ul>
                <?php foreach ($_SESSION['mailing']['receivers'] as $usrid=>$usr) : ?>
                <li>
                    <input type="checkbox"
                           name="receiver_<?php echo $usr->id; ?>"
                           id="receiver_<?php echo $usr->id; ?>"
                           value="1"
                           checked="checked" />
                    <label for="receiver_<?php echo $usr->id; ?>"><?php echo $usr->name.' ['.$usr->email.']'; if (!empty($usr->project)) echo ' Proyecto: <strong>'.$usr->project.'</strong>'; ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </dd>
    </dl>

    <input type="submit" name="send" value="<?php echo Text::_('Send'); ?>"  onclick="return confirm(Text::_("You have reviewed the contents and found the audience?"));"/>

    </form>
</div>