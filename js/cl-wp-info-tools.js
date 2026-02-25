'use strict';

document.addEventListener( 'DOMContentLoaded', () => {

	const contenidos = [
		'cl-content-wpo',
		'cl-content-ttfb',
		'cl-content-http2',
		'cl-content-dns',
		'cl-content-gzip',
		'cl-content-mail',
	];

	const muestraSoloDivActual = ( idActual ) => {
		contenidos.forEach( ( id ) => {
			document.getElementById( id ).style.display = 'none';
		} );
		document.getElementById( idActual ).style.display = 'flex';
	};

	//**************************************************//
	// Evento al hacer click en los diferentes botones //
	//************************************************//
	const botones = {
		'cl-wpo':   'cl-content-wpo',
		'cl-ttfb':  'cl-content-ttfb',
		'cl-http2': 'cl-content-http2',
		'cl-dns':   'cl-content-dns',
		'cl-gzip':  'cl-content-gzip',
		'cl-mail':  'cl-content-mail',
	};

	Object.entries( botones ).forEach( ( [ botonId, contenidoId ] ) => {
		document.getElementById( botonId ).addEventListener( 'click', () => {
			muestraSoloDivActual( contenidoId );
		} );
	} );

} ); // Fin del evento de carga del DOM.
