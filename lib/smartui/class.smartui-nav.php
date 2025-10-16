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

		$list_items = $this->parse_nav($nav_items,'',true);
		$result = parent::print_list($list_items, null, true);

		if ($return) return $result;
		else echo $result;
	}

	//$tcount=0;
	private function parse_nav($nav_item, $mainmenu='',$is_parent = false) {
		//global $tcount;
$mysqli = new mysqli("develop.cfiddgkrbkvm.us-west-2.rds.amazonaws.com", "root", "7Rjfz0cDjsSc","vervantis");
//require_once '/assets/includes/db_connect.php';
//require_once '/assets/includes/functions.php';
if(!isset($_SESSION))
	sec_session_start();

$ttmp_cs_permit=$ttmp_ao_permit="";
$user_one=$_SESSION['user_id'];
 
if(checkpermission($mysqli,35)==false) $ttmp_cs_permit='alert(\'Restricted Access. Please contact Vervantis!\')';
if(checkpermission($mysqli,38)==false) $ttmp_ao_permit='alert(\'Restricted Access. Please contact Vervantis!\')';


	//$ttmp_ao_permit=$ttmp_cs_permit='alert(\'Error Occured! No Login Data\')';
	$stmtat = $mysqli->prepare("SELECT u.accuvio_user,u.accuvio_pass,u.capturis_user,u.capturis_pass FROM user u WHERE u.user_id= '".$user_one."' LIMIT 1");
	$stmtat->execute();
	$stmtat->store_result();
	if ($stmtat->num_rows > 0) {
		$stmtat->bind_result($at_accuvio_user,$at_accuvio_pass,$at_capturis_user,$at_capturis_pass);
		while($stmtat->fetch()){
			if((@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "") or (@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "")){

					if(@trim($at_accuvio_user) != "" and @trim($at_accuvio_pass) != "" and $ttmp_ao_permit == ""){$ttmp_ao_permit='window.open(\'assets/ajax/autologin.php?w=fbce0bb98d18aca35b2938c78f52f57b\',\'_blank\')';}
					if(@trim($at_capturis_user) != "" and @trim($at_capturis_pass) != "" and $ttmp_cs_permit==""){$ttmp_cs_permit='window.open(\'assets/ajax/autologin.php?w=d1befa03c79ca0b84ecc488dea96bc68\',\'_blank\')';}
			}
		}
	}
	
	if($ttmp_ao_permit=="") $ttmp_ao_permit='alert(\'Error Occured! No Login Data\')';
	if($ttmp_cs_permit=="") $ttmp_cs_permit='alert(\'Error Occured! No Login Data\')';

		if (!$nav_item) return ''; 
		$nav_items_list = array();
		
		if($is_parent == false){
			$counttt=0;
			foreach ($nav_item as $name => $nav) {
				if($nav['disable']==0){$counttt=1;}
			} 
			if($counttt == 0){
				return Array(0 => Array('content' => '<a href="#" class="reqactivate" menutitle="'.$mainmenu.'" title="Click here to request activation: '.$mainmenu.'">Request Activation</a>','class' =>'','subitems' =>'' ));
			}
		}
		
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
			$disableit = $new_nav_prop['disable'];

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
				 '.($disableit == 1 ? ' class="reqactivate" menutitle="'.@strip_tags($title).'" title="Click to activate: '.@strip_tags($title).'" ' : ' title="'.@strip_tags($title).'"').' 
				'.($title=="UBM Software" ? ' onclick="'.$ttmp_cs_permit.'" ' : '').'
				'.($title=="CSR/ESG Software" ? ' onclick="'.$ttmp_ao_permit.'" ' : '').'
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
					$nav_item_structure['subitems'] = $this->parse_nav($new_nav_prop['sub'],($is_parent==true?$title:''));
				}
			}

			if($nav['disable'] != 1){
				$nav_items_list[] = $nav_item_structure;
			}
		}
		return $nav_items_list;
	}
	
	
public function checkpermission($mysqli,$pgid=0){
	if(!isset($_SESSION["user_id"])) {header('Location: https://'.$_SERVER['HTTP_HOST'] .'/login.php');exit();}
	else{$user_one=$_SESSION["user_id"];}

	if($pgid==0 or $pgid=="" or $mysqli=="") return false;

	if($_SESSION["group_id"]==1 || $_SESSION["group_id"]==2){return true;}
	elseif($_SESSION["group_id"]==4){return true;}
	elseif($_SESSION["group_id"]==3 || $_SESSION["group_id"]==5){

		$colomnsarr=array(0=>array('`Futures Pricing`','`Locational Marginal Pricing`','`Live Weather`','`Streaming News`','`Verve Energy Blog`','`Market Commentary`','`Direct Access Information`','`Energy Procurement`','`Dynamic Risk Management`','`Master Supply Agreements`','`Supplier Contracts`','`Utility Requirements`','`Regulated Information`','`Utility Rate Reports`','`Utility Rate Change Requests`','`Start New Service`','`Stop Service`','`StartStop Status`','`Correspondence`','`UBM Software`','`Utility Budgets`','`Invoice Validation`','`Exception Reports`','`Resolved Exceptions`','`Site n Account Changes`','`Data Analysis`','`Consumption Reports`','`Custom Reports`','`CSR ESG Software`','`Sustainability Reports`','`Corporate Reports`','`Surveys`','`Distributed Generation`','`Efficiency Upgrades`','`EV Charging`','`Rebates n Incentives`','`Other`','`Energy Advocate`','`Client Dashboard Summary`','`Saving Analysis`','`Focus Items`','`Users Edit`','`User Permissions`','`Benchmark Report`'),
		1=>array(48,59,52,60,2,3,31,27,46,54,55,56,30,41,61,49,50,51,58,35,37,36,62,63,64,33,65,34,38,39,74,75,40,66,67,68,69,45,17,6,5,12,76,78));


		if(!in_array($pgid,$colomnsarr[1])) return false;
		if(!$kyofid = array_search($pgid,$colomnsarr[1])) return false;
		

		$csql='SELECT '.$colomnsarr[0][$kyofid].' FROM `company_permission` cp, user u where u.company_id=cp.company_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';	

		$usql='SELECT '.$colomnsarr[0][$kyofid].' FROM `user_permission` cp, user u where u.user_id=cp.user_id and u.user_id="'.$mysqli->real_escape_string($user_one).'" LIMIT 1';


		$c_permissionarr=array(0,0);
		
		$u_permission=0;


		if ($cstmt = $mysqli->prepare($csql)) { 
			$cstmt->execute();
			$cstmt->store_result();
			if ($cstmt->num_rows > 0) {
				$cstmt->bind_result($c_permission);

				$cstmt->fetch();

				$c_permissionarr=explode(":",$c_permission);
			}
		}


		if ($ustmt = $mysqli->prepare($usql)) { 
			$ustmt->execute();
			$ustmt->store_result();
			if ($ustmt->num_rows > 0) {
				$ustmt->bind_result($u_permission);

				$ustmt->fetch();
			}
		}

		if(is_array($c_permissionarr) and count($c_permissionarr) == 2 and $c_permissionarr[0]==1 and $c_permissionarr[1]==1 and $u_permission==1) return true;
		else return false;
	}
	return false;
}
}


?>