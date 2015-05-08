# modal.js
TODO: I'm not sure how to write documentation. *- GC*

###Option One
```js
//                                      unique name, [contents,            submit,                                         text for buttons
var myEpicModal = new OpenSprites.modal('EpicModal', '<h1>EpicModal</h1>', function(parentModal) {alert('You go away!');}, {submit: 'Go away!', cancel: 'Okay'});

myEpicModal.show();
```

###Option Two
```js
var myMoreEpicModal = new OpenSprites.modal({
    name: 'lickRequest',
    content: '<h1>Lick me!</h1>',
    submit: function(t) {
        console.log(t);
        $(t.content).html('<h1>Good job!</h1>');
    },
    buttons: {
        submit: 'Licky licky!',
        cancel: 'No way!'
    }
});

myMoreEpicModal.show();
```

# models.js #
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
 - updateJson(json:array):void: Call this to populate the asset list. The parameter needs to be an array of asset objects (see site-api docs)

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

### On pages ###
 - The user page (user/index.php)

## SortableAssetList(target) ##
Like an asset list, except it also adds buttons to sort the data. Makes JSON requests by itself, no need to call updateJson. Uses AssetList internally.
### Constructor ###
 - target:jQuery: The HTML element to put the sortable asset list in

### Example ###
```html
<div id='my-sortable-asset-list'></div>
<script>
var model = OpenSprites.models.SortableAssetList($("#my-sortable-asset-list"));
// that's it!
</script>
```

### On pages ###
 - The front page (index.php)

## ScriptPreview(target) ##
Shows a scratchblocks2 preview of a script as a background image on the given target
### Constructor ###
 - target:jQuery: The HTML element to put this model in

### Methods ###
 - updateJson(json:array):void: Load the specified script into scratchblocks2 and show its preview.
