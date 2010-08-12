<?php
error_reporting(E_ALL);
set_time_limit(20);


function reg_escape( $str ) {
    $conversions = array( "^" => "\^", "[" => "\[", "." => "\.", "$" => "\$", "{" => "\{", "*" => "\*", "(" => "\(", "\\" => "\\\\", "/" => "\/", "+" => "\+", ")" => "\)", "|" => "\|", "?" => "\?", "<" => "\<", ">" => "\>" );
    return strtr( $str, $conversions );
}

/**
* Strip attribute Class
* Remove attributes from XML elements
* @author David (semlabs.co.uk)
* @version 0.2.1
*/

class StripAttributes
{

	public $str		= '';
	public $allow		= array();
	public $exceptions	= array();
	public $ignore		= array();

	public function strip( $str )
	{
		$this->str = $str;

		if( is_string( $str ) && strlen( $str ) > 0 )
		{
			$res = $this->findElements();
			if( is_string( $res ) )
				return $res;
			$nodes = $this->findAttributes( $res );
			$this->removeAttributes( $nodes );
		}

		return $this->str;
	}

	private function findElements()
	{

		# Create an array of elements with attributes
		$nodes = array();
		preg_match_all( "/<([^ !\/\>\n]+)([^>]*)>/i", $this->str, $elements );
		foreach( $elements[1] as $el_key => $element )
		{
			if( $elements[2][$el_key] )
			{
				$literal = $elements[0][$el_key];
				$element_name = $elements[1][$el_key];
				$attributes = $elements[2][$el_key];
				if( is_array( $this->ignore ) && !in_array( $element_name, $this->ignore ) )
					$nodes[] = array( 'literal' => $literal, 'name' => $element_name, 'attributes' => $attributes );
			}
		}

		# Return the XML if there were no attributes to remove
		if(!isset($nodes[0]))
			return $this->str;
		else
			return $nodes;
	}

	private function findAttributes( $nodes )
	{

		# Extract attributes
		foreach( $nodes as &$node )
		{
                        $atts = array();
			preg_match_all( "/([^ =]+)\s*=\s*[\"|']{0,1}([^\"']*)[\"|']{0,1}/i", $node['attributes'], $attributes );
			if( $attributes[1] )
			{
				foreach( $attributes[1] as $att_key => $att )
				{
					$literal = $attributes[0][$att_key];
					$attribute_name = $attributes[1][$att_key];
					$value = $attributes[2][$att_key];
					$atts[] = array( 'literal' => $literal, 'name' => $attribute_name, 'value' => $value );
				}
			}
			else
				$node['attributes'] = null;

			$node['attributes'] = $atts;
			unset( $atts );
		}

		return $nodes;
	}

	private function removeAttributes( $nodes )
	{

		# Remove unwanted attributes
		foreach( $nodes as $node )
		{

			# Check if node has any attributes to be kept
			$node_name = $node['name'];
			$new_attributes = '';
			if( is_array( $node['attributes'] ) )
			{
				foreach( $node['attributes'] as $attribute )
				{
					if( ( is_array( $this->allow ) && in_array( $attribute['name'], $this->allow ) ) || $this->isException( $node_name, $attribute['name'], $this->exceptions ) )
						$new_attributes = $this->createAttributes( $new_attributes, $attribute['name'], $attribute['value'] );
				}
			}
			$replacement = ( $new_attributes ) ? "<$node_name $new_attributes>" : "<$node_name>";
			$this->str = preg_replace( '/'. reg_escape( $node['literal'] ) .'/', $replacement, $this->str );
		}

	}

	private function isException( $element_name, $attribute_name, $exceptions )
	{
		if( array_key_exists($element_name, $this->exceptions) )
		{
			if( in_array( $attribute_name, $this->exceptions[$element_name] ) )
				return true;
		}

		return false;
	}

	private function createAttributes( $new_attributes, $name, $value )
	{
		if( $new_attributes )
			$new_attributes .= " ";
		$new_attributes .= "$name=\"$value\"";

		return $new_attributes;
	}
}



function stripArgumentFromTags( $t2 ) {
    global $sa;
     $sa->allow = array();
 $sa->exceptions = array(
     'img' => array( 'src', 'alt' ),
     'a' => array( 'href', 'title' )
 );
 $sa->ignore = array();
 $t2 = $sa->strip( $t2 );
$t2 = str_replace('<br>','<br />',$t2);
$t2 = str_replace('<b>','<strong>',$t2);
$t2 = str_replace('</b>','</strong>',$t2);
    $t2 = str_replace('<br/>','<br />',$t2);
                $t2 = str_replace('<h1><strong>','<h1>',$t2);
                $t2 = str_replace('</strong></h1>','</h1>',$t2);
                $t2 = str_replace('<span><br /></span>','',$t2);
                $t2 = str_replace('<p><br />','<p>',$t2);
                $t2 = str_replace('<p></p>','',$t2);
                $t2 = str_replace('<table>','',$t2);
                $t2 = str_replace('<tr>','',$t2);
                $t2 = str_replace('<td>','',$t2);

                $t2 = str_replace('</td>','',$t2);
                $t2 = str_replace('</tr>','',$t2);
                $t2 = str_replace('</table>','',$t2);
                $t2 = str_replace('<span><span>','<span>',$t2);
                $t2 = str_replace('</span></span>','</span>',$t2);
                $t2 = str_replace('<p> ','<p>',$t2);
                $t2 = str_replace('’','\'',$t2);
                $t2 = str_replace('â€¢','-',$t2);
                $t2 = str_replace('<div>','',$t2);
                $t2 = str_replace('</div>','',$t2);
                $t2 = str_replace('<span>','',$t2);
                $t2 = str_replace('</span>','',$t2);
                $t2 = str_replace('<br /><br />','<br />',$t2);
                $t2 = str_replace('<br /><br />','<br />',$t2);
                $t2 = str_replace('<br /><br />','<br />',$t2);
                $t2 = str_replace('<br /><br />','<br />',$t2);
                $t2 = str_replace('<br /><br />','<br />',$t2);
                $t2 = str_replace('<a>','',$t2);
                $t2 = trim(str_replace('<br /></p>','</p>',$t2));

    return $t2;
}
$sa = new StripAttributes();
$xml = simplexml_load_file('app/protected/pages/scrape/map.xml');
$t2 = '';
$i = 0;
foreach($xml as $x) {
    $x = str_replace("\n",'',$x);
    if(strpos($x,'.html')) {
        $handle = fopen('app/protected/pages/scrape/cache/'.$x, "r");
        $html = fread($handle, filesize('app/protected/pages/scrape/cache/'.$x));
        fclose($handle);
        echo $x.' - ';

        //echo substr($x,0,13);
        if(substr($x,0,20)=='products.html?depid=') {
            echo 'PRODUCT DEPARTMENT';
            
            $dom = new DOMDocument;
           $dom->loadHTML($html);
           $xpath = new DOMXPath($dom);
           $titleo = $xpath->query("/html/head/title");
           foreach($titleo as $t2) {
                $titleo2 = trim(substr(strip_tags($dom->saveXML($t2)),22));
           }

           $contento = $xpath->query("/html/body/center/div/table/tr[3]/td/table/tr/td[3]");

           $flag = false;
           foreach($contento as $t2) {
                $t2 = stripArgumentFromTags($dom->saveXML($t2));
                $flag = true;
                $contento2 = $t2;

           }
           if($flag==false) {
                $contento = $xpath->query("/html/body/center/div/table/tr[3]/td");
                $flag = false;
                foreach($contento as $t2) {
                    $t2 = stripArgumentFromTags($dom->saveXML($t2));
                    $flag = true;
                    $contento2 = $t2;
                }
           }
           //echo $contento2;
           $contento2 = trim(str_replace('â€¢','-',mysqli_real_escape_string($_dbconn,$contento2)));
           $query = "INSERT INTO `tmp_pages` (filename,title,content) VALUES ('$x','$titleo2','$contento2')";
           db_query($query);
             
            //exit();
        }
        else if(substr($x,0,21)=='products.html?prodid=') {
            echo 'PRODUCT';
            
            $dom = new DOMDocument;
           $dom->loadHTML($html);
           $xpath = new DOMXPath($dom);
           $titleo = $xpath->query("/html/head/title");
           foreach($titleo as $t2) {
                $titleo2 = trim(substr(strip_tags($dom->saveXML($t2)),22));
           }

           $contento = $xpath->query("/html/body/center/div/table/tr[3]/td/table/tr/td[3]");

           $flag = false;
           foreach($contento as $t2) {
                $t2 = stripArgumentFromTags($dom->saveXML($t2));
                $flag = true;
                $contento2 = $t2;

           }
           if($flag==false) {
                $contento = $xpath->query("/html/body/center/div/table/tr[3]/td");
                $flag = false;
                foreach($contento as $t2) {
                    $t2 = stripArgumentFromTags($dom->saveXML($t2));
                    $flag = true;
                    $contento2 = $t2;
                }
           }
           //echo $contento2;
           $contento2 = trim(str_replace('â€¢','-',mysqli_real_escape_string($_dbconn,$contento2)));
           $query = "INSERT INTO `tmp_pages` (filename,title,content) VALUES ('$x','$titleo2','$contento2')";
           //db_query($query);
           
           //exit();
        }
        else if(substr($x,0,20)=='products.html?catid=') {
            echo 'PRODUCT CATEGORY';
            
             $dom = new DOMDocument;
           $dom->loadHTML($html);
           $xpath = new DOMXPath($dom);
           $titleo = $xpath->query("/html/head/title");
           foreach($titleo as $t2) {
                $titleo2 = trim(substr(strip_tags($dom->saveXML($t2)),22));
           }

           $contento = $xpath->query("/html/body/center/div/table/tr[3]/td/table/tr/td[3]");

           $flag = false;
           foreach($contento as $t2) {
                $t2 = stripArgumentFromTags($dom->saveXML($t2));
                $flag = true;
                $contento2 = $t2;

           }
           if($flag==false) {
                $contento = $xpath->query("/html/body/center/div/table/tr[3]/td");
                $flag = false;
                foreach($contento as $t2) {
                    $t2 = stripArgumentFromTags($dom->saveXML($t2));
                    $flag = true;
                    $contento2 = $t2;
                }
           }
           //echo $contento2;
           $contento2 = trim(str_replace('â€¢','-',mysqli_real_escape_string($_dbconn,$contento2)));
           $query = "INSERT INTO `tmp_pages` (filename,title,content) VALUES ('$x','$titleo2','$contento2')";
           db_query($query);
           
           //exit();
        }
        else {
           
           echo 'OTHER';
           $dom = new DOMDocument;
           $dom->loadHTML($html);
           $xpath = new DOMXPath($dom);
           $titleo = $xpath->query("/html/head/title");
           foreach($titleo as $t2) {
                $titleo2 = trim(substr(strip_tags($dom->saveXML($t2)),22));
           }

           $contento = $xpath->query("/html/body/center/div/table/tr[3]/td/table/tr/td[1]");

           $flag = false;
           foreach($contento as $t2) {
                $t2 = stripArgumentFromTags($dom->saveXML($t2));
                $flag = true;
                $contento2 = $t2;
               
           }
           if($flag==false) {
                $contento = $xpath->query("/html/body/center/div/table/tr[3]/td");
                $flag = false;
                foreach($contento as $t2) {
                    $t2 = stripArgumentFromTags($dom->saveXML($t2));
                    $flag = true;
                    $contento2 = $t2;
                }
           }
           $contento2 = trim(str_replace('â€¢','-',mysqli_real_escape_string($_dbconn,$contento2)));
           $query = "INSERT INTO `tmp_pages` (filename,title,content) VALUES ('$x','$titleo2','$contento2')";
           db_query($query);
           
        }
        echo '<br />';

        //if($i==3)
           // exit();
        $i++;
    }



}
echo 'FINISHED!';
?>