# Site API #
All responses are in JSON, unless otherwise noted. Bullet points usually indicate keys in the JSON.

## Response Objects ##
### The user object ###
- username (string): The username of the user
- userid (int): The user ID of the user
- usertype (string): The account type, one of "member", "administrator", or "suspended"
- avatar (string): The URL of the user's uploaded avatar. If it doesn't exist, it will be the default user icon.
- groups (array): An array of groups the user belongs to.
- about (string): A description the user set for their profile, or "No about section given" if not set
- location (string): The location set by the user, or "No location set" if not set

### The truncated user object ###
- name (string): The username of the user
- id (int): The user ID of the user

### The collection object ###
 - name (string): The name set for the collection
 - url (string): A URL to the collection page for the collection
 - collection_id (string): A unique id for the collection used to identify it
 - create_time (string): The date and time the collection was created
 - created_by (object): A [truncated user object](#the-truncated-user-object) belonged to the creator
 - downloads (object):
     - this_week (int): The number of downloads this week for this collection
	 - total (int): The total number of downloads for this collection
 - description (string): The uploader's description of the collection

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

### The truncated asset object ###
 - userid (int): The user ID of the asset owner
 - hash (string): The MD5 hash of the asset

## search.php ##
Returns an array of asset or user objects for a given query.
### Request format ###
```http
get /site-api/search.php?query=?
```
 - query (string): The search query, in MySQL boolean match form (+, -, *, and "" supported)
 - sort (string): One of "relevance", "popularity", "date", "alphabetical" default "relevance"
 - dir (string): Sort direction, either "asc" or "desc" default "desc"
 - place (string): One of "both", "names", "descriptions" default "both"
 - filter (string): One of "all", "users", "resources", "collections" default "all"
 - limit (int): The maximum number of results to return, default 20
 - page (int): The offset of the results is defined by `page * limit` so a page of 1 and a limit of 20 would show the second page in pages of 20 results. Default 0

### Response format ###
A JSON object
 - message (string): A message, usually with the number of results
 - num_results (int): The total amount of results (if not paginated)
 - warning (array): An array of strings with syntax errors for the search query, if any
     - *item* (string): A single query syntax warning
 - results (array): An array of asset or user objects (asset objects only, currently) for the results
     - *item* (string): A single asset or user object

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

## report.php ##
Sends the OS team the given report and returns a status message. Limit of one report per minute.
### Request format ###
```http
POST /site-api/report.php
```
- type: The report type, either 'user' or 'asset'
- id: The id of the thing being reported, either a userid for users, or a userid followed by '/' and an asset hash for assets
- reason: A description / justification of the report. Limit of 500 characters.

### Response format ###
A JSON object
 - status: Either "success", "wait", or "error"
 - message: Feedback to display if status is "wait" or "error"

## collection_get.php ##
Gets info about a collection.
### Request format ###
```http
GET /site-api/collection_get.php?userid=?&cid=?
```
- userid: The id of the user who owns the collection
- cid: The id of the collection

### Response format ###
A JSON object
 - info: A [collection object](#the-collection-object)
 - assets: An array of [asset objects](#the-asset-object)

## collection_create.php ##
Creates a new collection. Requires a user to be logged in, and will assign the new collection to the logged in user.
### Request format ###
```http
POST /site-api/collection_create.php
```
 - name: The name to set for the collection
 - assets (optional): A string containing a JSON array of [truncated asset objects](#the-truncated-asset-object)

### Response format ###
A JSON object
 - status: "success" if the operation succeeded, "error" otherwise
 - message: A short message describing the status
 - collection_id: The id string for the new collection (only in the response if the status is "success")

## collection_edit.php ##
Edits the title or description of a collection owned by the logged in user.
### Request format ###
```http
POST /site-api/collection_edit.php
```
 - cid: The collection ID string
 - name: The new name for the collection (limit 32 characters)
 - description: The new description for the collection (limit 500 characters)

### Response format ###
A JSON object
 - status: "success" if the operation succeeded, "error" otherwise
 - message: A short message describing the status
 - collection_info: The "info" part of the [collection object](#the-collection-object) which contains the updated info.

## collection_add.php ##
Adds assets to a collection owned by the logged in user.
### Request format ###
```http
POST /site-api/collection_edit.php
```
 - cid: The collection ID string
 - assets: A string containing a JSON array of [truncated asset objects](#the-truncated-asset-object)

### Response format ###
A JSON object
 - status: "success" if the operation succeeded, "error" otherwise
 - message: A short message describing the status

## collection_remove.php ##
Removes assets from a collection owned by the logged in user.
### Request format ###
```http
POST /site-api/collection_edit.php
```
 - cid: The collection ID string
 - assets: A string containing a JSON array of [truncated asset objects](#the-truncated-asset-object)

### Response format ###
A JSON object
 - status: "success" if the operation succeeded, "error" otherwise
 - message: A short message describing the status