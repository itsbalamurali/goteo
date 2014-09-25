<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
 *  This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */

use Goteo\Library\Text,
    Goteo\Library\Template;

$list = $this['list'];

$templates = array(
    '33' => 'Boletin',
    '35' => 'Testeo'
);

// por defecto cogemos la newsletter
$tpl = 33;

$template = Template::get($tpl);

?>
<div class="widget board">
    <p>Select the template. Translated content will be used, you may want <a href="/admin/templates?group=massive" target="_blank">Review them</a></p>
    <p><strong>NOTE:</strong> with this system you can not add variables in the content, the same content for all recipients is generated.<br/>
    For custom content you must use the functionality<a href="/admin/mailing" ><?php echo Text::_("Communication submitted"); ?></a>.</p>

    <form action="/admin/newsletter/init" method="post" onsubmit="return confirm('This will email automatically, do you want to continue?');">

    <p>
        <label>Massive Template
            <select id="template" name="template" >
            <?php foreach ($templates as $tplId=>$tplName) : ?>
                <option value="<?php echo $tplId; ?>" <?php if ( $tplId == $tpl) echo 'selected="selected"'; ?>><?php echo $tplName; ?></option>
            <?php endforeach; ?>
            </select>
        </label>
    </p>
    <p>
        <label><input type="checkbox" name="test" value="1" checked="checked"/> It is a test (a test target is sent)</label>
    </p>
        
    <p>
        <label><input type="checkbox" name="nolang" value="1" checked="checked"/>Only in Spanish (not considering user preferred language)</label>
    </p>
        
    <p>
        <input type="submit" name="init" value="Iniciar" />
    </p>

    </form>
</div>

<?php if (!empty($list)) : ?>
<div class="widget board">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Date</th>
                <th>Subject</th>
                <th></th>
                <th></th>
                <th></th>
                <th><!-- Si no ves --></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $item) : ?>
            <tr>
                <td><a href="/admin/newsletter/detail/<?php echo $item->id; ?>">[Details]</a></td>
                <td><?php echo $item->date; ?></td>
                <td><?php echo $item->subject; ?></td>
                <td><?php echo $item->active ? '<span style="color:green;font-weight:bold;">Activo</span>' : '<span style="color:red;font-weight:bold;">Inactivo</span>'; ?></td>
                <td><?php echo $item->bloqued ? 'Bloqueado' : ''; ?></td>
                <td><a href="<?php echo $item->link; ?>" target="_blank">[Si no ves]</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
