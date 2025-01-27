<?php

class table

{

	public $primary_key;

	public $class_vars;

	public $className;

	public function __construct()

	{

		$this->class_vars = get_class_vars(get_class($this));

		$this->className = get_class($this);

		foreach ($this->class_vars as $name => $value) {

			if (isset($_POST[$name])) {

				$this->$name = htmlspecialchars(addslashes($_POST[$name]));

			}

		}

		$cv = $this->class_vars;

		list($name, $value) = each($cv);

		$this->primary_key = $name;

	}



	public function insert(){

		try {

			$fields = "";

			$values = "'";

			$class_vars = get_class_vars(get_class($this));

			foreach ($this->class_vars as $name => $value) {

				if (isset($_POST[$name])) {

					$fields .= $name . ",";

					$values .= $this->$name . "','";

				}

			}

			$fields = substr($fields, 0, strlen($fields) - 1);

			$values = substr($values, 0, strlen($values) - 2);

			$query = "insert into " . $this->className . "(" . $fields . ") values(" . $values . ")";

			$statut = connexion::getConnexion()->exec($query);





		} catch (PDOException $e) {

			die(handle_sql_errors($statut, $e->getMessage()));

		}

		return $statut;

	}



	public function update($index)

	{



		try {

			$sqlupdate = "";

			$sqlupdate = "update " . $this->className . " set ";



			foreach ($this->class_vars as $name => $value) {

				if (isset($_POST[$name])) {

					$sqlupdate .= $name . "='" . addslashes($this->$name) . "', ";

				}

			}

			$sqlupdate = substr($sqlupdate, 0, strlen($sqlupdate) - 2);

			$sqlupdate .= " where " . $this->primary_key . "=$index";



 



			$statut = connexion::getConnexion()->exec($sqlupdate);



		} catch (PDOException $e) {

			die(handle_sql_errors($statut, $e->getMessage()));

		}

		return $statut;

	}





	public function formupdate($tableau, $form)

	{

		$dom = new DomDocument;

		$dom->loadHTMLFile($form);

		for ($i = 0; $i <= $dom->getElementsByTagName("input")->length - 1; $i++) {

			if (($dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "text" || $dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "password" || $dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "hidden") && isset($tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")])) {

				$dom->getElementsByTagName("input")->item($i)->setAttribute("value", $tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")]);

			} elseif (($dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "radio" || $dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "checkbox") && (isset($tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")])) && ($tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")] == $dom->getElementsByTagName("input")->item($i)->getAttribute("value"))) {

				$dom->getElementsByTagName("input")->item($i)->setAttribute("checked", "checked");

			} elseif (($dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "radio" || $dom->getElementsByTagName("input")->item($i)->getAttribute("type") == "checkbox") && (isset($tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")])) && ($tableau[$dom->getElementsByTagName("input")->item($i)->getAttribute("name")] != $dom->getElementsByTagName("input")->item($i)->getAttribute("value"))) {

				$dom->getElementsByTagName("input")->item($i)->removeAttribute("checked");

			}

		}

		for ($i = 0; $i <= $dom->getElementsByTagName("textarea")->length - 1; $i++) {

			if (isset($tableau[$dom->getElementsByTagName("textarea")->item($i)->getAttribute("name")])) {

				$dom->getElementsByTagName("textarea")->item($i)->appendChild($dom->createTextNode(stripslashes($tableau[$dom->getElementsByTagName("textarea")->item($i)->getAttribute("name")])));

			}

		}

		for ($i = 0; $i <= $dom->getElementsByTagName("select")->length - 1; $i++) {

			if (isset($tableau[$dom->getElementsByTagName("select")->item($i)->getAttribute("name")])) {

				for ($j = 0; $j <= $dom->getElementsByTagName("select")->item($i)->childNodes->length - 1; $j++) {



					if ($dom->getElementsByTagName("select")->item($i)->childNodes->item($j)->getAttribute("value") == $tableau[$dom->getElementsByTagName("select")->item($i)->getAttribute("name")]) {

						$dom->getElementsByTagName("select")->item($i)->childNodes->item($j)->setAttribute("selected", "selected");

					}

				}

			}

		}

		echo $dom->saveHTML();

	}





	public function selectById($id)

	{





		$result = connexion::getConnexion()->query("select * from " . $this->className . " where " . $this->primary_key . "=" . $id);

		return $result->fetch(PDO::FETCH_ASSOC);

	}





	public function selectQuery($q)

	{

		$result = connexion::getConnexion()->query($q);



		return $result->fetchAll(PDO::FETCH_OBJ);

	}



	public function delete($id)

	{

		$statut = connexion::getConnexion()->exec("delete from " . $this->className . " where " . $this->primary_key . "='" . $id . "'");

		return $statut;

	}



	public 	function selectChamps($ch = '', $whr = '', $grby = '', $orby = '', $trie = '', $lim = '')

	{

		$where = (isset($whr) && ($whr <> '')) ? 'WHERE ' . $whr : '';

		$groupBy = (isset($grby) && ($grby <> '')) ? 'GROUP BY ' . $grby : '';

		$orderBy = (isset($orby) && ($orby <> '')) ? 'ORDER BY ' . $orby . ' ' . $trie : '';

		$limit = (isset($lim) && ($lim <> '')) ? 'LIMIT ' . $lim : '';







		$result = connexion::getConnexion()->query('SELECT ' . $ch . ' FROM ' . $this->className . ' ' . $where . ' ' . $groupBy . ' ' . $orderBy . ' ' . $limit);



		return $result->fetchAll(PDO::FETCH_OBJ);

	}



	public 	function getById($id)

	{



		$query = "SELECT * FROM " . $this->className . " WHERE id =" . $id;

		$result = connexion::getConnexion()->query($query);

		// return $query;

		return $result->fetchAll(PDO::FETCH_OBJ);

	}



	public 	function selectAll()

	{



		$result = connexion::getConnexion()->query('SELECT * FROM ' . $this->className);

		return $result->fetchAll(PDO::FETCH_OBJ);

	}





	public function laset_insert()

	{



		$result = connexion::getConnexion()->query("SELECT MAX( " . $this->primary_key . " ) as id FROM " . $this->className);



		$id = $result->fetch(PDO::FETCH_OBJ);



		return $id->id;

	}

}

