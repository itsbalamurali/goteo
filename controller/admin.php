<?php

namespace Goteo\Controller {

    use Goteo\Core\ACL,
        Goteo\Core\View,
        Goteo\Core\Redirection,
        Goteo\Model,
        Goteo\Library\Text,
        Goteo\Library\Feed,
        Goteo\Library\i18n\Lang,
        Goteo\Library\Page,
        Goteo\Library\Mail,
        Goteo\Library\Template,
        Goteo\Library\Message,
        Goteo\Library\Newsletter,
        Goteo\Library\Worth;

    class Admin extends \Goteo\Core\Controller {

        // Array de usuarios con permisos especiales
        static public function _supervisors() {
            return array(
                'supervisor' => array(
                    // paneles de admin permitidos
                    'texts',
                    'faq',
                    'pages',
                    'licenses',
                    'icons',
                    'tags',
                    'criteria',
                    'templates',
                    'glossary'
                )
            );
        }

        // Array de los gestores que existen
        static public function _options() {

            return array(
                'accounts' => array(
                    'label' => Text::_('Contribution administration'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'details' => array('label' => Text::_('Contribution details'), 'item' => true),
                        'update' => array('label' => Text::_('Contribution details'), 'item' => true),
                        'add' => array('label' => Text::_('Manual contribution'), 'item' => false),
                        'move' => array('label' => Text::_('Relocating the contribution'), 'item' => true),
                        'execute' => array('label' => Text::_('Realisation of charge'), 'item' => true),
                        'cancel' => array('label' => Text::_('Cancelling contribution'), 'item' => true),
                        'report' => array('label' => Text::_('Project report'), 'item' => true),
                        'viewer' => array('label' => Text::_('Viewing logs'), 'item' => false)
                    ),
                    'filters' => array('id' => '', 'methods' => '', 'investStatus' => 'all', 'projects' => '', 'name' => '', 'calls' => '', 'review' => '', 'types' => '', 'date_from' => '', 'date_until' => '', 'issue' => 'all', 'procStatus' => 'all', 'amount' => '')
                ),
                'banners' => array(
                    'label' => Text::_('Banners'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Banner'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Banner'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Banner'), 'item' => true)
                    )
                ),
                'blog' => array(
                    'label' => Text::_('Blog'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Entry'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Entry'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Entry'), 'item' => true),
                        'reorder' => array('label' => Text::_('Placing Entry on Front Page'), 'item' => false),
                        'footer' => array('label' => Text::_('Placing Entry in Footer'), 'item' => false)
                    ),
                    'filters' => array('show' => 'owned', 'blog' => '')
                ),
                'categories' => array(
                    'label' => Text::_('Categories'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Category'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Category'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Category'), 'item' => true),
                        'keywords' => array('label' => Text::_('Keywords'), 'item' => false)
                    )
                ),
                'criteria' => array(
                    'label' => Text::_('Review criteria'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Criteria'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Criteria'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Criteria'), 'item' => true)
                    ),
                    'filters' => array('section' => 'project')
                ),
                'faq' => array(
                    'label' => Text::_('FAQs'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Question'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Question'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Question'), 'item' => true)
                    ),
                    'filters' => array('section' => 'node')
                ),
            'home' => array(
                'label' => Text::_('Featured on frontpage'),
                'actions' => array(
                    'list' => array('label' => Text::_('Manage'), 'item' => false)
                )
            ),
                'glossary' => array(
                    'label' => Text::_('Glossary'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Term'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Term'), 'item' => true)
                    )
                ),
                'icons' => array(
                    'label' => Text::_('Types of Return'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editing Type'), 'item' => true),
                        'translate' => array('label' => Text::_('Translating Type'), 'item' => true)
                    ),
                    'filters' => array('group' => '')
                ),
                'invests' => array(
                    'label' => Text::_('Contributions'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'details' => array('label' => Text::_('Contribution details'), 'item' => true)
                    ),
                    'filters' => array('methods' => '', 'status' => 'all', 'investStatus' => 'all', 'projects' => '', 'name' => '', 'calls' => '', 'types' => '')
                ),
                'licenses' => array(
                    'label' => Text::_('Licenses'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Translating License'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Licencia'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'icon' => '')
                ),
                'mailing' => array(
                    'label' => Text::_('Communication submitted'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Selecting recipients'), 'item' => false),
                        'edit' => array('label' => Text::_('Writing Content'), 'item' => false),
                        'send' => array('label' => Text::_('Communication submitted'), 'item' => false)
                    ),
                    'filters' => array('project' => '', 'type' => '', 'status' => '-1', 'method' => '', 'interest' => '', 'role' => '', 'name' => '', 'donant' => '',
                    )
                ),
                'news' => array(
                    'label' => Text::_('Micronews'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Nueva Micronoticia'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Micronoticia'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Micronoticia'), 'item' => true)
                    )
            ),
            'newsletter' => array(
                'label' => _('Boletín'),
                'actions' => array(
                    'list' => array('label' => Text::_('Estado del envío automático'), 'item' => false),
                    'init' => array('label' => Text::_('Iniciando un nuevo boletín'), 'item' => false),
                    'init' => array('label' => Text::_('Viendo listado completo'), 'item' => true)
                )
                ),
                'pages' => array(
                    'label' => Text::_('Páginas'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Página'), 'item' => true),
                        'add' => array('label' => Text::_('Nueva Página'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Página'), 'item' => true)
                    )
                ),
                'projects' => array(
                    'label' => Text::_('Gestión de proyectos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'dates' => array('label' => Text::_('Fechas del proyecto'), 'item' => true),
                        'accounts' => array('label' => Text::_('Cuentas del proyecto'), 'item' => true),
                        'images' => array('label' => Text::_('Imágenes del proyecto'), 'item' => true),
                        'move' => array('label' => Text::_('Moviendo a otro Nodo el proyecto'), 'item' => true),
                        'assign' => array('label' => Text::_('Asignando a una Convocatoria el proyecto'), 'item' => true),
                        'report' => array('label' => Text::_('Informe Financiero del proyecto'), 'item' => true),
                        'rebase' => array('label' => Text::_('Cambiando Id de proyecto'), 'item' => true)
                    ),
                    'filters' => array('status' => '-1', 'category' => '', 'proj_name' => '', 'name' => '', 'node' => '', 'called' => '', 'order' => '')
                ),
                'promote' => array(
                    'label' => Text::_('Proyectos destacados'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('New Featured'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Destacado'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Destacado'), 'item' => true)
                    )
                ),
                'recent' => array(
                    'label' => Text::_('Recent Activity'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false)
                    )
                ),
                'reviews' => array(
                    'label' => Text::_('Revisions'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Iniciando briefing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando briefing'), 'item' => true),
                        'report' => array('label' => Text::_('Informe'), 'item' => true)
                    ),
                    'filters' => array('project' => '', 'status' => 'open', 'checker' => '')
                ),
                'rewards' => array(
                    'label' => Text::_('Rewards'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Managing Rewards'), 'item' => true)
                    ),
                    'filters' => array('project' => '', 'name' => '', 'status' => '')
                ),
                'sended' => array(
                    'label' => Text::_('Historial envíos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Emails enviados'), 'item' => false)
                    ),
                    'filters' => array('user' => '', 'template' => '', 'node' => '', 'date_from' => '', 'date_until' => '')
                ),
                'sponsors' => array(
                    'label' => Text::_('Apoyos institucionales'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Patrocinador'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Patrocinador'), 'item' => true)
                    )
                ),
                'tags' => array(
                    'label' => Text::_('Tags de blog'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Nuevo Tag'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Tag'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Tag'), 'item' => true)
                    )
                ),
                'templates' => array(
                    'label' => Text::_('Plantillas de email'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Plantilla'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Plantilla'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'name' => '')
                ),
                'texts' => array(
                    'label' => Text::_('Interface texts'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Original'), 'item' => true),
                        'translate' => array('label' => Text::_('Traduciendo Texto'), 'item' => true)
                    ),
                    'filters' => array('group' => '', 'text' => '')
                ),
                'translates' => array(
                    'label' => Text::_('Traducciones de proyectos'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Habilitando traducción'), 'item' => false),
                        'edit' => array('label' => Text::_('Asignando traducción'), 'item' => true)
                    ),
                    'filters' => array('owner' => '', 'translator' => '')
                ),
                'users' => array(
                    'label' => Text::_('Gestión de usuarios'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'add' => array('label' => Text::_('Creando Usuario'), 'item' => true),
                        'edit' => array('label' => Text::_('Editando Usuario'), 'item' => true),
                        'manage' => array('label' => Text::_('Managing User'), 'item' => true),
                        'impersonate' => array('label' => Text::_('Suplantando al Usuario'), 'item' => true),
                        'move' => array('label' => Text::_('Moviendo a otro Nodo el usuario '), 'item' => true)
                    ),
                    'filters' => array('interest' => '', 'role' => '', 'node' => '', 'id' => '', 'name' => '', 'order' => '', 'project' => '', 'type' => '')
                ),
                'worth' => array(
                    'label' => Text::_('Niveles de meritocracia'),
                    'actions' => array(
                        'list' => array('label' => Text::_('Listing'), 'item' => false),
                        'edit' => array('label' => Text::_('Editando Nivel'), 'item' => true)
                    )
                )
            );
        }

        // preparado para index unificado
        public function index($option = 'index', $action = 'list', $id = null, $subaction = null) {
            if ($option == 'index') {
                $BC = self::menu(array('option' => $option, 'action' => null, 'id' => null));
                define('ADMIN_BCPATH', $BC);
                $tasks = Model\Task::getAll(array(), null, true);
                return new View('view/admin/index.html.php', array('tasks' => $tasks));
            } else {
                $BC = self::menu(array('option' => $option, 'action' => $action, 'id' => $id));
                define('ADMIN_BCPATH', $BC);
                $SubC = 'Goteo\Controller\Admin' . \chr(92) . \ucfirst($option);
                return $SubC::process($action, $id, self::setFilters($option), $subaction);
            }
        }

        // Para marcar tareas listas (solo si tiene módulo Tasks implementado)
        public function done($id) {
            $errors = array();
            if (!empty($id) && isset($_SESSION['user']->id)) {
                $task = Model\Task::get($id);
                if ($task->setDone($errors)) {
                    Message::Info('La tarea se ha marcado como realizada');
                } else {
                    Message::Error(implode('<br />', $errors));
                }
            } else {
                Message::Error('Faltan datos');
            }
            throw new Redirection('/admin');
        }


        /*
         * Menu de secciones, opciones, acciones y config para el panel Admin
         */

        public static function menu($BC = array()) {

            $admin_label = Text::_('Admin');

            $options = static::_options();
            $supervisors = static::_supervisors();

            // El menu del panel admin dependerá del rol del usuario que accede
            // Superadmin = todo
            // Admin = contenidos de Nodo
            // Supervisor = menus especiales
            if (isset($supervisors[$_SESSION['user']->id])) {
                $menu = self::setMenu('supervisor', $_SESSION['user']->id);
            } elseif (isset($_SESSION['user']->roles['admin'])) {
                $menu = self::setMenu('admin', $_SESSION['user']->id);
            } else {
                $menu = self::setMenu('superadmin', $_SESSION['user']->id);
            }

            // si el breadcrumbs no es un array vacio,
            // devolveremos el contenido html para pintar el camino de migas de pan
            // con enlaces a lo anterior
            if (empty($BC)) {
                return $menu;
            } else {

                // a ver si puede estar aqui!
                if ($BC['option'] != 'index') {
                    $puede = false;
                    foreach ($menu as $sCode => $section) {
                        if (isset($section['options'][$BC['option']])) {
                            $puede = true;
                            break;
                        }
                    }

                    if (!$puede) {
                        Message::Error(Text::get('admin-no_permission', $options[$BC['option']]['label']));
                        throw new Redirection('/admin');
                    }
                }

                // Los últimos serán los primeros
                $path = '';

                // si el BC tiene Id, accion sobre ese registro
                // si el BC tiene Action
                if (!empty($BC['action']) && $BC['action'] != 'list') {

                    // si es una accion no catalogada, mostramos la lista
                    if (!in_array($BC['action'], array_keys($options[$BC['option']]['actions']))) {
                        $BC['action'] = '';
                        $BC['id'] = null;
                    }

                    $action = $options[$BC['option']]['actions'][$BC['action']];
                    // si es de item , añadir el id (si viene)
                    if ($action['item'] && !empty($BC['id'])) {
                        $path = " &gt; <strong>{$action['label']}</strong> {$BC['id']}";
                    } else {
                        $path = " &gt; <strong>{$action['label']}</strong>";
                    }
                }

                // si el BC tiene Option, enlace a la portada de esa gestión (a menos que sea laaccion por defecto)
                if (!empty($BC['option']) && isset($options[$BC['option']])) {
                    $option = $options[$BC['option']];
                    if ($BC['action'] == 'list') {
                        $path = " &gt; <strong>{$option['label']}</strong>";
                    } else {
                        $path = ' &gt; <a href="/admin/' . $BC['option'] . '">' . $option['label'] . '</a>' . $path;
                    }
                }

                // si el BC tiene section, facil, enlace al admin
                if ($BC['option'] == 'index') {
                    $path = "<strong>{$admin_label}</strong>";
                } else {
                    $path = '<a href="/admin">' . $admin_label . '</a>' . $path;
                }

                return $path;
            }
        }

        /*
         * Si no tenemos filtros para este gestor los cogemos de la sesion
         */

        private static function setFilters($option) {

            $options = static::_options();

            // arary de fltros para el sub controlador
            $filters = array();

            if (isset($_GET['reset']) && $_GET['reset'] == 'filters') {
                unset($_SESSION['admin_filters'][$option]);
                unset($_SESSION['admin_filters']['main']);
                foreach ($options[$option]['filters'] as $field => $default) {
                    $filters[$field] = $default;
                }
                return $filters;
            }

            // si hay algun filtro
            $filtered = false;

            // filtros de este gestor:
            // para cada uno tenemos el nombre del campo y el valor por defecto
            foreach ($options[$option]['filters'] as $field => $default) {
                if (isset($_GET[$field])) {
                    // si lo tenemos en el get, aplicamos ese a la sesión y al array
                    $filters[$field] = (string) $_GET[$field];
                    $_SESSION['admin_filters'][$option][$field] = (string) $_GET[$field];
                    if (($option == 'reports' && $field == 'user')
                            || ($option == 'projects' && $field == 'user')
                            || ($option == 'users' && $field == 'name')
                            || ($option == 'accounts' && $field == 'name')
                            || ($option == 'rewards' && $field == 'name')) {

                        $_SESSION['admin_filters']['main']['user_name'] = (string) $_GET[$field];
                    }
                    $filtered = true;
                } elseif (!empty($_SESSION['admin_filters'][$option][$field])) {
                    // si no lo tenemos en el get, cogemos de la sesion pero no lo pisamos
                    $filters[$field] = $_SESSION['admin_filters'][$option][$field];
                    $filtered = true;
                } else {
                    // a ver si tenemos un filtro equivalente
                    switch ($option) {
                        case 'projects':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'users':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'accounts':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                        case 'rewards':
                            if ($field == 'name' && !empty($_SESSION['admin_filters']['main']['user_name'])) {
                                $filters['name'] = $_SESSION['admin_filters']['main']['user_name'];
                                $filtered = true;
                            }
                            break;
                    }

                    // si no tenemos en sesion, ponemos el valor por defecto
                    if (empty($filters[$field])) {
                        $filters[$field] = $default;
                    }
                }
            }

            if ($filtered) {
                $filters['filtered'] = 'yes';
            }

            return $filters;
        }

        /*
         * Diferentes menus para diferentes perfiles
         */

        public static function setMenu($role, $user = null) {

            $options = static::_options();
            $supervisors = static::_supervisors();

            $labels = array();
            $labels['contents'] = Text::_('Contenidos');
            $labels['projects'] = Text::_('Projects');
            $labels['users'] = Text::_('Usuarios');
            $labels['home'] = Text::_('Portada');
            $labels['texts'] = Text::_('Texts and Translations');
            $labels['services'] = Text::_('Servicios');

            switch ($role) {
                case 'supervisor':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['contents'],
                            'options' => array()
                        )
                    );

                    foreach ($supervisors[$user] as $opt) {
                        $menu['contents']['options'][$opt] = $options[$opt];
                    }

                    break;
                case 'admin':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['contents'],
                            'options' => array(
                                'pages' => $options['pages'], // páginas institucionales del nodo
                                'blog' => $options['blog'], // entradas del blog
                                'banners' => $options['banners']    // banners del nodo
                            )
                        ),
                        'projects' => array(
                            'label' => $labels['projects'],
                            'options' => array(
                                'projects' => $options['projects'], // proyectos del nodo
                                'reviews' => $options['reviews'], // revisiones de proyectos del nodo
                                'translates' => $options['translates'], // traducciones de proyectos del nodo
                                'invests' => $options['invests'], // Contribution administration avanzada
                            )
                        ),
                        'users' => array(
                            'label' => $labels['users'],
                            'options' => array(
                                'users' => $options['users'], // usuarios asociados al nodo
                                'mailing' => $options['mailing'], // comunicaciones del nodoc on sus usuarios / promotores
                                'sended' => $options['sended'], // historial de envios realizados por el nodo,
                                'tasks' => $options['tasks']  // gestión de tareas
                            )
                        ),
                        'home' => array(
                            'label' => $labels['home'],
                            'options' => array(
                                'home' => $options['home'], // elementos en portada
                                'promote' => $options['promote'], // seleccion de proyectos destacados
                                'blog' => $options['blog'], // entradas de blog (en la gestion de blog)
                                'sponsors' => $options['sponsors'], // patrocinadores del nodo
                                'recent' => $options['recent'] // feed admin
                            )
                        )
                    );

                    break;
                case 'superadmin':
                    $menu = array(
                        'contents' => array(
                            'label' => $labels['texts'],
                            'options' => array(
                                'blog' => $options['blog'],
                                'texts' => $options['texts'],
                                'faq' => $options['faq'],
                                'pages' => $options['pages'],
                                'categories' => $options['categories'],
                                'licenses' => $options['licenses'],
                                'icons' => $options['icons'],
                                'tags' => $options['tags'],
                                'criteria' => $options['criteria'],
                                'templates' => $options['templates'],
                                'glossary' => $options['glossary'],
                            )
                        ),
                        'projects' => array(
                            'label' => $labels['projects'],
                            'options' => array(
                                'projects' => $options['projects'],
                                'accounts' => $options['accounts'],
                                'reviews' => $options['reviews'],
                                'translates' => $options['translates'],
                                'rewards' => $options['rewards'],
                            )
                        ),
                        'users' => array(
                            'label' => $labels['users'],
                            'options' => array(
                                'users' => $options['users'],
                                'worth' => $options['worth'],
                                'mailing' => $options['mailing'],
                                'sended' => $options['sended'],
                                'tasks' => $options['tasks']
                            )
                        ),
                        'home' => array(
                            'label' => $labels['home'],
                            'options' => array(
                                'news' => $options['news'],
                                'banners' => $options['banners'],
                                'blog' => $options['blog'],
                                'promote' => $options['promote'],
                                'footer' => $options['footer'],
                                'recent' => $options['recent'],
                                'home' => $options['home']
                            )
                        ),
                        'sponsors' => array(
                            'label' => $labels['services'],
                            'options' => array(
                                'newsletter' => $options['newsletter'],
                                'sponsors' => $options['sponsors'],
                                'tasks' => $options['tasks']  // gestión de tareas
                            )
                        )
                    );
                    break;
            }

            return $menu;
        }

    }

}
