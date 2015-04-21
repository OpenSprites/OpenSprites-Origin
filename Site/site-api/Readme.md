# Site API #
All responses are in JSON, unless otherwise noted. Bullet points usually indicate keys in the JSON.

## Response Objects ##
### The user object ###
- name (string): The username of the user
- id (int): The user ID of the user

### The asset object ###
 - name (string): The name set for the asset
 - type (string): One of "image" "sound" or "script"
 - url (string): The direct link to the asset
 - md5 (string): The MD5 hash of the asset
 - upload_time (string): The date and time the asset was uploaded
 - uploaded_by (object): A user object for the uploader
 
## stuff.php ##
Returns an array of asset objects belonging to the specified user
### Request format ###
```http
GET /site-api/stuff.php?userid=?
```
- userid: The id of the user to get assets from

### Response format ###
A JSON array of asset objects

## list.php ##
Returns an array of asset objects according to the given parameters
### Request format ###
```http
GET /site-api/list.php?max=?&sort=?&type=?
```
- max: The maximum number of items to return
- sort: One of "popularity" "alphabetical" "newest" or "oldest"
- type: One of "all" "image" "sound" or "script"

### Response format ###
A JSON array of asset objects

## user.php ##
No documentation yet
