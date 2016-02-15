<?php
    include_once '../assets/includes/connect.php';
    include '../assets/includes/collections.php';
    
    if(!isset($_GET['uid'])){
    	include '../404.php';
    	die();
    }
    
    $uid = intval($_GET['uid']);
    $username = getUserInfo($uid)['username'];
    
    $cid = NULL;
    $info = NULL;
    $assets = NULL;
    if(isset($_GET['cid'])){
    	$cid = $_GET['cid'];
    }
    
    $is_creating = ((isset($_GET['action']) && $_GET['action'] === "create") || $_GET['cid'] == "create");
    if($is_creating){
    	$cid = NULL;
    } else {
    	$info = getCollectionInfo($uid, $cid);
    	$assets = getAssetsInCollection($uid, $cid);
    }
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php 
            include '../Header.html'; // Imports the metadata and information that will go in the <head> of every page
            ?>
        <script>
            OpenSprites.view = OpenSprites.view || {};
            OpenSprites.view.isCreatingCollection = <?php echo json_encode($is_creating); ?>;
        </script>
        <style>
            h1 {
                font-size: 48px;
                font-weight: 300;
                text-align: center;
            }
            
            #id {
                font-size: 20px;
                margin-top: -10px;
            }
            
            #description {
                font-size: 22px;
                text-align: center;
                margin-top: 30px;
            }
            
            #collection-search {
                margin-bottom: 15px;
            }
            /* When creating collection */
            
            #create-collection {
                width: 150px;
                margin: auto;
            }
            
            button.action.btn.orange {
                padding-top: 0;
            }
            
            button.btn.orange {
                padding-top: 0;
            }
        </style>
    </head>

    <body>
        <link href='../main-style.css' rel='stylesheet' type='text/css'>
        <?php
            include "../navbar.php"; // Imports navigation bar
            ?>
            <!-- Main wrapper -->
            <div class="container main">
                <div class="main-inner">
                    <h1><?php echo ($is_creating ? "New Collection" : $info['customName'] . " <br><!--Is there a reason why we need this ID?--><div id='id'>(#" . $info['id'] . ")</div>"); ?></h1>
                    <div id="description">
                        <strong>By: </strong>
                        <a href="http://opensprites.org/users/<?php echo urlencode($username); ?>" target="blank">
                            <?php echo ($uid === $logged_in_userid ? "You" : $username) /*. " (#" . $uid . ")"*/; ?>
                        </a>
                    </div>
                    <!--<h2><?php //echo ($uid === $logged_in_userid ? "You" : $username) . " (#" . $uid . ")"; ?></h2>-->
                    <?php if($is_creating){ ?>
                    <form id="create-collection">
                        <p class='status'></p>
                        <input type="text" name="name" id="collection-name" placeholder="Collection name" maxlength="32" /><br/>
                        <!-- I'll remove these deprecated <center> tags once I discovered a workaround.. -->
                        <center><button class="btn orange" type="submit">Create Collection</button></center>
                    </form>
                    <?php } else { ?>
                    <div id="collection-description">
                        Render markdown here
                    </div>
                    <br/>
                    <?php if($uid == $logged_in_userid){ ?>
                    <div id="collection-actions">
                        <p class='label' style='float:left;margin-top:2px;margin-right:8px;'>Actions:</p>
                        <button class="action btn orange" type="button" id="action-edit" data-toggle="action-edit" data-target="#action-edit-modal">Edit Details</button>
                        <button class="action btn orange" type="button" id="action-add">Add Assets</button>
                        <button class="action on-select btn orange" type="button" id="action-remove">Remove Selected Assets</button>
                        <button class="action btn orange" type="button" id="action-collab">Manage Collaborators</button>
                    </div>
                    <br/>
                    <?php } ?>
                    <div id="collection-assets">
                        <div id="collection-search">
                            <h2 class="centered-heading">Collection Resources</h2>
                            <input type="text" placeholder="Search collection" />
                            <div class="box-content assets-list" id="collection-assets-list"></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div id="action-edit-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edit Collection</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="collection-name-input">Collection Name</label>
                                <input type="name" class="form-control" id="collection-name-input" placeholder="Collection Name" maxlength="32">
                            </div>
                            <div class="form-group">
                                <label for="collection-description-input">Collection Description:</label>
                                <textarea class="form-control" rows="5" id="collection-description-input" placeholder="Collection Description" maxlength="500"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="action-edit-modal" id="action-edit-modal-close">Close</button>
                            <button type="button" class="btn btn-default" data-dismiss="action-edit-modal" id="action-edit-modal-save-close">Save & Close</button>
                        </div>
                    </div>

                </div>
            </div>
            <br/><br/>
            <div class="container main">
                <div class="main-inner">
                    test
                </div>
            </div>
            <script src="/users/collections.js"></script>
            <script>
                var modelCollections = OpenSprites.models.AssetList($("#collection-assets-list"));
                $.get("/site-api/collection_get.php?userid=<?php echo urlencode($username);?>&cid=<?php echo urlencode($cid);?>", function(data) {
                    modelCollections.loadJson(data);
                });

                // Event Listeners
                $('#action-edit-modal-save-close').on('click', function(e) {
                    var name = encodeURIComponent($('#collection-name-input').val());
                    var description = encodeURIComponent($('#collection-description-input').val());
                    $.post("/site-api/collection_edit.php?cid=<?php echo urlencode($cid);?>&name=" + name + "&description=" + description, {}, function(data, status) {});
                });
            </script>
            <!-- footer -->
            <?php echo file_get_contents('../footer.html'); ?>
    </body>
</html>
