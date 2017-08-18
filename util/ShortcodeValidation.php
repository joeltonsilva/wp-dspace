<?php
require_once 'FormValidation.php';
include_once ('../configuration/config.php');
class ShortcodeValidation extends FormValidation{

    public function create_configuration($configuration){
        $b=FALSE; $names = Array();
        $directorio = get_configuration_directory();
	foreach (glob($directorio."*.ini") as $value) {
            $ini_array = parseFile($value);
            array_push($names, $ini_array['name']);
            if ($ini_array['name'] == $configuration){
                $b=TRUE;
            }
        }
        if($b){
            return parent::create_configuration($configuration);
        } else {
            echo "Configuración incorrecta. Configuraciones permitidas: ";
            $print_names=implode(" - ",$names);
            echo $print_names;
            return null;
        }
    }

    function maxResults($max_results){
            if ( $max_results < min_results()) { $max_results = min_results();}
            else { if ( $max_results > max_results()) { $max_results = max_results();} }
            return $max_results;
    }
    function maxLenght($max_lenght){
            if (!is_null($max_lenght)){
		 if ( $max_lenght < min_results()) { $max_lenght = show_text();}
            }
            return $max_lenght;
    }
    public function getOrder($subtype,$date){
        if (($subtype) && ('true' === $date)){
            return $this->order['group_year_subtype'];
        }
        elseif ($subtype){
            return $this->order['group_subtype'];
        }
        elseif ('true' === $date){
            return $this->order['group_year'];
        }
        return null;
    }

}
