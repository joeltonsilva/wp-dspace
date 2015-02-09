<?php

/**
 * Plugin Name: Sedici-Plugin
 * Plugin URI: http://sedici.unlp.edu.ar/
 * Description: This plugin connects the repository SEDICI in wordpress, with the purpose of showing the publications of authors or institutions
 * Version: 1.0
 * Author: SEDICI - Paula Salamone Lacunza
 * Author URI: http://sedici.unlp.edu.ar/
 * Copyright (c) 2015 SEDICI UNLP, http://sedici.unlp.edu.ar
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 */

function plugin_sedici($atts) {
	$a = shortcode_atts ( array (
			'type' => null,
			'context' => null,
			'max_results' => 0,
			'max_lenght' => 0,
			'all' => false,
			'description' => false,
			'date' => false,
			'show_author' => false,
			'cache' => 604800,
			'article' => false,
			'preprint' => false,
			'book' => false,
			'working_paper' => false,
			'technical_report' => false,
			'conference_object' => false,
			'revision' => false,
			'work_specialization' => false,
			'thesis' => false 
	), $atts );
	
	if ((is_null ( $a ['type'] )) && (is_null ( $a ['context'] ))) {
		return "Ingrese un type y un context";
	}
	$type = $a ['type'];
	if ((strcmp ( $type, "handle" ) !== 0) && (strcmp ( $type, "author" ) !== 0)) {
		return "El type debe ser handle o author";
	}
	
	$descripcion = $a ['description'] === 'true' ? "description" : false;
	$fecha = $a ['date'] === 'true' ? true : false;
	$mostrar = $a ['show_author'] === 'true' ? true : false;
	$cache = $a ['cache'];
	$context = $a ['context'];
	$all = $a ['all'] === 'true' ? true : false;
	$max_results = $a ['max_results'];
	$maxlenght = $a ['max_lenght'];
	$filtro = new Filtros ();
	$util = new Consulta ();
	$opciones = $filtro->vectorPublicaciones ();
	/* Opciones es un vector que tiene todos los filtros, es decir, articulos, tesis, etc */
	
	$filtros = array ();
	$vectorAgrupar = array ();
	/* vectorAgrupar, agrupara todas las publicaciones mediante su tipo */
	foreach ( $opciones as $o ) {
		/*
		 * Itera sobre opciones, y compara con las opciones marcadas del usuario. Si esta en true, entonces, guarda el nombre en filtros ($o) y en vectorAgrupar pone $o como clave
		 */
		$valor = $filtro->convertirEspIng ( $o );
		if ('true' === $a [$valor]) {
			array_push ( $filtros, $o );
			$vectorAgrupar [$o] = array ();
		}
	}
	$tesis = $a ['thesis'] === 'true' ? true : false;
	if ($tesis) {
		$vectorTesis = $filtro->vectorTesis ();
		// vectorTesis tiene todos los subtipos de tesis. Los agrego para la busqueda
		foreach ( $vectorTesis as $o ) {
			array_push ( $filtros, $o );
			$vectorAgrupar [$o] = array ();
		}
	}
	
	$vectorAgrupar = $util->agruparSubtipos ( $type, $all, $context, $filtros, $vectorAgrupar,$cache );
	if (! $all) {
		$enviar = $util->armarVista ( $vectorAgrupar, $type, $context );
	}
	$atributos = $util->agruparAtributos ( $descripcion, $fecha, $mostrar, $max_results, $context ,$maxlenght );
	$util->render ( $type, $all, $vectorAgrupar, $atributos, $enviar );
}