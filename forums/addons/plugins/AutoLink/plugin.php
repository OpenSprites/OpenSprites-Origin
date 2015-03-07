<?php

if (!defined("IN_ESOTALK")) exit;

 // An implementation of the string filter interface for plain text strings
ET::$pluginInfo["AutoLink"] = array(
	"name" => "AutoLink",
	"description" => "When you post an URL, AutoLinks automatically embeds images, MP3, videos.Turns other URLs in to a clickable link",
	"version" => "1.2.0",
	"author" => "Ramouch0",
	"authorEmail" => "support@esotalk.org",
	"authorURL" => "http://esotalk.org",
	"license" => "GPLv2"
);

class ETPlugin_AutoLink extends ETPlugin {

	// ACCEPTED PROTOTYPES
	//
	var $accepted_protocols = array(
	  'http://', 'https://', 'ftp://', 'ftps://', 'mailto:', 'telnet://',
	  'news://', 'nntp://', 'nntps://', 'feed://', 'gopher://', 'sftp://' );

	//
	// AUTO-EMBED IMAGE FORMATS
	//
	var $accepted_image_formats = array(
	  'gif', 'jpg', 'jpeg', 'tif', 'tiff', 'bmp', 'png', 'svg', 'ico' );


public function handler_format_format($sender)
{
	// quick check to rule out complete wastes of time
	if( strpos( $sender->content, '://' ) !== false || strpos($sender->content, 'mailto:' ) !== false )
	{
	  $sender->content = preg_replace_callback( '/(?<=^|\r\n|\n| |\t|<br>|<br\/>|<br \/>)!?([a-z]+:(?:\/\/)?)([^ <>"\r\n\?]+)(\?[^ <>"\r\n]+)?/i', array( &$this, 'autoLink' ), $sender->content );
	 }
}

public function autoLink( $link = array())
{
  // $link[0] = the complete URL
  // $link[1] = link prefix, lowercase (e.g., 'http://')
  // $link[2] = URL up to, but not including, the ?
  // $link[3] = URL params, including initial ?

  // sanitise input
  $link[1] = strtolower( $link[1] );
  if( !isset( $link[3] ) ) $link[3] = '';

  // check protocol is allowed
  if( !in_array( $link[1], $this->accepted_protocols ) ) return $link[0];

  // check for forced-linking and strip prefix
  $forcelink = substr( $link[0], 0, 1 ) == '!';
  if( $forcelink ) $link[0] = substr( $link[0], 1 );

  $params = array();
  $matches = array();

  
  if( !$forcelink && ( $link[1] == 'http://' || $link[1] == 'https://' ) )
  {
	$width = 400;
	$height = 225;
	// images
	if( preg_match( '/\.([a-z]{1,5})$/i', $link[2], $matches ) && in_array( strtolower( $matches[1] ), $this->accepted_image_formats ) )
	  return '<img class="auto-embedded" src="'.$link[1].$link[2].$link[3].'" alt="-image-" title="'.$link[1].$link[2].$link[3].'" />';
	// mp3s
	else if( strtolower( substr( $link[2], -4 ) ) == '.mp3' )
	  return '<embed class="video" width="240" height="24" src="http://skreemr.com/audio/player.swf" type="application/x-shockwave-flash" flashvars="playerID=1&soundFile='.$link[0].'" align="top" />';
	// youtube
	else if( strcasecmp( 'www.youtube.com/watch', $link[2] ) == 0 && $this->params( $params, $link[3], 'v' ) )
	  return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$params['v'].'" frameborder="0" allowfullscreen></iframe>';
	else if( preg_match( '/^(?:www\.)?youtu\.be\/([^\/]+)/i', $link[2], $matches ))
	  return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe>';
	
	// google video
	else if( preg_match( '/^(video\.google\.co(?:m|\.uk))\/videoplay$/i', $link[2], $matches ) && $this->params( $params, $link[3], 'docid' ) )
	  return '<embed class="video" style="width:'.$width.'px;height:'.$height.'px" allowfullscreen="true" src="'.$link[1].$matches[1].'/googleplayer.swf?docid='.$params['docid'].'&fs=true" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" align="top" />';
	// vimeo
	else if( preg_match( '/^(?:www\.)?vimeo\.com\/([^\/]+)/i', $link[2], $matches ) )
	return '<iframe src="http://player.vimeo.com/video/'.$matches[1].'?byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	// metacafe
	else if( preg_match( '/^www\.metacafe\.com\/watch\/([0-9]+)\/([^\/]+)\/?$/', $link[2], $matches ) )
	  return '<embed class="video" src="http://www.metacafe.com/fplayer/'.$matches[1].'/'.$matches[2].'.swf"  width="'.$width.'" height="'.$height.'" wmode="transparent" type="application/x-shockwave-flash" allowfullscreen="true" />';
	// dailymotion
	else if( preg_match( '/^www\.dailymotion\.com\/(?:[a-z]+\/)?video\/([^\/]+)/i', $link[2], $matches ) )
	  return '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.dailymotion.com/embed/video/'.$matches[1].'"></iframe>';
	// myspace
	else if( preg_match( '/^(?:(?:www|vids)\.)?myspace\.com\/index\.cfm$/', $link[2] ) && $this->params( $params, $link[3], array( 'fuseaction', 'VideoID' ) ) && $params['fuseaction'] == 'vids.individual' )
	  return '<embed class="video" src="http://lads.myspace.com/videos/vplayer.swf" flashvars="m='.$params['VideoID'].'&v=2&type=video" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" align="top" />';
	// spike
	else if( preg_match( '/^[a-z]+\.spike\.com\/video\/(?:.+\/)?([-0-9a-z]+)$/', $link[2], $matches ) )
	  return '<embed class="video"  width="'.$width.'" height="'.$height.'" src="http://www.spike.com/efp" type="application/x-shockwave-flash" flashvars="flvbaseclip='.$matches[1].'" allowfullscreen="true" align="top" />';
	// redlasso
	else if( strcasecmp( 'redlasso.com/ClipPlayer.aspx', $link[2] ) == 0 && $this->params( $params, $link[3], 'id' ) )
	  return '<embed class="video" src="http://media.redlasso.com/xdrive/WEB/vidplayer_1b/redlasso_player_b1b_deploy.swf" flashvars="embedId='.$params['id'].'"  width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" align="top" />';
	// onsmash.com
	else if( preg_match( '/^videos\.onsmash\.com\/v\/([a-z0-9A-Z]+)/', $link[2], $matches ) )
	  return '<embed class="video" src="http://videos.onsmash.com/e/'.$matches[1].'" type="application/x-shockwave-flash" allowfullscreen="true"  width="'.$width.'" height="'.$height.'" align="top" />';
	// tangle.com
	else if( strcasecmp( 'www.tangle.com/view_video.php', $link[2] ) == 0 && $this->params( $params, $link[3], 'viewkey' ) )
	  return '<embed class="video" src="http://www.tangle.com/flash/swf/flvplayer.swf" type="application/x-shockwave-flash" allowscriptaccess="always" flashvars="viewkey='.$params['viewkey'].'" wmode="transparent"  width="'.$width.'" height="'.$height.'" align="top" />';
	// LiveLeak.com
	else if( strcasecmp( 'www.liveleak.com/view', $link[2] ) == 0 && $this->params( $params, $link[3], 'i' ) )
	  return '<embed class="video" src="http://www.liveleak.com/e/'.$params['i'].'" type="application/x-shockwave-flash" wmode="transparent"  width="'.$width.'" height="'.$height.'" align="top" />';
  }


  // default to linkifying
	return '<a href="'.$link[0].'" rel="nofollow external">'.$link[0].'</a>';

}

/*Reads query parameters
params : result array as key => value
string : query string
required : array of required parameters key
@return true if required parameters are present.
*/
function params( &$params, $string, $required )
{
  $string = html_entity_decode($string);
  if( !is_array( $required ) ) $required = array( $required );
  if( substr( $string, 0, 1 ) == '?' ) $string = substr( $string, 1 );
  $params = array();
  $bits = split( '&', $string );
  foreach( $bits as $bit ) {
	$pair = split( '=', $bit, 2 );
	if( in_array( $pair[0], $required ) ) $params[ $pair[0] ] = $pair[1];
  }
  return count( $required ) == count( $params );
}

}
?>