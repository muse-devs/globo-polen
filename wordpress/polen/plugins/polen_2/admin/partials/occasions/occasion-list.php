<?php
use Polen\Includes\Polen_Occasion_List;
$occasion_list = new Polen_Occasion_List();

if( ( isset( $_POST['occasion_category'] ) && !empty( $_POST['occasion_category'] ) ) &&
( isset( $_POST['occasion_description'] ) && !empty( $_POST['occasion_description'] ) ) )
{
  $new_occasion = $occasion_list->set_occasion( $_POST['occasion_category'], $_POST['occasion_description'] );
}  
$arr_occasion = $occasion_list->get_occasion();
?>
<div>
<h2>Categorias de Vídeo</h2>

<form action="" method="post">
  <table>
    <tr>
      <td>Categoria <input type="text" name="occasion_category" value="" required></td>
      <td>Descrição <input type="text" name="occasion_description" value="" required></td> 
      <td><input type="submit" value="cadastrar" class="button-primary"></td>
    </tr> 
  </table>
</form>
<div> 
  <?php 
    if( $new_occasion ){
      echo $new_occasion;
    }  
  ?>
<div>
<table class="pink-table">
    <thead>
        <tr>
            <th>CATEGORIA</th>
            <th>DESCRIÇÃO</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach( $arr_occasion as $occasion ): ?>
        <tr>
            <td><?php echo $occasion->type;?></td>
            <td><?php echo $occasion->description;?></td>
        </tr>
    <?php
    endforeach;    
    ?>
    </tbody>
</table>


<style>
table.pink-table {
  border: 1px solid #E61681;
  background-color: #FFFFFF;
  width: 80%;
  text-align: left;
  border-collapse: collapse;
}
table.pink-table td, table.pink-table th {
  border: 1px solid #AAAAAA;
  padding: 6px 6px;
}
table.pink-table tbody td {
  font-size: 14px;
}
table.pink-table tr:nth-child(even) {
  background: #EDF0EE;
}
table.pink-table thead {
  background: #E61681;
  background: -moz-linear-gradient(top, #ec50a0 0%, #e82d8d 66%, #E61681 100%);
  background: -webkit-linear-gradient(top, #ec50a0 0%, #e82d8d 66%, #E61681 100%);
  background: linear-gradient(to bottom, #ec50a0 0%, #e82d8d 66%, #E61681 100%);
  border-bottom: 2px solid #444444;
}
table.pink-table thead th {
  font-size: 15px;
  font-weight: bold;
  color: #FFFFFF;
  border-left: 2px solid #D0E4F5;
}
table.pink-table thead th:first-child {
  border-left: none;
}

table.pink-table tfoot td {
  font-size: 14px;
}
table.pink-table tfoot .links {
  text-align: right;
}
table.pink-table tfoot .links a{
  display: inline-block;
  background: #1C6EA4;
  color: #FFFFFF;
  padding: 2px 8px;
  border-radius: 5px;
}
</style>