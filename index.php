<?php 
//index.php
$connect = mysqli_connect("localhost", "root", "", "kuliah_sp_bengkel");
$query = "SELECT * FROM gejala ORDER BY kd_gejala ASC";
$result = mysqli_query($connect, $query);
$query1 = "SELECT * FROM kerusakan ORDER BY kd_rusak ASC";
$result1 = mysqli_query($connect, $query1);
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Sistem Pakar Kerusakan Pada Sepeda Motor</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <style type="text/css">
    p,
    label {
        font: 1.5rem 'Fira Sans', sans-serif;
    }
    input {
        margin: .4rem;
    }
    .pagination li:hover{
    cursor: pointer;
    }
  </style>
  
 </head>
 <body>
  <br /><br />
  <div class="container">
   <h2 align="center">Sistem Pakar Kerusakan Pada Sepeda Motor</h2>
   <br />
   <div class="table-responsive col-sm-6">
      <div class="form-group pull-right">  <!--    Show Numbers Of Rows    -->
        <select class  ="form-control" name="state" id="maxRows">
         <option value="5000">Show ALL Rows</option>
         <option value="5">5</option>
         <option value="10">10</option>
         <option value="15">15</option>
         <option value="20">20</option>
         <option value="50">50</option>
         <option value="70">70</option>
         <option value="100">100</option>
        </select>
      </div>
    <table class="table table-striped table-bordered" id="TbG">
     <tr>
      <th>Kode</th>
      <th>Nama</th>
      <th>View</th>
     </tr>
     <?php
     while($row = mysqli_fetch_array($result))
     {
      echo '
      <tr>
       <td>'.$row["kd_gejala"].'</td>
       <td>'.$row["nm_gejala"].'</td>
       <td><button type="button" name="view" class="btn btn-info view" id="'.$row["kd_gejala"].'" >View</button></td>
      </tr>
      ';
     }
     ?>
    </table>
    <!--    Start Pagination -->
      <div class='pagination-container' style="margin-top: -25px">
        <nav>
          <ul class="pagination">
            
            <li data-page="prev" >
                     <span> < <span class="sr-only">(current)</span></span>
                    </li>
           <!-- Here the JS Function Will Add the Rows -->
        <li data-page="next" id="prev">
                       <span> > <span class="sr-only">(current)</span></span>
                    </li>
          </ul>
        </nav>
      </div>
   </div>
   <div class="table-responsive col-sm-6">
    <table class="table table-striped table-bordered">
     <tr>
      <th>Kode</th>
      <th>Nama</th>
     </tr>
     <?php
     while($row = mysqli_fetch_array($result1))
     {
      echo '
      <tr>
       <td>'.$row["kd_rusak"].'</td>
       <td>'.$row["nm_rusak"].'</td>
      </tr>
      ';
     }
     ?>
    </table>
   </div>
   
  </div>
 </body>
</html>

<div id="post_modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content"> 
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Post Details</h4>
   </div>
   <div class="modal-body" id="post_detail">

   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" id="cancel" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>

<script>
$(document).ready(function(){
 
 function fetch_post_data(kd_gejala)
 {
  $.ajax({
   url:"fetch.php",
   method:"POST",
   data:{kd_gejala:kd_gejala},
   success:function(data)
   {
    $('#post_modal').modal('show');
    $('#post_detail').html(data);
   }
  });
 }

 $(document).on('click', '.view', function(){
  var kd_gejala = $(this).attr("id");
  fetch_post_data(kd_gejala);
 });

 $(document).on('click', '.previous', function(){
  var kd_gejala = $(this).attr("id");
  fetch_post_data(kd_gejala);
 });

 $(document).on('click', '.next', function(){
  var kd_gejala = $(this).attr("id");
  fetch_post_data(kd_gejala);
 });
 
});
</script>

<script type="text/javascript">
  getPagination('#TbG');
          //getPagination('.table-class');
          //getPagination('table');

      /*          PAGINATION 
      - on change max rows select options fade out all rows gt option value mx = 5
      - append pagination list as per numbers of rows / max rows option (20row/5= 4pages )
      - each pagination li on click -> fade out all tr gt max rows * li num and (5*pagenum 2 = 10 rows)
      - fade out all tr lt max rows * li num - max rows ((5*pagenum 2 = 10) - 5)
      - fade in all tr between (maxRows*PageNum) and (maxRows*pageNum)- MaxRows 
      */
     

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');            // reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //  numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
                  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
                </li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');          // add active class to the clicked
      limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
    limitPagging();
    })
    .val(5)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
  // alert($('.pagination li').length)

  if($('.pagination li').length > 7 ){
      if( $('.pagination li.active').attr('data-page') <= 3 ){
      $('.pagination li:gt(5)').hide();
      $('.pagination li:lt(5)').show();
      $('.pagination [data-page="next"]').show();
    }if ($('.pagination li.active').attr('data-page') > 3){
      $('.pagination li:gt(0)').hide();
      $('.pagination [data-page="next"]').show();
      for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
        $('.pagination [data-page="'+i+'"]').show();

      }

    }
  }
}

//  Developed By Yasser Mas
// yasser.mas2@gmail.com

</script>