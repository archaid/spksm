<?php
error_reporting(0);
//fetch.php
if(isset($_POST["kdg"]) && isset($_POST["choice"]))
{
 $connect = mysqli_connect("localhost", "root", "", "kuliah_sp_bengkel");
 $output = '';
 $query = "SELECT * FROM gejala WHERE kd_gejala = '".$_POST['kdg']."'";
 $result = mysqli_query($connect, $query);
 while($row = mysqli_fetch_array($result))
 {
  $query_1 = "SELECT * FROM gejala WHERE kd_gejala = '".$_POST['kdg']."' ORDER BY kd_gejala DESC LIMIT 1";
  $result_1 = mysqli_query($connect, $query_1);
  $data_1 = mysqli_fetch_assoc($result_1);
  $query_2 = "SELECT * FROM gejala WHERE kd_gejala = '".$_POST['kdg']."' ORDER BY kd_gejala ASC LIMIT 1";
  $result_2 = mysqli_query($connect, $query_2);
  $data_2 = mysqli_fetch_assoc($result_2);
  $if_previous_disable = '';
  $if_next_disable = '';

  if($data_1["kd_gejala"] == $_POST["kdg"] && $_POST["choice"] == "Y"){
    $data_2["kd_gejala"] = $data_1["benar"];
  } else if($data_1["kd_gejala"] == $_POST["kdg"] && $_POST["choice"] == "N"){
    $data_2["kd_gejala"] = $data_1["salah"];
  }

  if($data_1["mulai"] == "Y")
  {
   $if_previous_disable = 'disabled';
  }
  if($data_2["selesai"] == "Y")
  {
   $if_next_disable = 'disabled';
  }

 $query_0 = "SELECT * FROM kerusakan WHERE kd_rusak = '".$row["benar"]."'";
 $result_0 = mysqli_query($connect, $query_0);
 $data_0 = mysqli_fetch_assoc($result_0);
 	if($row["benar"] == $data_0["kd_rusak"]){
  		$output .= '
  		  <div>
  		  <label for="gejala">Gejala</label>
		  <center><h1>'.$data_0["nm_rusak"].'</h1></center>
		  </div>
		  <div>
		  <label for="solusi">Solusi</label>
		  <center><h2>'.$data_0["solusi"].'</h2></center>
		  </div>';
  	} else {
 		$output .= '
		  <form id="fc">
		  <h2>'.$row["nm_gejala"].'?</h2>
		  <div>
		    <input type="radio" name="choice" value="Y">
		    <label for="yes">Ya</label>
		  </div>
		  <div>
		    <input type="radio" name="choice" value="N">
		    <label for="no">Tidak</label>
		  </div>
		  <div>
		    <input type="text" id="kdg" value="'.$_POST["kdg"].'" hidden>
		  </div>
		  </form>
		  ';	
		  $output .= '
		  <br /><br />
		  <div align="center">
		   <button type="button" name="previous" class="btn btn-info btn-sm previous" id="'.$data_1["kd_gejala"].'" '.$if_previous_disable.'>Previous</button>
		   <button type="button" name="next" class="btn btn-info btn-sm next" id="'.$data_2["kd_gejala"].'" '.$if_next_disable.'>Next</button>
		  </div>
		  <br /><br />
		  ';
 	}
 }
 echo $output;
}

?>