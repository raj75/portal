<?php
require_once 'db_connect.php';
require_once 'functions.php';
sec_session_start();

$user_one=$_SESSION['user_id'];
$group_id=$_SESSION['group_id'];


if(!isset($user_one) or !isset($group_id) or $group_id != 1)
{
	echo json_encode(array("error"=>"Restricted Access!"));
	exit();
}



if(isset($_POST["query"]))
{
	$query = @trim(@urldecode($_POST['query']));
	$result=checkMySqlSyntax($mysqli, $query);
	if($result==false){ echo json_encode(array("error"=>"")); }
	else{ echo json_encode(array("error"=>$result)); }
	exit();
}

echo false;
exit();

function checkMySqlSyntax($mysqli, $query) {
   if ( trim($query) ) {
      // Replace literals within strings that may *** up the process by dummies
      $query = replaceCharacterWithinQuotes($query, '#', '%') ;
      $query = replaceCharacterWithinQuotes($query, ';', ':') ;
      // Prepare the query to make a valid EXPLAIN query
      // Remove comments # comment ; or  # comment newline
      // Remove SET @var=val;
      // Remove empty statements
      // Remove last ;
      // Put EXPLAIN in front of every MySQL statement (separated by ;) 
      $query = "EXPLAIN " .
               preg_replace(Array("/#[^\n\r;]*([\n\r;]|$)/",
                              "/[Ss][Ee][Tt]\s+\@[A-Za-z0-9_]+\s*=\s*[^;]+(;|$)/",
                              "/;\s*;/",
                              "/;\s*$/",
                              "/;/"),
                        Array("","", ";","", "; EXPLAIN "), $query) ;

      foreach(explode(';', $query) as $q) {
         $result = $mysqli->query($q) ;
         $err = !$result ? $mysqli->error : false ;
         if ( ! is_object($result) && ! $err ) $err = "Unknown SQL error";
         if ( $err) return $err ;
      }
      return false ;
  }
}

function replaceCharacterWithinQuotes($str, $char, $repl) {
    if ( strpos( $str, $char ) === false ) return $str ;

    $placeholder = chr(7) ;
    $inSingleQuote = false ;
    $inDoubleQuotes = false ;
    for ( $p = 0 ; $p < strlen($str) ; $p++ ) {
        switch ( $str[$p] ) {
            case "'": if ( ! $inDoubleQuotes ) $inSingleQuote = ! $inSingleQuote ; break ;
            case '"': if ( ! $inSingleQuote ) $inDoubleQuotes = ! $inDoubleQuotes ; break ;
            case '\\': $p++ ; break ;
            case $char: if ( $inSingleQuote || $inDoubleQuotes) $str[$p] = $placeholder ; break ;
        }
    }
    return str_replace($placeholder, $repl, $str) ;
 }

?>