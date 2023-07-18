<?php session_start();
// if(!isset($_SESSION['userId'])){
//     $_SESSION['userId'] = 10392424371;
// }
require 'config.php'; 
if (isset($_POST['upload'])) {
	$doc=$_FILES['file']['name'];
	$file=explode('.', $doc);
    $name = $file[0];
    $price = 'Free';
    if(isset($_POST['name'])){
        $name = $_POST['name'];
    }
    if(isset($_POST['price'])){
        $price = $_POST['price'];
    }
	$ext=end($file);
	$ext=strtolower($ext);
    $_SESSION['code'] = uniqid();
	if($ext=='pdf' || $ext=='doc' || $ext=='docx' || $ext=='xlsx' || $ext=='pptx' || $ext=='txt'){
		$book1='../document/'.time().'.'.$ext;
		$book2='document/'.time().'.'.$ext;
		move_uploaded_file($_FILES['file']['tmp_name'], $book1);
        echo $_SESSION['userID'].'ww';
		$sql="INSERT into book set b_name=?,b_src=?,b_code=?,b_user=?,b_price=?";
		$book=$pdo->prepare($sql);
		$book->bindValue(1,$name);
        $book->bindValue(2,$book2);
        $book->bindValue(3,$_SESSION['code']);
        $book->bindValue(4,$_SESSION['userID']);
		$book->bindValue(5,$price);
		$book->execute();
	}else{
	echo 'no';
}
	if ($ext=='pdf') {
		include ( 'PdfToText.phpclass' ) ;

	function  output ( $message )
	   {
		if  ( php_sapi_name ( )  ==  'cli' )
			echo ( $message ) ;
		else
			echo ( nl2br ( $message ) ) ;
	    }

	$file	=  $book1 ;
	$pdf	=  new PdfToText ( "$file" ) ;
	$word =  output ( $pdf -> Text ) ;
	}

   class DocxConversion{
    private $filename;

    public function __construct($filePath) {
        $this->filename = $filePath;
    }

    private function read_doc() {
        $fileHandle = fopen($this->filename, "r");
        $line = @fread($fileHandle, filesize($this->filename));   
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
          {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
              {
              } else {
                $outtext .= $thisline." ";
              }
          }
         $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }

    private function read_docx(){

        $striped_content = '';
        $content = '';

        $zip = zip_open($this->filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

 /************************excel sheet************************************/

function xlsx_to_text($input_file){
    $xml_filename = "xl/sharedStrings.xml"; //content file name
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text = strip_tags($xml_handle->saveXML());
        }else{
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}

/*************************power point files*****************************/
function pptx_to_text($input_file){
    $zip_handle = new ZipArchive;
    $output_text = "";
    if(true === $zip_handle->open($input_file)){
        $slide_number = 1; //loop through slide files
        while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false){
            $xml_datas = $zip_handle->getFromIndex($xml_index);
            $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            $output_text .= strip_tags($xml_handle->saveXML());
            $slide_number++;
        }
        if($slide_number == 1){
            $output_text .="";
        }
        $zip_handle->close();
    }else{
    $output_text .="";
    }
    return $output_text;
}


    public function convertToText() {

        if(isset($this->filename) && !file_exists($this->filename)) {
            return "File Not exists";
        }

        $fileArray = pathinfo($this->filename);
        $file_ext  = $fileArray['extension'];
        if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx")
        {
            if($file_ext == "doc") {
                return $this->read_doc($this->filename);
            } elseif($file_ext == "docx") {
                return $this->read_docx($this->filename);
            } elseif($file_ext == "xlsx") {
                return $this->xlsx_to_text($this->filename);
            }elseif($file_ext == "pptx") {
                return $this->pptx_to_text($this->filename);
            }
        } else {
            return "Invalid File Type";
        }
    }
}

if($ext=='doc'){
	$docObj = new DocxConversion($book1);
	$docText= $docObj->convertToText(); 
 echo $string = preg_replace("/[^a-z0-9 ]+/i", "", $docText);
}else if($ext=='docx'){
	$docObj = new DocxConversion($book1);
	$docText= $docObj->convertToText(); 
 echo $string = preg_replace("/[^a-z0-9 ]+/i", "", $docText);
}else if($ext=='xlsx'){
	$docObj = new DocxConversion($book1);
	$docText= $docObj->convertToText(); 
 echo $string = preg_replace("/[^a-z0-9 ]+/i", "", $docText);
}else if($ext=='pptx'){
	$docObj = new DocxConversion($book1);
    $docText= $docObj->convertToText(); 
 echo $string = preg_replace("/[^a-z0-9 ]+/i", "", $docText);
}
else if($ext=='txt'){
$file_name = $book1;
$file_handler = fopen($file_name, 'r');
$line_number = 0;
$reading_file_line_by_line = "";
while(!feof($file_handler)){ 
    $line = fgets($file_handler);
    if($line_number == 0){ 
        $line1 = $line;
    }
    $reading_file_line_by_line .= $line;
    $line_number++; 
}
fclose($file_handler);
$file_handler = fopen($file_name, 'r');

$whole_file = fread($file_handler, filesize($file_name)); 
fclose($file_handler);

 echo $string = preg_replace("/[^a-z0-9 ]+/i", "", $whole_file);

}

}
?>