<?php
/**
 * Description of Sedici-config
 *
 * @author Paula Salamone
 */
define ( 'S_CONECTOR2', '%5C' );
define ( 'S_CONECTOR3', '%7C' );
define ('S_CONECTOR4' , '%2C');
define ('S_CONECTOR5', '\+');
define ('S_SEPARATOR', '\|\|\|');
define ('S_FILTER' , '/discover?fq=author_filter%3A');
class Sedici_config extends Configuration{
    public function Sedici_config($conf){
       parent::__construct ($conf);
    }
    
    public function remplace($text){
		return str_replace(" ", S_CONECTOR5, $text);
	}
        
    function print_author($author){
        $link = $this->get_protocol_domain();
	$link = $link. S_FILTER;
        $name = str_replace(",", S_CONECTOR4, $author);
        $name = $this->remplace($name);
        $link .= strtolower($name). S_SEPARATOR . $name;
	return  ('<a href='.$link.' target="_blank">'.$author.'</a>') ;
            
    }
}