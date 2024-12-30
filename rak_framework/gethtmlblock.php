<?php
/* version: 2  */
/* modified by rahian in version: 2 */

/*

USAGE:

include_once('gethtmlblock.php');

	//getHTMLBlock($url,$startTag,$endTag,$showTags);
	getHTMLBlock('http://finance.yahoo.com/q?s=DUMA%2C+&ql=1','<span id="yfs_c63_duma">','</span>',1);
	

*/

ini_set('display_errors',0);

function getHTMLBlock($url,$startTag,$endTag,$showTags){


if(file_get_contents($url)){

$config['url']       = $url; // url of html to grab
$config['start_tag'] = $startTag; // where you want to start grabbing
$config['end_tag']   = $endTag; // where you want to stop grabbing
$config['show_tags'] = $showTags; // do you want the tags to be shown when you show the html? 1 = yes, 0 = no

class grabber
{
	var $error = '';
	var $html  = '';
	
	function grabhtml( $url, $start, $end )
	{
		$file = file_get_contents( $url );
		
		if( $file )
		{
			if( preg_match_all( "#$start(.*?)$end#s", $file, $match ) )
			{				
				$this->html = $match;
			}
			else
			{
				//$this->error = "N/A";
			}
		}
		else
		{
			$this->error = "Site cannot be found!";
		}
	}
	
	function strip( $html, $show, $start, $end )
	{
		if( !$show )
		{
			$html = str_replace( $start, "", $html );
			$html = str_replace( $end, "", $html );
			
			return $html;
		}
		else
		{
			return $html;
		}
	}
}

$grab = new grabber;
$grab->grabhtml( $config['url'], $config['start_tag'], $config['end_tag'] );

echo $grab->error;

foreach( $grab->html[0] as $html )
{
	//echo htmlspecialchars( $grab->strip( $html, $config['show_tags'], $config['start_tag'], $config['end_tag'] ) ) . "";
	return $grab->strip( $html, $config['show_tags'], $config['start_tag'], $config['end_tag'] )  . "";


}


}
else
{
	return 'Offline';	
}

}
?>