# Using models.js #
All models are located in OpenSprites.model
## BaseModel(target) ##
Empty base class, use this for creating new models
### Constructor ###
 - target:jQuery: The HTML element to put this model in
### Methods ###
 - updateJson(json:object|array):void: Extend this to perform some action on a server JSON update.

## AssetList(target) ##
Shows a scrollable horizontal list of assets
### Constructor ###
 - target:jQuery: The HTML element to put the asset list in
### Methods ###
 - updateJson(json:object|array):void: Call this to populate the asset list
### Example ###
```html
<div id='my-asset-list'></div>
<script>
var model = OpenSprites.models.AssetList($("#my-asset-list"));
$.get("my-api-endpoint", function(data){
  model.updateJson(data);
});
</script>
```
## SortableAssetList(target) ##
Like an asset list, except it also adds buttons to sort the data. Makes JSON requests by itself, no need to call updateJson
### Constructor ###
 - target:jQuery: The HTML element to put the sortable asset list in
### Example ###
```html
<div id='my-sortable-asset-list'></div>
<script>
var modelOpenSprites.models.SortableAssetList($("#my-sortable-asset-list"));
// that's it!
</script>
```
