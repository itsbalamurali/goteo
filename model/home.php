<?php

namespace Goteo\Model {

    use Goteo\Library\Check,
        Goteo\Library\Text;

    class Home extends \Goteo\Core\Model {

        public
            $item,
            $type,
            $node,
            $order;

        static public function _types() {
            return array(
                    'side' => Text::_('Laterales'),
                    'main' => Text::_('Centrales')
                );
        }

        static public function _items() {
            return array(
                     'posts' => Text::_('Entradas de blog'),
                     'promotes' => Text::_('Proyectos destacados'),
//callsys                     'drops' => Text::_('Capital Riego'),
                     'feed' => Text::_('Recent Activity'),
//vipsys                     'patrons' => Text::_('Padrinos')
                 );
        }

        static public function _node_items() {
            return array(
                     'posts' => Text::_('Novedades'),
                     'promotes' => Text::_('Proyectos'),
//callsys                     'calls' => Text::_('Convocatorias'),
//vipsys                     'patrons' => Text::_('Padrinos')
                 );
        }

        static public function _node_side_items() {
            return array(
                     'searcher' => Text::_('Selector proyectos'),
                     'categories' => Text::_('Categorias de proyectos'),
                     'summary' => Text::_('Resumen proyectos'),
                     'sumcalls' => Text::_('Resumen convocatorias'),
                     'sponsors' => Text::_('Patrocinadores')
                 );
        }

        static public function _admins() {
            return array(
                     'promotes' => '/admin/promote',
//callsys                     'drops' => '/admin/calls',
//callsys                     'calls' => '/admin/campaigns',
                     'posts' => '/admin/blog',
//vipsys                     'patrons' => '/admin/patron',
                     'sponsors' => '/admin/sponsors'
                 );
        }


        /*
         *  Devuelve datos de un elemento
         */
        public static function get ($item, $node = \GOTEO_NODE) {
                $query = self::query("
                    SELECT *
                    FROM    home
                    WHERE home.item = :item
                    AND home.node = :node
                    ", array(':item' => $item, ':node'=>$node));
                $home = $query->fetchObject(__CLASS__);

                return $home;
        }

        /*
         * Devuelve elementos en portada
         */
		public static function getAll ($node = \GOTEO_NODE) {
            $array = array();
            $values = array(':node'=>$node);
            $sql = "SELECT
                        home.item as item,
                        home.node as node,
                        home.order as `order`
                    FROM home
                    WHERE home.node = :node
                    AND (type = 'main' OR type IS NULL)
                    ORDER BY `order` ASC
                    ";

            $query = self::query($sql, $values);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $home) {
                $array[$home->item] = $home;
            }
            return $array;
		}

        /*
         * Devuelve elementos laterales de portada para nodos
         */
		public static function getAllSide ($node = \GOTEO_NODE) {
            $array = array();
            $values = array(':node'=>$node);
            $sql = "SELECT
                        home.item as item,
                        home.node as node,
                        home.order as `order`
                    FROM home
                    WHERE home.node = :node
                    AND type = 'side'
                    ORDER BY `order` ASC
                    ";

            $query = self::query($sql, $values);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $home) {
                $array[$home->item] = $home;
            }
            return $array;
		}

        public function validate (&$errors = array()) { 
            if (empty($this->item))
                $errors[] = Text::_('Falta elemento');

            if (empty($this->node))
                $errors[] = Text::_('Falta nodo');

            if (empty($this->type))
                $this->type = 'main';

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'item',
                'type',
                'node',
                'order'
                );

            $set = '';
            $values = array();

            foreach ($fields as $field) {
                if ($set != '') $set .= ", ";
                $set .= "`$field` = :$field ";
                $values[":$field"] = $this->$field;
            }

            try {
                $sql = "REPLACE INTO home SET " . $set;
                self::query($sql, $values);

                $extra = array(
                    'node' => $this->node,
                    'type' => $this->type
                );
                Check::reorder($this->item, $this->move, 'home', 'item', 'order', $extra);

                return true;
            } catch(\PDOException $e) {
                $errors[] = "HA FALLADO!!! " . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar un elemento
         */
        public static function delete ($item, $node = \GOTEO_NODE, $type = 'main') {
            
            $sql = "DELETE FROM home WHERE item = :item AND node = :node AND type = :type";
            if (self::query($sql, array(':item'=>$item, ':node'=>$node, ':type'=>$type))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que un elemento salga antes  (disminuir el order)
         */
        public static function up ($item, $node = \GOTEO_NODE, $type = 'main') {
            $extra = array(
                'node' => $node,
                'type' => $type
            );
            return Check::reorder($item, 'up', 'home', 'item', 'order', $extra);
        }

        /*
         * Para que un elemento salga despues  (aumentar el order)
         */
        public static function down ($item, $node = \GOTEO_NODE, $type = 'main') {
            $extra = array(
                'node' => $node,
                'type' => $type
            );
            return Check::reorder($item, 'down', 'home', 'item', 'order', $extra);
        }

        /*
         * Orden para añadirlo al final
         */
        public static function next ($node = \GOTEO_NODE, $type = 'main') {
            $query = self::query('SELECT MAX(`order`) FROM home WHERE node = :node AND type = :type'
                , array(':node'=>$node, ':type'=>$type));
            $order = $query->fetchColumn(0);
            return ++$order;

        }

        /*
         * Elementos disponibles apra portada
         */
		public static function available ($node = \GOTEO_NODE) {
            if ($node == \GOTEO_NODE) {
                $array = static::_items();
            } else {
                $array = static::_node_items();
            }
            $values = array(':node'=>$node);
            $sql = "SELECT
                        home.item as item
                    FROM home
                    WHERE home.node = :node
                    AND (type = 'main' OR type IS NULL)
                    ";

            $query = self::query($sql, $values);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $used) {
                unset($array[$used->item]);
            }
            return $array;
		}

        /*
         * Elementos disponibles apra portada
         */
		public static function availableSide ($node = \GOTEO_NODE) {
            if ($node == \GOTEO_NODE) {
                $array = array();
            } else {
                $array = static::_node_side_items();
            }
            $values = array(':node'=>$node);
            $sql = "SELECT
                        home.item as item
                    FROM home
                    WHERE home.node = :node
                    AND type = 'side'
                    ";

            $query = self::query($sql, $values);
            foreach ( $query->fetchAll(\PDO::FETCH_CLASS) as $used) {
                unset($array[$used->item]);
            }
            return $array;
		}

    }
    
}