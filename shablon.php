<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $this->pagina_name; ?></title>
<style>
 body {background-color: #E8EAEA;}
p,fieldset{width:60%;}
	p,fieldset{width:60%;}
	input,select{background: #FBFBFF;
	color :#a29d9e;}
form {
    border: 0px solid white;
    border-radius: 5px;
width: 90%;
    max-width: 500px;
   text-align: right;
    
    margin: 5px auto 0 auto!important;
    -webkit-transition: 0.4s;
    -o-transition: 0.4s;
    transition: 0.4s;
}

input,select{
	
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    text-decoration: none;
    outline: none;
    padding: 2px 15px;
    min-height: 30px;
    margin: 5px; 
    border-radius: 20px;
    
    border: 1px solid #3f415682;
}
input[type=submit] {background: #635c5f;color:#ffffff;}
.asinatura {width:30%;float:right; height:600px;background: #FBFBFF;}
.verifirar {width:400px;height:200px;background:#E8EAEA; color: red;font-size: 14px;}
.menu {width:100%;height:100px;background:#a29d9e;}
.menu li {width:12%;height:80px;background:#a29d9e; float:left;margin: 10px;}
.Atos {}
.polosa{height:50px;background:#a29d9e;width: 200px;margin: 10px; }
</style>
<script src="jquery-3.6.0.min.js"></script>

</head>

<body>
 <header><ul class='menu'>
    <?php echo $this->menu;?>
 </ul></header>   


<div>
<h1><?php echo $this->pagina_name; ?>:</h1>
<div>
<form name="<?php echo $this->form_name; ?>" action="<?php echo $this->pagina_adres; ?>" method="post"
 id="<?php echo $this->form_name; ?>"
style="margin: 0px; padding: 0px;width:40%;float:left;"  enctype="multipart/form-data">

<input type='hidden' name='pagina_numer' value='<?php echo @$this->pagina_numer;  ?>'/>
<input type="hidden" name="Identificador" value="<?php echo @$this->Identificador; ?>" />
<?php
//==============================================================
 include('./PAGINA/'.$this->form_name.'.php');
//=============================================================== 
 ?>


<input type="submit" name ='guardar' style="background: #635c5f;color:#ffffff;" value="guardar"/>



</form>


<form name="navigasia_prev" action="<?php echo $this->pagina_adres; ?>" method="post">
<input type="hidden" name="Identificador" value="<?php echo @$this->Identificador; ?>" />
<?php echo $this->botton_prev;?></form>

<form name="navigasia_next" action="<?php echo $this->pagina_adres; ?>" method="post">
<input type="hidden" name="Identificador" value="<?php echo @$this->Identificador; ?>" />
<?php echo $this->botton_next;?></form>


<div class='verifirar' ><?php echo @$this->procura;?> </div>

<div class='asinatura'>
</div>





</div>





</div>
<!---
<script type="text/javascript">
$('.plus').click(function() {
        $('.Atos').toggle();

   //$('.Atos').clone().appendTo('#primeira');
  });
</script>--->
</body>
</html>