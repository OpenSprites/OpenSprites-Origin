# Site API #
All responses are in JSON, unless otherwise noted. Bullet points usually indicate keys in the JSON.

## Response Objects ##
### The user object ###
- username (string): The username of the user
- userid (int): The user ID of the user
- usertype (string): The account type, one of "member", "administrator", or "suspended"
- avatar (string): The URL of the user's uploaded avatar. If it doesn't exist, it will be the default user icon.
- groups (array): An array of groups the user belongs to.

### The truncated user object ###
- name (string): The username of the user
- id (int): The user ID of the user

### The asset object ###
 - name (string): The name set for the asset
 - type (string): One of "image" "sound" or "script"
 - url (string): The direct link to the asset
 - md5 (string): The MD5 hash of the asset
 - upload_time (string): The date and time the asset was uploaded
 - uploaded_by (object): A [truncated user object](#the-truncated-user-object) belonged to the uploader
 - downloads (object):
     - this_week (int): The number of downloads this week for this asset
	 - total (int): The total number of downloads for this asset
 - description (string): The uploader's description of the asset
 
## stuff.php ##
Returns an array of [asset objects](#the-asset-object) belonging to the specified user
### Request format ###
```http
GET /site-api/stuff.php?userid=?
```
- userid: The id of the user to get assets from

### Response format ###
A JSON array of [asset objects](#the-asset-object)

## list.php ##
Returns an array of [asset objects](#the-asset-object) according to the given parameters
### Request format ###
```http
GET /site-api/list.php?max=?&sort=?&type=?
```
- max: The maximum number of items to return
- sort: One of "popularity" "alphabetical" "newest" or "oldest"
- type: One of "all" "image" "sound" or "script"

### Response format ###
A JSON array of [asset objects](#the-asset-object)

## asset.php ##
Returns an [asset object](#the-asset-object) with the given hash and owner, if one exists
### Request format ###
```http
GET /site-api/asset.php?userid=?&hash=?
```
- userid: The id of the owner of the asset
- hash: The md5 hash of the asset

### Response format ###
An [asset object](#the-asset-object)

## user.php ##
Returns the [user object](#the-user-object) of a given userid
### Request format ###
```http
GET /site-api/user.php?userid=?
```
- userid: The userid of a user

### Response format ###
A JSON [user object](#the-user-object)
