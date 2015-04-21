function getAssetList($raw){
  $assets = array();
  for($i=0;$i<sizeof($raw);$i++){
	  $asset = $raw[$i];
	  $obj = array(
	  	"name" => $asset['customName'],
	  	"type" => $asset['assetType'],
	  	"url" => "/uploads/uploaded/" . $asset['name'],
	  	"md5" => $asset['hash'],
	  	"upload_time" => $asset['date'],
	  	"uploaded_by" => array(
	  		"name" => $asset["user"],
	  		"id" => $asset["userid"]
	  	)
	  );
	  array_push($assets, $obj);
  }
}
