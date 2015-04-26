# Includes documentation #
## database.php ##
### Variables (absolutely do not to use outside of database.php) ###
 - $username: The username to the assets database
 - $password: The password to the assets database
 - $db_name: The name of the assets database
 - $assets_table_name: The name of the assets table in the assets database
 - $dbh: The database handle
 - $forum_username: The username to the forums database
 - $forum_password: The password to the forums database
 - $forum_db_name: The name of the forums database
 - $forum_dbh: The forums database handle
 - $forum_member_table: The name of the table listing forum members
 - $forum_group_table: The name of the table listing forum groups
 - $forum_group_member_table: The name of the table mapping members to groups

### Functions ###
 - connectDatabase():void: Connects to the assets database and initializes $dbh
 - getDatabaseError():array: Returns the last PDO error, if any. See the [PDO documentation](http://php.net/manual/en/pdo.errorinfo.php) for more info
 - getAssetsTableName():string: Returns the name of the assets table. Use this rather than the actual variable.
 - imagesQuery($query:string, $parameters:array):array: Returns an associative array of PDO results for the given string and parameters
   - Example: ```imagesQuery("SELECT * FROM `".getAssetsTableName()."` WHERE `userid`=? AND `hash`=?", array($userid_you_want, $hash_you_want))```
   - TODO: Rename this to something more descriptive
 - imagesQuery0($query:string, $parameters:array):void: like imagesQuery(), but does not expect a result.
   - TODO: Rename this to something more descriptive
 - getAllImages():array: Returns an associative array of all entries in the assets table.
   - TODO: Rename this. "Images" is because a lot of this code is from picturit, which only handled images.
 - getImagesForUser($userid:int):array: Returns an associative array of assets belonging to the user with the given user id
 - createImagesTable():void: Creates the assets table in the assets database. Used internally on connectDatabase()
 - createUserUploadTable():void: Creates the table linking users to bytes uploaded and last upload time. This table is used for spam control. Function used internally on connectDatabase()
 - imageExists($hash:string):boolean: Checks if an asset already exists in the database, returns true if so, false otherwise.
 - addImageRow($fileName:string, $hash:string, $user:string, $userId:int, $assetType:string, $customName:string):void: Creates a row in the assets table with the given details. $fileName is the actual filename in /uploads/uploaded/, $customName is the display name. $assetType is one of "image" "sound" or "script"
 - tableExists($name:string):boolean: Checks for the given table in the database, returns true if it exists, false otherwise. Used internally to create the assets table if it does not exist.
 - isUserAbleToUpload($userid:int, $post_bytes:int):void: Checks if the user with the given id is able to upload the given size of files. Returns TRUE if allowed, and the number of seconds left before the user is able to upload if not allowed.
 - incrementDownload($userid:int, $hash:int):void: Increments the download count for the asset with the given hash and uploader id.
 - connectForumDatabase():void: Connects to the esotalk database
 - forumQuery($query:string, $params:array):array: Like imagesQuery, except runs on the forum database
 - getUserInfo($userid:string):array: Retrieves info about a user, with the given id. Return array is formatted slightly. Contents of the return array:
   - userid:int: The id of the user
   - username:string: The username of the user
   - usertype:string: One of "member" "administrator" or "suspended"
   - groups:array: A non-associative array of the groups the user is in, ie "Moderator" or "OpenSprites Developer"
 - setAccountType($username:string, $type:string):void: Set the type of account for the given user. $type is one of "administrator", "member", or "suspended"
 - 

## connect.php ##
### Automatic actions ###
Connects the esotalk session and sets global variables.
 - $is_admin:boolean: If the user is an administrator
 - $logged_in_userid:int: The userid of the logged in user, or 0 if not logged in
 - $logged_in_user:string: The username of the logged in user, or "not logged in" if not logged in
 - $user_group:string: The account type of the user, one of "member" "administrator" or "suspended"
 - $user_banned:boolean: True if the user is suspended, false otherwise