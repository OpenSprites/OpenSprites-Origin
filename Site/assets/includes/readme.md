# Includes documentation #
## database.php ##
### Variables (absolutely do not to use outside of database.ph[) ###
 - $username: The username to the assets database
 - $password: The password to the assets database
 - $db_name: The name of the assets database
 - $assets_table_name: The name of the assets table in the assets database
 - $dbh: The database handle

### Functions ###
 - connectDatabase():void: Connects to the assets database and initializes $dbh
 - getDatabaseError():array: Returns the last PDO error, if any. See the [PDO documentation](http://php.net/manual/en/pdo.errorinfo.php) for more info
 - getAssetsTableName():string: Returns the name of the assets table. Use this rather than the actual variable.
 - imagesQuery($query:string, $parameters:array):array: Returns an associative array of PDO results for the given string and parameters
   - Example: ```php
imagesQuery("SELECT * FROM `".getAssetsTableName()."` WHERE `userid`=? AND `hash`=?", array($userid_you_want, $hash_you_want))
```
   - TODO: Rename this to something more descriptive
 - imagesQuery0($query:string, $parameters:array):void: like imagesQuery(), but does not expect a result.
   - TODO: Rename this to something more descriptive
 - getAllImages():array: Returns an associative array of all entries in the assets table.
   - TODO: Rename this. "Images" is because a lot of this code is from picturit, which only handled images.
 - getImagesForUser($userid:int):array: Returns an associative array of assets belonging to the user with the given user id
 - createImagesTable():void: Creates the assets table in the assets database. Used internally on connectDatabase()
 - imageExists($hash:string):boolean: Checks if an asset already exists in the database, returns true if so, false otherwise.
 - addImageRow($fileName:string, $hash:string, $user:string, $userId:int, $assetType:string, $customName:string):void: Creates a row in the assets table with the given details. $fileName is the actual filename in /uploads/uploaded/, $customName is the display name. $assetType is one of "image" "sound" or "script"
 - tableExists($name:string):boolean: Checks for the given table in the database, returns true if it exists, false otherwise. Used internally to create the assets table if it does not exist.

## connect.php ##
### Automatic actions ###
Connects the esotalk session and sets global variables
 - $is_admin:string: If the user is an administrator
 - $logged_in_userid:int: The userid of the logged in user, or 0 if not logged in
 - $logged_in_user:string: The username of the logged in user, or "not logged in" if not logged in
 - $user_group:string: The group the user is in, if any
 - $user_banned:boolean: True if the user is suspended, false otherwise

MATU is adding more swag to connect.php atm