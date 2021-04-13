<script>
$(document).ready(function(){

 $("#fc").submit(function() {
  var choice = document.querySelector('input[name="choice"]:checked').value;
  var kdg = document.getElementById("kdg").value;

  $.ajax({
    url: 'ajax.php',
    type: 'POST',
    data: {choice : choice, kdg : kdg},
    success: function(data) {
      $('#post_modal').modal('show');
      $('#post_detail').html(data);
      $('.next').trigger('click');
    }
  });

  return false;
  });
 
});

$('input[type=radio]').on('click', function() {
    $(this).closest("form").submit();
});
</script>


<?php
//fetch.php
 $connect = mysqli_connect("localhost", "root", "", "kuliah_sp_bengkel");
 $output = '';
 $query = "SELECT * FROM gejala WHERE kd_gejala = '".$_POST['kd_gejala']."'";
 $result = mysqli_query($connect, $query);
 while($row = mysqli_fetch_array($result))
 {
  if($row["kd_gejala"] == 'A111'){
    $output .= '
      <center><h1>'.$row["nm_gejala"].'</h1></center>';
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
      <input type="text" id="kdg" value="'.$_POST["kd_gejala"].'" hidden>
    </div>
    </form>
    ';
  }
 }
 echo $output;

?>