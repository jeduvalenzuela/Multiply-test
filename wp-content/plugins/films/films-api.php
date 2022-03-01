<?php /**
 * Plugin Name: Films Api
 * Plugin URI: https://gavaweb.com/Eduardo-Valenzuela
 * Description: Consumir api rest de peliculas e imprimir lista de segun rol de usuario
 * Version: 1.0.0
 * Author: Eduardo Valenzuela
 * License: GPL2
 */


//CREO TABLA WISHLIST
function activar(){
    global $wpdb;

    $sql ="CREATE TABLE IF NOT EXISTS `multiply`.`$table_name` ( 
        `id_favorito` INT(20) NOT NULL AUTO_INCREMENT , 
        `id_imbd` VARCHAR(20) NULL DEFAULT NULL , 
        `id_user` VARCHAR(20) NULL DEFAULT NULL , 
        PRIMARY KEY (`id_favorito`)
        ) ENGINE = InnoDB;";

    $wpdb->query($sql);
} 

function desactivar(){
    flush_rewrite_rules(  );
}
register_activation_hook( __FILE__, 'activar' );
register_deactivation_hook( __FILE__, 'desactivar' );



//ACCION CREACIÓN ROLES
function nuevo_rol(){
    add_role( 
        'amante-de-pelis',
        'Amante de Pelis',
        [
            'read' => true,

        ]
    );

    add_role( 
        'amante-de-series',
        'Amante de series',
        [
            'read' => true,

        ]
    );
}
register_activation_hook( __FILE__, 'nuevo_rol' );
add_action( 'init', 'nuevo_rol' );



// FILTRO PARA IMPRIMIR EL CONTENIDO APIS EN PAGINA FILMS 
add_filter('the_content', 'add_custom_content');

// AGREGO EL CONTENIDO SOLO SI ME ENCUENTRO EN FILMS"
function add_custom_content($content){

    
	if ( ! is_page('films') ) return $content;

        if (is_user_logged_in()) {
            

            // Aquí van las acciones a realizar si el usuario está identificado
            $html = get_data_api();
            return $html;
        } else {
                $mensaje = "<p>Para ver el contenido debes Loguearte <a href='../wp-login.php?action=login'>ACÁ</a>.</p>";
                return $mensaje;

        }

}


// Función que se encarga de recuperar los datos de la API externa
function get_data_api(){

        if( is_user_logged_in() ) { // check if there is a logged in user 
	 
            $user = wp_get_current_user(); // getting & setting the current user 
            $roles = $user->roles; // obtaining the role 
        
            //print_r($roles) ; // return the role for the current user 
            


            if(in_array("amante-de-pelis", $roles)){
                $api = 'https://imdb-api.com/en/API/Top250Movies/k_3cuhy6f7';
                
            }elseif(in_array("amante-de-series", $roles)){
                $api = 'https://imdb-api.com/en/API/Top250Tvs/k_3cuhy6f7';
            }
        }
        //print_r($api);

        $url = $api;
        $response = wp_remote_get($url);

        //print_r($response) ;

        if (is_wp_error($response)) {
            error_log("Error: ". $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);

        $data = json_decode($body);

        

        $template = '<table class="table-data">
                        <tr>
                            <th></th>
                            <th>Rank</th>
                            <th>Title</th>
                            <th>Calificacion</th>
                            <th>Reparto</th>
                            <th>Favorito</th>
                        </tr>
                        {data}
                    </table>';

        if ( $data ){
            $str = '';
            $conteo = count($data->items);
        
//            print_r($data->items);
            
            foreach ($data->items as $film) {
                $id_user = get_current_user_id(  );
                //print_r($id_user);

                //GUARDAR EN FAVORITOS
                global $wpdb;
        
                if(isset($_POST['btn-save'.$film->id])){

                    global $wpdb;

                    //$save ="INSERT INTO `wishlist`(`id_imbd`, `id_user`) VALUES ($film->id,$id_user);";
                    //$wpdb->query($save);
                    $wpdb->insert(
                        'wishlist',
                        [
                            'id_imbd' => $film->id,
                            'id_user' => $id_user,
                        ]
                    );

                }

                if ($film->rank <= 10 & in_array("amante-de-pelis", $roles)){
                    $str .= "<tr>";
                    $str .= "<td><img src='{$film->image}' width='175'/></td>";
                    $str .= "<td>{$film->rank}</td>";
                    $str .= "<td>{$film->fullTitle}</td>";
                    $str .= "<td>{$film->imDbRating}</td>";
                    $str .= "<td>{$film->crew}</td>";
                    $str .= "<td><form method='post'><input type='submit' name='btn-save".$film->id."' value='Guardar'></form></td>";
                    $str .= "</tr>";
                }elseif ($film->rank > $conteo - 10 & in_array("amante-de-series", $roles)){
                    $str .= "<tr>";
                    $str .= "<td><img src='{$film->image}' width='175'/></td>";
                    $str .= "<td>{$film->rank}</td>";
                    $str .= "<td>{$film->fullTitle}</td>";
                    $str .= "<td>{$film->imDbRating}</td>";
                    $str .= "<td>{$film->crew}</td>";
                    $str .= "<td><form method='post'><input type='submit' name='btn-save".$film->id."' value='Guardar'></form></td>";
                    $str .= "</tr>";
                }
            }
        }

        $html = str_replace('{data}', $str, $template);

        return $html;

}





//DESPUES DE LOGUEARSE COMO AMANTE DE PELIS O SERIES SE REDIRIGEN A LISTADO DE PELICULAS
function after_login_redirect($redirect_to, $request, $user) {
    global $user;
  
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
      if ( in_array( 'amante-de-pelis', $user->roles ) ) {
        return home_url("/films");
      }elseif ( in_array( 'amante-de-series', $user->roles ) ) {
        return home_url("/films");
      }else{
        return home_url();
      }
    }
  
    return $redirect_to;
  }
  add_filter( 'login_redirect', 'after_login_redirect', 10, 3 );

  function my_custom_logout_redirect(){
    wp_redirect( home_url() );
    exit();
  }
  add_action( 'wp_logout', 'my_custom_logout_redirect' );




//IMPRIMIR FAVORITOS SOLO EN PAGINA FAVORITOS
add_filter( 'the_content', 'list_wishlist' );
 
function list_wishlist( $content ) {
	$slug_page = 'favoritos'; //slug de la página en donde se mostrará la tabla
	$id_user = get_current_user_id(  );
	
	if (is_page($slug_page)){
	    global $wpdb;	
	    $wishitems = $wpdb->get_results("SELECT * FROM `wishlist` WHERE `id_user`= $id_user;");
	    $result = '';
		
		// nombre de los campos de la tabla
		foreach ($wishitems as $wishitem) {
			$url = "https://imdb-api.com/en/API/Title/k_3cuhy6f7/$wishitem->id_imbd";
            $wish_id = $wishitem->id_favorito;
            //print_r($wish_id);

            $response = wp_remote_get($url);
            $body = wp_remote_retrieve_body($response);
            $wishes = json_decode($body);
            
            if(isset($_POST['btn-delete'.$wish_id])){

                global $wpdb;

                $wpdb->delete(
                    'wishlist',
                    array(
                        'id_favorito' => $wish_id,
                    )
                );

                return "<p>Has eliminado de tu lista de favoritos: ".$wishes->fullTitle."</p><a href='./favoritos'>Volver a tu lista de favoritos</a>";
            
            }

            //print_r($wishes);
                
                    $result .= "<tr>";
                    $result .= "<td><img src='{$wishes->image}' width='175'/></td>";
                    $result .= "<td>{$wishes->fullTitle}</td>";
                    $result .= "<td>{$wishes->imDbRating}</td>";
                    $result .= "<td>{$wishes->genres}</td>";
                    $result .= "<td><form method='post'><input type='submit' name='btn-delete{$wish_id}' value='Borrar'></form></td>";
                    $result .= "</tr>";
  
		}

        $template = '<table class="table-data">
            <tr>
                <th></th>
                <th>Title</th>
                <th>Calificacion</th>
                <th>Genero</th>
                <th>Favorito</th>
            </tr>
            {data}
        </table>';
        

	    return $content.str_replace('{data}', $result, $template);
	}

	return $content;
}