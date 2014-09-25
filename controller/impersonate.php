<?php

namespace Goteo\Controller {

	use Goteo\Core\Redirection,
        Goteo\Core\Error,
        Goteo\Core\View,
        Goteo\Library\Feed,
        Goteo\Library\Message,
		Goteo\Model\User;

	class Impersonate extends \Goteo\Core\Controller {

	    /**
	     * Suplantando al usuario
	     * @param string $id   user->id
	     */
		public function index () {

            $admin = $_SESSION['user'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST' 
                && !empty($_POST['id'])
                && !empty($_POST['impersonate'])) {

                $impersonator = $_SESSION['user']->id;

                session_unset();
                $_SESSION['user'] = User::get($_POST['id']);
                $_SESSION['impersonating'] = true;
                $_SESSION['impersonator'] = $impersonator;

                unset($_SESSION['admin_menu']);
                /*
                 * Evento Feed
                 */
                // Evento Feed
                $log = new Feed();
                $log->setTarget($_SESSION['user']->id, 'user');
                $log->populate('Suplantación usuario (admin)', '/admin/users', \vsprintf('El admin %s ha %s al usuario %s', array(
                    Feed::item('user', $admin->name, $admin->id),
                    Feed::item('relevant', 'Suplantado'),
                    Feed::item('user', $_SESSION['user']->name, $_SESSION['user']->id)
                )));
                $log->doAdmin('user');
                unset($log);


                throw new Redirection('/dashboard');
                
            }
            else {
                Message::Error('Ha ocurrido un error');
                throw new Redirection('/dashboard');
            }
		}

    }

}