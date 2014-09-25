<?php

namespace Goteo\Controller\Admin {

    use Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Core\Error,
		Goteo\Library\Message,
		Goteo\Library\Feed,
        Goteo\Model;

    class Home {

        public static function process ($action = 'list', $id = null, $filters = array(), $type = 'main') {

            //@NODESYS
            $node = \GOTEO_NODE;
            $type = 'main';

            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // instancia
                $item = new Model\Home(array(
                    'item' => $_POST['item'],
                    'type' => $_POST['type'],
                    'node' => $node,
                    'order' => $_POST['order'],
                    'move' => 'down'
                ));

				if ($item->save($errors)) {
				} else {
                    Message::Error(implode('<br />', $errors));
                }
			}


            switch ($action) {
                case 'remove':
                    Model\Home::delete($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'up':
                    Model\Home::up($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'down':
                    Model\Home::down($id, $node, $type);
                    throw new Redirection('/admin/home');
                    break;
                case 'add':
                    $next = Model\Home::next($node, 'main');
                    $availables = Model\Home::available($node);

                    if (empty($availables)) {
                        Message::Info(Text::_('All items are already available on the cover'));
                        throw new Redirection('/admin/home');
                        break;
                    }
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'home',
                            'file' => 'add',
                            'action' => 'add',
                            'home' => (object) array('node'=>$node, 'order'=>$next, 'type'=>'main'),
                            'availables' => $availables
                        )
                    );
                    break;
                case 'addside':
                    $next = Model\Home::next($node, 'side');
                    $availables = Model\Home::availableSide($node);

                    if (empty($availables)) {
                        Message::Info(Text::_('All side elements are already available on the cover'));
                        throw new Redirection('/admin/home');
                        break;
                    }
                    return new View(
                        'view/admin/index.html.php',
                        array(
                            'folder' => 'home',
                            'file' => 'add',
                            'action' => 'add',
                            'home' => (object) array('node'=>$node, 'order'=>$next, 'type'=>'side'),
                            'availables' => $availables
                        )
                    );
                    break;
            }

            $viewData = array(
                'folder' => 'home',
                'file' => 'list'
            );

            $viewData['items'] = Model\Home::getAll($node);

            /* Para añadir nuevos desde la lista */
            $viewData['availables'] = Model\Home::available($node);
            $viewData['new'] = (object) array('node'=>$node, 'order'=>Model\Home::next($node, 'main'), 'type'=>'main');

            // laterales
            $viewData['side_items'] = Model\Home::getAllSide($node);
            $viewData['side_availables'] = Model\Home::availableSide($node);
            $viewData['side_new'] = (object) array('node'=>$node, 'order'=>Model\Home::next($node, 'side'), 'type'=>'side');

            return new View('view/admin/index.html.php', $viewData);
            
        }

    }

}
