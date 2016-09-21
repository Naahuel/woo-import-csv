<?php
	$archivo  = WP_CONTENT_DIR .  '/uploads/csv/sku_titulo.csv';
?>
<div class="wrap">
<h1>WooCommerce nombres por SKU</h1>
<p>
	Ubicar el archivo con exactamente esta ruta:
	<code><?php echo $archivo; ?></code>
</p>
<p>
	<a href="<?php menu_page_url('woo-csv-nombres/woo-csv-nombres-do.php'); ?>&amp;do-it=go" class="button button button-primary">Run</a>
</p>
<?php

if( isset($_GET['do-it']) ){

	// A por ello
	if( !file_exists( $archivo ) ){
		echo '<div id="message" class="error"><p>El archivo no existe.</p></div>';
	} else{

		// Sí existe.
		// Leo el archivo a un array
		$hw_file = file( $archivo );
		$num_linea = 0;
		$no_importados = array();

		// Abro tabla
		?>
		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-name column-primary">SKU</th>
					<th scope="col" class="manage-column column-description">Título viejo</th>
					<th scope="col" class="manage-column column-description">Título Nuevo</th>
				</tr>
			</thead>
			<tbody>
		<?php

		// Recorro linea a línea y convierto a array el CSV
		foreach ($hw_file as $linea) {

			// $linea = utf8_encode( $linea );
			$csv_linea = str_replace(',', '.', str_getcsv( $linea, ';' ));
			$num_linea++;

			if( $num_linea == 1 ){
				// Ignorar primera línea
				continue;
			}

			// Busco el producto por SKU
			$post_id = wc_get_product_id_by_sku( $csv_linea[0] );

			if( $post_id ){
				// El producto existe
				$the_post = get_post( $post_id );
				$titulo_existente = $the_post->post_title;

				// Mostrar
				if( $titulo_existente != $csv_linea[1] ){
					// Títulos diferentes
					$bg_color = '#9af495';

					// Actualizo el título
					$my_post = array(
			      'ID'           => $post_id,
			      'post_title'   => $csv_linea[1],
				  );

					// Update the post into the database
					wp_update_post( $my_post );

				} else {
					$bg_color = 'transparent';
				}
				echo sprintf( '<tr><td>%s</td><td>%s</td><td style="background-color: %s">%s</td></tr>', $csv_linea[0], $titulo_existente,$bg_color, $csv_linea[1] );

			} else {
				// El producto no existe
				$no_importados[] = $csv_linea;
			}

			// if( $num_linea == 50 ){
			// 	break;
			// }

		} // hwdfile as linea

		// Cierro tabla
		echo '</tbody></table>';

		?>

		<h3>No importados</h3>
		<table class="wp-list-table widefat striped">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-name column-primary">SKU</th>
					<th scope="col" class="manage-column column-description">Título</th>
				</tr>
			</thead>
			<tbody>

		<?php
			foreach ($no_importados as $no_importado) {
				echo sprintf( '<tr><td>%s</td><td>%s</td></tr>', $no_importado[0], $no_importado[1] );
			}

			echo '</tbody></table>';

	} // file_exists();

} //isset($_GET['do-it'])


echo '</div>'; // wrap
