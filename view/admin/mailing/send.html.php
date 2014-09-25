<?php

use Goteo\Library\Text;

$data = $this['data'];

$filters = $_SESSION['mailing']['filters'];
$receivers = $_SESSION['mailing']['receivers'];
$users = $this['users'];

?>
<div class="widget">
    <p><?php echo Text::_("La comunicación se ha enviado correctamente con este contenido:"); ?></p>
        <blockquote><?php echo $this['content'] ?></blockquote>
    
    <p><?php echo Text::_("Buscábamos comunicarnos con ") . $_SESSION['mailing']['filters_txt']; ?><?php echo Text::_(" y finalmente hemos enviado a los siguientes destinatarios: "); ?></p>
        <blockquote><?php foreach ($users as $usr) {
                echo $receivers[$usr]->ok ? Text::_("Enviado a ") : Text::_("Fallo al enviar a ");
                echo '<strong>' .$receivers[$usr]->name . '</strong> ('.$receivers[$usr]->id.') <?php echo Text::_("al mail "); ?><strong>' . $receivers[$usr]->email . '</strong><br />';
        } ?></blockquote>
</div>

