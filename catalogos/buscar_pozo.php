<?php
 	//sleep(1);
    if(isset($_POST['term'])) {
		$buscar = $_POST['term'];
	}
    if(isset($_GET['term'])) {
		$buscar = $_GET['term'];
	}
	  if(!empty($buscar)) {
            buscar($buscar);
      }
       
      function buscar($b) {
            $con = mysqli_connect('localhost','moises', 'pemex11');
            mysqli_select_db($con, 'SECOE');
       
            $sql = mysqli_query($con, "SELECT * FROM cat_pozos WHERE nombrepozo LIKE '%".$b."%' LIMIT 10");
             
            $contar = mysqli_num_rows($sql);
             
            if($contar == 0){
                  $resultado[] = array('label'=>"No se han encontrado resultados para '<b>".$b."</b>'.");
            }else{
                  while($row=mysqli_fetch_array($sql)){
                        $resultado[] = array('value'=>$row['idpozo'],'label' => $row['nombrepozo']);
						//$nombre = $row['nombrepozo'];
                        //$id = $row['idpozo'];
                  }
            print json_encode($resultado, JSON_PRETTY_PRINT);
			}
			
      }
       
?>