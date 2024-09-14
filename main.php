<?php
class document_todo
{

 

  public function __construct(){ 
   print_r($_POST);
    if ((isset($_POST ['Identificador']))&&($_POST ['Identificador']!=="")) {$Identificador=$_POST ['Identificador'];}
         else {$Identificador=0;}
    
    if (!(isset($_POST ['pagina_numer'])))

    {    $pagina_num=1;       }

    else     {$pagina_num=$_POST ['pagina_numer'];                           }


  if ((isset($_POST ['pagina_next'])))  {$pagina_num=$_POST ['pagina_next']+1; }
  
  
  if ((isset($_POST ['pagina_prev'])))   $pagina_num=$_POST ['pagina_prev']-1;

  if ($pagina_num<=0 ) $pagina_num=1;

  
  $this->pag_next[$pagina_num]=new document_sistema($pagina_num,$Identificador);



}

}
//===============================================================================================================
class document_sistema  extends document_todo
{ 
   
  public function form_element_select ($key,$options ){
      // key-is name  variable
      // $options----- array assosiaision variable for selection, keys=>variable

      $selecd="<select name='".$key."'      form='".$this->form_name."' >";

      if ((isset($this->param_post[$key]))&(!empty($this->param_post[$key])))
           {
            echo $options[$this->param_post[$key]]."<br/>";
         $selecd.="<option value='".$this->param_post[$key]."' selected >".$options[$this->param_post[$key]]."</option>";
          }

      else {$selecd.="<option value='' selected >SELECT</option>";}
     
     //
     foreach($options as $kkey=>$value)
       { $selecd.="<option value='".$kkey."' >".$value."</option>";}

    $selecd.="</select>";
    return $selecd;
}
//================================================================================================
public function selectin_($if_list,$files_tab,$name_tabl)
{   
$if_lll=""; $order_bu="";
if  ((isset($if_list))&& ($if_list!==""))   {$if_list=' WHERE '.$if_list;}
$stroka="SELECT ".$files_tab." FROM  ".$name_tabl."  ".@$if_list;
                 
//ECHO $stroka;               
return ($stroka);
      } 



//=====================================================================================================
public function run_stroka_database_adm($stroka)
{
  //echo $stroka;
	$sth = $this->db->prepare($stroka, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  
    if ($sth->execute()){ ($resulta = ($sth->fetchAll(PDO::FETCH_NAMED)));}
   
   else {     	 $resulta='ERROR SELECT TABL ';           }
  return ($resulta);
}
//============================================================================
  public function insert_records($tabl_name,$fields){
 
    $stra1="";
    $args="";
    $koma1="";
  foreach ($fields as $key=> $args) {
  
    $stra1=$stra1.$koma1. "`".$key."` = :".$key;
    $koma1=",";
  
    $stra2[$key]=($args);
   // echo  "<BR>perem=".$stra2[$key];
    
   }//end foreach
  
  $stroka = "INSERT INTO ".$tabl_name."   SET  ".$stra1;
  echo $stroka;
  
  $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sth = $this->db->prepare($stroka);
  
  if(  $sth->execute($stra2)){$nad = $this->db->lastInsertId();}
   else             { $nad=0;}
  
  return $nad;
  
  }     
//===========================================================

public function update_zap($if_umova,$fields,$param,$name_tabl)
{

    $stra1="";
    $args="";
     $koma="";
   
   //1.path name fields for UPDATE
 	
  	foreach ($fields as $args) { 
  	  	if ($stra1=="") {$koma=" ";}
  	    else {$koma=","; }
      
        if (gettype($args)=="string"){$vv="'".addslashes($param[$args])."'";}
        if (gettype($args)=="integer"){$vv=($param[$args]);}
         
  	    $stra1=$stra1.$koma.'`'.$args."`=".$vv; 
	   } 
   $stroka='UPDATE  '.$name_tabl." SET " .$stra1." ".$if_umova;
echo $stroka;
    $sth = $this->db->prepare($stroka);

   if($sth->execute()) { $flag=1; }
                  else { $flag=0;}

     return $flag;                    
}
//------------------------------------
public function greate_xml_document($list){
  
  $dir_recl='./dir_documentos/document_'.$this->Identificador.'.xml';
  if (file_exists($dir_recl)) 
  {
   $xml_out = simplexml_load_file($dir_recl);
     
     }
 else {
         $xml_out = new SimpleXMLElement('<xml/>');
         
      }
         
 $document = $xml_out->addChild('document');
 foreach( $list as $key)

    {   //echo $key."\n";          
        $document->addAttribute($key,@$this->param_post[$key]);  }

    
  $xml_out -> saveXML($dir_recl);
}
//===========read xml documenta with identificator =================================================
public function read_xml_document_identifikator ($Identificador){

      $dir_recl='./dir_documentos/document_'.$Identificador.'.xml';
      $document_dad=array();
      if (file_exists($dir_recl)) 
         {  
             $xml_out = simplexml_load_file($dir_recl);
             //================para menu==
             $menu="";
              $i=0;
             foreach ($xml_out as $responda){
                  if ($responda['pagina_numer']==$this->pagina_numer){
                      foreach(  $responda->attributes() as $key=>$value )                
                           { $document_dad[$i][$this->pagina_numer][$key]=(string)$value; }
                   $i=$i+1;
                  }
              }
             }
 
return ( $document_dad);

            }


//==================================================
// @$this->param_post['Denominacao']--$par1
//@$this->param_post['Utilizador']----$par2
public function identificao_fazer($par1,$par2){
  $ident="";

 
  $nhex = str_replace(array('-','{','}'), '', $par1);

  // Binary Value
  $nstr = '';

  // Convert Namespace UUID to bits
  for($i = 0; $i <strlen($nhex); $i+=2) {
    $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
  }

  // Calculate hash value
  $hash = md5($nstr . $par2);

  $Identificador= sprintf('%08s-%04s-%04x-%04x-%12s',

    // 32 bits for "time_low"
    substr($hash, 0, 8),

    // 16 bits for "time_mid"
    substr($hash, 8, 4),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 3
    (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

    // 48 bits for "node"
    substr($hash, 20, 12)
  );
   echo "prosedura=".$Identificador;
  return $Identificador;
}

//=======================================================================================================
   public function __construct($num,$Identificador){ 
    $tag_primeira='paginas';
    include('pagina.php');   //------especificação da página
    $movies = new SimpleXMLElement($xmlstr);

    //================para menu==
    $menu="";
 
    foreach ($movies as $responda){
   
      foreach( $responda->attributes() as $key=>$value ){                        
                          $pagina[ ((integer)$responda['num'])][$key]=(string)$value ;
      }
    }
                         

   foreach ($pagina as $key=>$val){
      $pagina[$key]['menu']=" <li><a href='".$pagina[$key]['menu'].
      "' alt='".$pagina[$key]['disri']."' >".$pagina[$key]['disri']." </a></li>";
         $menu.= $pagina[$key]['menu'];    
  } //end foreach
  
  //====================
    $kol=count($movies);
    
    
   
    $this->botton_prev=" <input type='hidden' name='pagina_prev' value='".$num."'/><input type='submit'  value='prev'/>";
    $this->botton_next="<input type='hidden' name='pagina_next' value='".$num."''/><input type='submit'  value='next'/>";

    if ($num==1) {   $this->pagina_numer=1; 
                     
                     $this->botton_prev="";
                    }

   if ($num>=$kol) {   $this->pagina_numer=$kol; 
                      
                      $this->botton_next="";  }           
    
 $this->pagina_numer=$num; 
 $this->pagina_name=$pagina[$this->pagina_numer]['disri'];
 $this->form_name=$pagina[$this->pagina_numer]['name'];
 $this->Estado=$pagina[$this->pagina_numer]['Estado'];
 $this->pagina_adres='index.php';
 $this->menu=$menu;
 $this->date=date('Y-m-d');
 $this->Utilizador=session_id();
 echo  $this->Utilizador."<br>";
 print_r($_SESSION);
 print_r( @$this->param_post);
 //include('shablon.php');
 $this->campos = $pagina[$this->pagina_numer]['campos'];
 $list_campo_para_xml = explode(",", $this->campos);
 $this->Identificador=$Identificador;
//============================================

//---entradas de formulário
foreach ($_POST as $key => $value)
{         $this->param_post[$key]=$value;    }

if (isset($this->param_post['guardar'])){
    

//---se a primeira página criar id do documento
//=================================================================
if (($Identificador==0)){

      $this->Identificador=$this->identificao_fazer($this->param_post['Denominacao'],$this->Utilizador);
      $this->param_post['Identificador']=$this->Identificador;
     }


//==============доастать данные файла страницы если есть======================================================================
//$this->document_dad=$this->read_xml_document_identifikator ($Identificador);
//===========================================================================
//// создание документа 
  if (($this->pagina_numer==1)&&(isset($this->Identificador))){
      $e="";
      $this->db = new pdo('mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8','root','',array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,));
      $tabl_name='documenti_arxivo';
      $list_campo=array('Identificador','Date_arquivo','Estado','Utilizador');
      $list_campo_para_xml = explode(",", $this->campos);
      foreach ($list_campo as $campo) { 
          $fields[$campo] =$this->param_post[$campo];
       }

    $if_list="Identificador='".$fields['Identificador']."'";
    $stroka=$this->selectin_($if_list,'*',$tabl_name);

    $res=$this->run_stroka_database_adm($stroka);
    $if_umova=" where ".$if_list;
    
    if ( count($res)>0) {$result=$this->update_zap($if_umova,$list_,$fields,$tabl_name);}
     else {$result=$this->insert_records($tabl_name,$fields);
         $this->greate_xml_document($list_campo_para_xml);  
           }





}//if $this->pagina_numer==1
//}
}

$this->document_dad=$this->read_xml_document_identifikator ($Identificador);
 
$kol=count($this->document_dad);
if ($kol>0){
  
  foreach ($this->document_dad[0][$this->pagina_numer] as $key => $value){
       $this->param_post[$key]=$value;
  }
}


//============================================================================


 if (($this->pagina_numer==2)){
   

  if (isset($this->param_post['guardar']))
  {$this->greate_xml_document($list_campo_para_xml);}

  $this->document_dad=$this->read_xml_document_identifikator ($Identificador);

  $this->blok="";
  if (isset($this->param_post['plus'])){

 $options=array("procuracao"=>'procuracao',"proc_"=>'proc_00','selecsio'=>'selecsio',"procuracao5"=>'procuracao5'); 
             
 $this->blok="<div>Ato:".$this->form_element_select ('Ato',$options)."</div>
               <div>Data de validade:<input type='date' name='Data_de_validade' value='".$this->date."'/></div>";
     }

     
  
  $this->bloka=""; 
  $kol=count($this->document_dad);
  for ($i=0;$i<$kol;$i++)
    { $this->bloka.="<div class='polosa'><a href='' class='Atos'  >".$this->document_dad[$i][$this->pagina_numer]['Ato']."</a></div>";}
   
     }

//==========================================================




//$this->greate_xml_document($list_campo_para_xml);


/*
    if (isset($this->param_post['navigasia_prev'])){
     // достать данные страници по идентификатору




    }

    if (isset($this->param_post['navigasia_next'])){
      // достать данные страници по идентификатору
 
 
 
 
     }






   $this->greate_xml_document($list_campo_para_xml);

    /*$xmlstr = simplexml_load_file('reestr.xml');
    $id=$identificao;
    $element=$xmlstr->addChild('document',$id."\n");
    foreach($this->param_post as $key => $value)
   {     $element->addAttribute($key,$value);  }

     $xmlstr->asXML('reestr.xml');
*/

 
 
    
 //--------------------------------------------------------------------   
 
   
//---------------------------------------------------------------
include('shablon.php');
  }


}




?>