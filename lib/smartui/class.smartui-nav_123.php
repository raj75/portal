<?php

class Nav extends SmartUI {

	private $_structure = array(
		'nav' => array(),
		'id' => ''
	);

	public function __construct($nav_items = array()) {
		$this->_init_structure($nav_items);
	}

	private function _init_structure($nav_items) {
		$this->_structure = parent::array_to_object($this->_structure);
		$this->_structure->nav = $nav_items;
		$this->_structure->id = parent::create_id(true);
	}

	public function __get($name) {
		if (isset($this->_structure->{$name})) {
            return $this->_structure->{$name};
        }
        SmartUI::err('Undefined structure property: '.$name);
        return null;
	}

	public function __set($name, $value) {
		if (isset($this->_structure->{$name})) {
            $this->_structure->{$name} = $value;
            return;
        }
		SmartUI::err('Undefined structure property: '.$name);
	}

	public function __call($name, $args) {
		return parent::_call($this, $this->_structure, $name, $args);
	}

	public function print_html($return = false) {
		$get_property_value = parent::_get_property_value_func();

		$that = $this;
		$structure = $this->_structure;

		$nav_items = $get_property_value($structure->nav, array(
			'if_closure' => function($nav_items) use ($that) {
				return SmartUI::run_callback($nav_items, array($that));
			},
			'if_other' => function($nav_items) {
				SmartUI::err('SmartUI::Nav:nav requires array');
				return null;
			}
		));

		if (!is_array($nav_items)) {
			parent::err("SmartUI::Nav:nav requires array");
			return null;
		}

		$list_items = $this->parse_nav($nav_items, true);
		$result = parent::print_list($list_items, null, true);

		if ($return) return $result;
		else echo $result;
	}

	private function parse_nav($nav_item, $is_parent = false) {
/*$mysqli = new mysqli("localhost", "root", "");
//require_once '/assets/includes/db_connect.php';
//require_once '/assets/includes/functions.php';
if(!isset($_SESSION))
	sec_session_start();

$user_one=$_SESSION['user_id'];

	$ttmp_ao_permit=$ttmp_cs_permit=0;
	$stmtat = $mysqli->prepare("SELECT up.accuvio_user,up.accuvio_pass,up.capturis_user,up.capturis_pass FROM userprofile up, user u WHERE up.user_id=u.id and u.id= '".$user_one."' LIMIT 1");
	$stmtat->execute();
	$stmtat->store_result();
	if ($stmtat->num_rows > 0) {
		$stmtat->bind_result($at_accuvio_user,$at_accuvio_pass,$at_capturis_user,$at_capturis_pass);
		while($stmtat->fetch()){
			if((@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "") or (@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "")){

					 if(@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != ""){$ttmp_ao_permit=1;}
						if(@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != ""){$ttmp_cs_permit=1;}
			}
		}
	}*/
		if (!$nav_item) return '';
		$nav_items_list = array();
		foreach ($nav_item as $name => $nav) {
			$nav_prop = array(
				'url' => '#',
				'url_target' => '',
				'icon' => '',
				'icon_badge' =>  '',
				'label_htm' => '',
				'title' => $name,
				'title_append' => '',
				'label' => '',
				'active' => false,
				'sub' => array(),
				'attr' => array(),
				'class' => array(),
				'li_class' => array()
			);

			$new_nav_prop = parent::set_array_prop_def($nav_prop, $nav, 'title');

			$icon_badge_prop = array(
				'content' => '',
				'class' => ''
			);

			$icon_badge_prop = parent::set_array_prop_def($icon_badge_prop, $new_nav_prop['icon_badge'], 'content');
			$badge = '';
			if ($icon_badge_prop['content']) {
				$badge_class = $icon_badge_prop['class'] ? 'class="'.$icon_badge_prop['class'].'"' : '';
				$badge = '<em '.$badge_class.'>'.$icon_badge_prop['content'].'</em>';
			}

			$icon = $new_nav_prop['icon'] ? '<i class="'.(preg_match("/glyphicon/s",$new_nav_prop['icon'],$tmp_none)?'glyphicon ':SmartUI::$icon_source.' '.SmartUI::$icon_source.'-lg '.SmartUI::$icon_source.'-fw ').$new_nav_prop['icon'].'">'.$badge.'</i>' : '';
			$title = $new_nav_prop['title'];
			$title_append = $new_nav_prop['title_append'];

			$display_title = $title.' '.$title_append;

			$label_htm = $new_nav_prop['label_htm'] ? ' '.$new_nav_prop['label_htm'] : '';

			$display_text = $is_parent ? '<span class="menu-item-parent">'.$display_title.'</span>' : $display_title;
			$display_text .= $label_htm;

			$attrs = array_map(function($attr, $value) {
				return $attr.'="'.$value.'"';
			}, array_keys($new_nav_prop['attr']), $new_nav_prop['attr']);

			if ($new_nav_prop['class']) $attrs[] = 'class="'.$new_nav_prop['class'].'"';

			$nav_htm = '<a
				href="'.$new_nav_prop['url'].'"
				'.($new_nav_prop['url_target'] ? 'target="'.$new_nav_prop['url_target'].'"' : '').'
				title="'.$title.'"
				'.($title=="Energy Accounting" ? ' onclick="window.open(\'assets/ajax/autologin.php?w=d1befa03c79ca0b84ecc488dea96bc68\',\'Capturis\',\'width=\'+(screen.width-(screen.width/5))+\', height=\'+(screen.height-(screen.height/5))+\', scrollbars=1\')" ' : '').'
				'.($title=="Sustainability" ? ' onclick="window.open(\'assets/ajax/autologin.php?w=fbce0bb98d18aca35b2938c78f52f57b\',\'Accuvio\',\'width=\'+(screen.width-(screen.width/5))+\', height=\'+(screen.height-(screen.height/5))+\', scrollbars=1\')" ' : '').'
				'.implode(' ', $attrs).'>
					'.$icon.'
					'.$display_text.'
					'.$new_nav_prop['label'].'
				</a>';

			$li_classes = array();
			if ($new_nav_prop["active"]) $li_classes[] = 'active';
			if ($new_nav_prop['li_class']) {
				if (is_string($new_nav_prop['li_class'])) $li_classes[] = $new_nav_prop['li_class'];
				else if (is_array($new_nav_prop['li_class'])) $li_classes = array_merge($li_classes, $new_nav_prop['li_class']);
			}

			$nav_item_structure = array(
				'content' => $nav_htm,
				'class' => implode(' ', $li_classes)
			);

			if (isset($new_nav_prop['sub'])) {
				if (is_string($new_nav_prop['sub'])) {
					$nav_item_structure['subitems'] = array($new_nav_prop['sub']);
				} else {
					$nav_item_structure['subitems'] = $this->parse_nav($new_nav_prop['sub']);
				}
			}

			$nav_items_list[] = $nav_item_structure;
		}

		return $nav_items_list;
	}
}


?>