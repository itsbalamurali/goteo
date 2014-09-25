<?php

namespace Goteo\Controller {

    use Goteo\Library\Page,
        Goteo\Core\Redirection,
        Goteo\Core\View,
        Goteo\Model,
        Goteo\Library\Text,
        Goteo\Library\Mail,
        Goteo\Library\Template;

    class About extends \Goteo\Core\Controller {
        
        public function index ($id = null) {

            // si llegan a la de mantenimiento sin estar en mantenimiento
            if ($id == 'maintenance' && GOTEO_MAINTENANCE !== true) {
                $id = 'credits';
            }

            // paginas especificas
            if ($id == 'faq' || $id == 'contact') {
                throw new Redirection('/'.$id, Redirection::TEMPORARY);
            }

            // en estos casos se usa el contenido de goteo
            if ($id == 'howto') {
                if (!$_SESSION['user'] instanceof Model\User) {
                    throw new Redirection('/');
                }
                $page = Page::get($id);
                return new View(
                    'view/about/howto.html.php',
                    array(
                        'name' => $page->name,
                        'description' => $page->description,
                        'content' => $page->content
                    )
                 );
            }

            // el tipo de contenido de la pagina about es diferente
            if ( empty($id) ||
                 $id == 'about'
                ) {
                $id = 'about';

                    $posts = Model\Info::getAll(true, \GOTEO_NODE);

                    return new View(
                        'view/about/info.html.php',
                        array(
                            'posts' => $posts
                        )
                     );


            }

            // resto de casos
            $page = Page::get($id);

            return new View(
                'view/about/sample.html.php',
                array(
                    'name' => $page->name,
                    'description' => $page->description,
                    'content' => $page->content
                )
             );

        }
        
    }
    
}