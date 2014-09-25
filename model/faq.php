<?php


namespace Goteo\Model {

    use Goteo\Library\Check,
        Goteo\Library\Text;

    class Faq extends \Goteo\Core\Model {

        public
            $id,
            $node,
            $section,
            $title,
            $description,
            $order;

        /*
         *  Devuelve datos de un destacado
         */
        public static function get ($id) {
                $query = static::query("
                    SELECT
                        faq.id as id,
                        faq.node as node,
                        faq.section as section,
                        IFNULL(faq_lang.title, faq.title) as title,
                        IFNULL(faq_lang.description, faq.description) as description,
                        faq.order as `order`
                    FROM faq
                    LEFT JOIN faq_lang
                        ON  faq_lang.id = faq.id
                        AND faq_lang.lang = :lang
                    WHERE faq.id = :id
                    ", array(':id' => $id, ':lang'=>\LANG));
                $faq = $query->fetchObject(__CLASS__);

                return $faq;
        }

        /*
         * Lista de proyectos destacados
         */
        public static function getAll ($section = 'node') {

            $values = array(':section' => $section, ':lang' => \LANG);

            // piñonaco para sacar alternativos mientras traducen
            switch (\LANG) {
                case 'es':
                    // en español, sacar directamente el original
                    $sql = "
                        SELECT
                            faq.id as id,
                            faq.node as node,
                            faq.section as section,
                            faq.title as title,
                            faq.description as description,
                            faq.order as `order`
                        FROM faq
                        WHERE faq.section = :section
                        ORDER BY `order` ASC
                        ";

                    unset($values[':lang']);
                    
                    break;

                case 'en':
                    // en inglés hay menos preguntas, no sacar original
                    $sql = "
                        SELECT
                            faq.id as id,
                            faq.node as node,
                            faq.section as section,
                            faq_lang.title as title,
                            faq_lang.description as description,
                            faq.order as `order`
                        FROM faq
                        LEFT JOIN faq_lang
                            ON  faq_lang.id = faq.id
                            AND faq_lang.lang = :lang
                        WHERE faq.section = :section
                        ORDER BY `order` ASC
                        ";

                    break;

                case 'fr':
                    // en francés, sacar el ingles como original
                    $sql = "
                        SELECT
                            faq.id as id,
                            faq.node as node,
                            faq.section as section,
                            IFNULL(faq_lang.title, faq_en.title) as title,
                            IFNULL(faq_lang.description, faq_en.description) as description,
                            faq.order as `order`
                        FROM faq
                        LEFT JOIN faq_lang
                            ON  faq_lang.id = faq.id
                            AND faq_lang.lang = :lang
                        LEFT JOIN faq_lang as faq_en
                            ON  faq_en.id = faq.id
                            AND faq_en.lang = 'en'
                        WHERE faq.section = :section
                        ORDER BY `order` ASC
                        ";

                    break;

                default:
                    // en catalan y por defecto, sacar los originales en español
                    $sql = "
                        SELECT
                            faq.id as id,
                            faq.node as node,
                            faq.section as section,
                            IFNULL(faq_lang.title, faq.title) as title,
                            IFNULL(faq_lang.description, faq.description) as description,
                            faq.order as `order`
                        FROM faq
                        LEFT JOIN faq_lang
                            ON  faq_lang.id = faq.id
                            AND faq_lang.lang = :lang
                        WHERE faq.section = :section
                        ORDER BY `order` ASC
                        ";
                    
                    break;
            }
            
            $query = static::query($sql, $values);
            
            return $query->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }

        public function validate (&$errors = array()) { 
            if (empty($this->node))
                $errors[] = Text::_('Falta nodo');

            if (empty($this->section))
                $errors[] = Text::_('Falta sección');

            if (empty($this->title))
                $errors[] = Text::_('Falta título');

            if (empty($errors))
                return true;
            else
                return false;
        }

        public function save (&$errors = array()) {
            if (!$this->validate($errors)) return false;

            $fields = array(
                'id',
                'node',
                'section',
                'title',
                'description',
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
                $sql = "REPLACE INTO faq SET " . $set;
                self::query($sql, $values);
                if (empty($this->id)) $this->id = self::insertId();

                $extra = array(
                    'section' => $this->section,
                    'node' => $this->node
                );
                Check::reorder($this->id, $this->move, 'faq', 'id', 'order', $extra);

                return true;
            } catch(\PDOException $e) {
                $errors[] = Text::_('No se ha guardado correctamente. ') . $e->getMessage();
                return false;
            }
        }

        /*
         * Para quitar una pregunta
         */
        public static function delete ($id, $node = \GOTEO_NODE) {
            
            $sql = "DELETE FROM faq WHERE id = :id AND node = :node";
            if (self::query($sql, array(':id'=>$id, ':node'=>$node))) {
                return true;
            } else {
                return false;
            }

        }

        /*
         * Para que una pregunta salga antes  (disminuir el order)
         */
        public static function up ($id, $node = \GOTEO_NODE) {
            $query = static::query("SELECT section FROM faq WHERE id = ?", array($id));
            $faq = $query->fetchObject();
            $extra = array(
                'section' => $faq->section,
                'node' => $node
            );
            return Check::reorder($id, 'up', 'faq', 'id', 'order', $extra);
        }

        /*
         * Para que un proyecto salga despues  (aumentar el order)
         */
        public static function down ($id, $node = \GOTEO_NODE) {
            $query = static::query("SELECT section FROM faq WHERE id = ?", array($id));
            $faq = $query->fetchObject();
            $extra = array(
                'section' => $faq->section,
                'node' => $node
            );
            return Check::reorder($id, 'down', 'faq', 'id', 'order', $extra);
        }

        /*
         * Orden para añadirlo al final
         */
        public static function next ($section = 'node', $node = \GOTEO_NODE) {
            $query = self::query('SELECT MAX(`order`) FROM faq WHERE section = :section AND node = :node'
                , array(':section'=>$section, ':node'=>$node));
            $order = $query->fetchColumn(0);
            return ++$order;

        }

        public static function sections () {
            return array(
                'node' => Text::get('faq-main-section-header'),
                'project' => Text::get('faq-project-section-header'),
                'sponsor' => Text::get('faq-sponsor-section-header'),
                'investors' => Text::get('faq-investors-section-header'),
                'nodes' => Text::get('faq-nodes-section-header')
            );
        }

        public static function colors () {
            return array(
                'node' => '#808285',
                'project' => '#20b3b2',
                'sponsor' => '#96238f',
                'investors' => '#0c4e99',
                'nodes' => '#8f8f8f'
            );
        }


    }
    
}