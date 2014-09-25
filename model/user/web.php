<?php


namespace Goteo\Model\User {

    class Web extends \Goteo\Core\Model {

        public
            $id,
            $user,
            $url;


        /**
         * Get the interests for a user
         * @param varcahr(50) $id  user identifier
         * @return array of interests identifiers
         */
	 	public static function get ($id) {
            $list = array();
            try {
                $query = static::query("SELECT id, user, url FROM user_web WHERE user = ?", array($id));
                foreach ($query->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $web) {
                    if (\substr($web->url, 0, 4) != 'http') {
                        $web->url = 'http://'.$web->url;
                    }
                    $list[] = $web;
                }

                return $list;
            } catch(\PDOException $e) {
				throw new \Goteo\Core\Exception($e->getMessage());
            }
		}

		public function validate(&$errors = array()) {}

		/*
		 *  Guarda las webs del usuario
		 */
		public function save (&$errors = array()) {

            $values = array(':user'=>$this->user, ':id'=>$this->id, ':url'=>$this->url);

			try {
	            $sql = "REPLACE INTO user_web (id, user, url) VALUES(:id, :user, :url)";
				self::query($sql, $values);
				return true;
			} catch(\PDOException $e) {
				$errors[] = Text::_("No se ha guardado correctamente. ") . $e->getMessage();
				return false;
			}

		}

		/**
		 * Quitar una palabra clave de un proyecto
		 *
		 * @param varchar(50) $user id de un proyecto
		 * @param INT(12) $id  identificador de la tabla keyword
		 * @param array $errors
		 * @return boolean
		 */
		public function remove (&$errors = array()) {
			$values = array (
				':user'=>$this->user,
				':id'=>$this->id,
			);

            try {
                self::query("DELETE FROM user_web WHERE id = :id AND user = :user", $values);
				return true;
			} catch(\PDOException $e) {
                $errors[] = Text::_('No se ha podido quitar la web ') . $this->id . Text::_(' del usuario ') . $this->user . ' ' . $e->getMessage();
                return false;
			}
		}

	}

}