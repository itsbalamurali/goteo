<?php

namespace Goteo\Library {

	use Goteo\Core\Model,
        Goteo\Core\Exception;

	/*
	 * Clase para gestionar la traducción de registros de tablas de content
     *
     * Ojo, todos los campos de traduccion son texto (a ver como sabemos si corto o largo...)
     *
	 */
    class Content {

        public static function _tables() {
         return array(
                'promote'   => Text::_('Featured Projects'),
                'patron'    => Text::_('Sponsored Projects'),
                'icon'      => Text::_('altered the type of return/reward (admin)'),
                'license'   => Text::_('Licenses'),
                'category'  => Text::_('Categories'),
                'news'      => Text::_('News'),
                'faq'       => Text::_('Faq'),
                'post'      => Text::_('Blog'),
                'banner'    => Text::_('Banners'),
                'tag'       => Text::_('Tags'),
                'criteria'  => Text::_('Review criteria'),
                'worthcracy'=> Text::_('Meritocracy'),
                'template'  => Text::_('Automatic email templates'),
                'glossary'  => Text::_('Glossary of Terms'),
                'info'      => Text::_('Ideas about')
            );
        }

        public static function _fields() {

            return array(
                'banner' => array (
                    'title' => Text::_('Title'),
                    'description' => Text::_('Description')
                ),
                'promote' => array (
                    'title' => Text::_('Title'),
                    'description' => Text::_('Description')
                ),
                'patron' => array (
                    'title' => Text::_('Title'),
                    'description' => Text::_('Description')
                ),
                'icon' => array (
                    'name' => Text::_('Name'),
                    'description' => Text::_('Description')
                ),
                'license' => array (
                    'name' => Text::_('Name'),
                    'description' => Text::_('Description'),
                    'url' => 'Link'
                ),
                'category' => array (
                    'name' => Text::_('Name'),
                    'description' => Text::_('Description')
                ),
                'news' => array (
                    'title' => Text::_('Title'),
                    'description' => Text::_('Excerpt')
                ),
                'faq' => array (
                    'title' => Text::_('Title'),
                    'description' => Text::_('Description')
                ),
                'post' => array (
                    'title' => Text::_('Title'),
                    'text' => Text::_('Text entered'),
                    'legend' => Text::_('Middle legend')
                ),
                'tag' => array (
                    'name' => Text::_('Name')
                ),
                'criteria' => array (
                    'title' => Text::_('Title')
                ),
                'worthcracy' => array (
                    'name' => Text::_('Name')
                ),
                'template' => array (
                    'title' => Text::_('Title'),
                    'text' => Text::_('Content')
                ),
                'glossary' => array (
                    'title' => Text::_('Title'),
                    'text' => Text::_('Content'),
                    'legend' => Text::_('Middle legend')
                ),
                'info' => array (
                    'title' => Text::_('Title'),
                    'text' => Text::_('Content'),
                    'legend' => Text::_('Middle legend')
                )
            );
        }

        public static function _types() {
            return array(
                'description' => Text::_('Description'),
                'url'         => Text::_('Link'),
                'name'        => Text::_('Name'),
                'text'        => Text::_('Extended Text'),
                'legend'      => Text::_('Legend'),
                'title'       => Text::_('Title')
            );
        }

        /*
         * Para sacar un registro
         */
        static public function get ($table, $id, $lang = 'original') {

            $fields = static::_fields();

            // buscamos el content para este registro de esta tabla
			$sql = "SELECT  
                        {$table}.id as id,
                        ";

            foreach ($fields[$table] as $field=>$fieldName) {
                $sql .= "IFNULL({$table}_lang.$field, {$table}.$field) as $field,
                         {$table}.$field as original_$field,
                        ";
            }

            $sql .= "IFNULL({$table}_lang.lang, '$lang') as lang
                     FROM {$table}
                     LEFT JOIN {$table}_lang
                        ON {$table}_lang.id = {$table}.id
                        AND {$table}_lang.lang = :lang
                     WHERE {$table}.id = :id
                ";

			$query = Model::query($sql, array(
                                            ':id' => $id,
                                            ':lang' => $lang
                                        )
                                    );
			$content = $query->fetchObject(__CLASS__);
            $content->table = $table;
            
            return $content;
		}

		/*
		 *  Metodo para la lista de registros de las tablas de contents
		 */
		public static function getAll($filters = array(), $lang = 'original') {

            $tables = static::_tables();
            $fields = static::_fields();

            $contents = array(
                'ready' => array(),
                'pending' => array()
            );

            /// filters:  type  //tipo de campo
            //          , table //tabla o modelo o concepto
            //          , text //cadena de texto

            // si hay filtro de tabla solo sacamos de una tabla

            // si hay filtro de tipo, solo las tablas que tengan ese tipo y solo ese tipo en los resultados

            // si hay filtro de texto es para todas las sentencias

            // y todos los campos sacan el content "purpose" si no tienen del suyo

            try {

                \asort($tables);
                
                foreach ($tables as $table=>$tableName) {
                    if (!self::checkLangTable($table)) continue;
                    if (!empty($filters['type']) && !isset($fields[$table][$filters['type']])) continue;
                    if (!empty($filters['table']) && $table != $filters['table']) continue;

                    $sql = "";
                    $primercampo = "";
                    $values = array();

                    $sql .= "SELECT
                                {$table}.id as id,
                                ";

                    foreach ($fields[$table] as $field=>$fieldName) {
                        $sql .= "IFNULL({$table}_lang.$field, {$table}.$field) as $field,
                                IF({$table}_lang.$field IS NULL, 0, 1) as {$field}ready,
                                ";
                        $primercampo = ($primercampo == '') ?: "{$field}ready";
                    }

                    $sql .= "CONCAT('{$table}') as `table`
                            ";

                    $sql .= "FROM {$table}
                             LEFT JOIN {$table}_lang
                                ON {$table}_lang.id = {$table}.id
                                AND {$table}_lang.lang = '$lang'
                             WHERE {$table}.id IS NOT NULL
                        ";

                        // solo entradas de goteo en esta gestión
                        if ($table == 'post') {
                            $sql .= "AND post.blog = 1
                                ";
                        }
                        if ($table == 'info') {
                            $sql .= "AND info.node = '".\GOTEO_NODE."'
                                ";
                        }

                    // para cada campo
                        $and = "AND";
                    if (!empty($filters['text'])) {
                        foreach ($fields[$table] as $field=>$fieldName) {
                            $sql .= " $and ( {$table}_lang.{$field} LIKE :text{$field} OR ({$table}_lang.{$field} IS NULL AND {$table}.{$field} LIKE :text{$field} ))
                                ";
                            $values[":text{$field}"] = "%{$filters['text']}%";
                            $and = "OR";
                        }
                    }

                    // ojo originales vacios
                    foreach ($fields[$table] as $field=>$fieldName) {
                        $sql .= " AND {$table}.{$field} IS NOT NULL
                            ";
                    }

                    // pendientes de traducir
                    if (!empty($filters['pending'])) {
                        $sql .= " HAVING $primercampo = 0";
                    }

                    $sql .= " ORDER BY id ASC";

                    /*
                    echo $sql . '<br /><br />';
                    var_dump($values);
                    echo '<br /><br />';
                     *
                     */
                    
                    $query = Model::query($sql, $values);
                    foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $content) {

                        foreach ($fields[$table] as $field=>$fieldName) {
                            if (!empty($filters['type']) && $field != $filters['type']) continue;

                            $data = array(
                                'table' => $table,
                                'tableName' => $tableName,
                                'id' => $content->id,
                                'field' => $field,
                                'fieldName' => $fieldName,
                                'value' => $content->$field
                            );

                            $campoready = $field . 'ready';

                            $group = $content->$campoready == 1 ? 'ready' : 'pending';

                            $contents[$group][] = (object) $data;

                        }

                    }

                }

                return $contents;
            } catch (\PDOException $e) {
                throw new Exception('FATAL ERROR SQL: ' . $e->getMessage() . "<br />$sql<br /><pre>" . print_r($values, 1) . "</pre>");
            }
		}

        public function validate(&$errors = array()) {
            return true;
        }

		/*
		 *  Esto se usa para actualizar datos en cualquier tabla de content
		 */
		public static function save($data, &$errors = array()) {

            $fields = static::_fields();

            if (empty($data)) {
                $errors[] = "Sin datos";
                return false;
            }
            if (empty($data['lang']) || $data['lang'] == 'original') {
                $errors[] = "No se peude traducir el content original, seleccionar un idioma para traducir";
                return false;
            }

  			try {
                // tenemos el id en $this->id  (el campo id siempre se llama id)
                // tenemos el lang en $this->lang
                // tenemos el Name de la tabla en $this->table
                // tenemos los campos en $fields[$table] y el content de cada uno en $this->$field

                $set = '`id` = :id, `lang` = :lang ';
                $values = array(
                    ':id' => $data['id'],
                    ':lang' => $data['lang']
                );

                foreach ($fields[$data['table']] as $field=>$fieldDesc) {
                    if ($set != '') $set .= ", ";
                    $set .= "`$field` = :$field ";
                    $values[":$field"] = $data[$field];
                }

				$sql = "REPLACE INTO {$data['table']}_lang SET $set";
				if (Model::query($sql, $values)) {
                    return true;
                } else {
                    $errors[] = "Ha fallado $sql con <pre>" . print_r($values, 1) . "</pre>";
                    return false;
                }
                
			} catch(\PDOException $e) {
                $errors[] = 'Error sql al grabar el content multiidioma. ' . $e->getMessage();
                return false;
			}

		}


        public static function checkLangTable($table) {
            //assume yes
            return true;
        }

	}
}