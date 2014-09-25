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

namespace Goteo\Controller\Admin {

    use Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Core\Error,
		Goteo\Library\Text,
		Goteo\Library\Feed,
		Goteo\Library\Message,
        Goteo\Model;

    class Sponsors {

        public static function process ($action = 'list', $id = null) {

            $node = isset($_SESSION['admin_node']) ? $_SESSION['admin_node'] : \GOTEO_NODE;

            $model = 'Goteo\Model\Sponsor';
            $url = '/admin/sponsors';

            $errors = array();

            switch ($action) {
                case 'add':
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => (object) array('order' => $model::next($node), 'node' => $node ),
                            'form' => array(
                                'action' => "$url/edit/",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::_('Add')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'node' => array(
                                        'label' => '',
                                        'name' => 'node',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Sponsor'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('Link'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'image' => array(
                                        'label' => Text::_('Logo'),
                                        'name' => 'image',
                                        'type' => 'image'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Position'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'edit':

                    // gestionar post
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        // instancia
                        $item = new $model(array(
                            'id' => $_POST['id'],
                            'name' => $_POST['name'],
                            'node' => $_POST['node'],
                            'image' => $_POST['image'],
                            'url' => $_POST['url'],
                            'order' => $_POST['order']
                        ));

                        // tratar si quitan la imagen
                        $current = $_POST['image']; // la actual
                        if (isset($_POST['image-' . $current .  '-remove'])) {
                            $image = Model\Image::get($current);
                            $image->remove('sponsor');
                            $item->image = '';
                            $removed = true;
                        }

                        // tratar la imagen y ponerla en la propiedad image
                        if(!empty($_FILES['image']['name'])) {
                            $item->image = $_FILES['image'];
                        }

                        if ($item->save($errors)) {
                            Message::Info(Text::_('Information was saved correctly'));
                            throw new Redirection($url);
                        } else {
                            Message::Error(Text::_('was not correctly saved') . implode(', ', $errors));
                        }
                    } else {
                        $item = $model::get($id);
                    }

                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'base',
                            'file' => 'edit',
                            'data' => $item,
                            'form' => array(
                                'action' => "$url/edit/$id",
                                'submit' => array(
                                    'name' => 'update',
                                    'label' => Text::get('regular-save')
                                ),
                                'fields' => array (
                                    'id' => array(
                                        'label' => '',
                                        'name' => 'id',
                                        'type' => 'hidden'

                                    ),
                                    'node' => array(
                                        'label' => '',
                                        'name' => 'node',
                                        'type' => 'hidden'

                                    ),
                                    'name' => array(
                                        'label' => Text::_('Sponsor'),
                                        'name' => 'name',
                                        'type' => 'text'
                                    ),
                                    'url' => array(
                                        'label' => Text::_('link'),
                                        'name' => 'url',
                                        'type' => 'text',
                                        'properties' => 'size=100'
                                    ),
                                    'image' => array(
                                        'label' => Text::_('Logo'),
                                        'name' => 'image',
                                        'type' => 'image'
                                    ),
                                    'order' => array(
                                        'label' => Text::_('Position'),
                                        'name' => 'order',
                                        'type' => 'text'
                                    )
                                )

                            )
                        )
                    );

                    break;
                case 'up':
                    $model::up($id, $node);
                    throw new Redirection($url);
                    break;
                case 'down':
                    $model::down($id, $node);
                    throw new Redirection($url);
                    break;
                case 'remove':
                    if ($model::delete($id)) {
                        Message::Info(Text::_('The record has been removed'));
                        throw new Redirection($url);
                    } else {
                        Message::Info(Text::_('Is has not been possible to delete the record'));
                    }
                    break;
            }

            return new View(
                'view/admin/index.html.php',
                array(
                    'folder' => 'base',
                    'file' => 'list',
                    'addbutton' => Text::_('New sponsor'),
                    'data' => $model::getAll($node),
                    'columns' => array(
                        'edit' => '',
                        'name' => Text::_('Sponsor'),
                        'url' => Text::_('Link'),
                        'image' => Text::_('image'),
                        'order' => Text::_('Position'),
                        'up' => '',
                        'down' => '',
                        'remove' => ''
                    ),
                    'url' => "$url"
                )
            );
            
        }

    }

}
