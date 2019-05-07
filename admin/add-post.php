<?php
// Include Config File
require_once('../includes/config.php');

// If Not Logged In, Redirect To Login Page
if(!$user->isLoggedIn()) {
	// Redirect To Login
	header('Location: login.php');
}
?>
<!-- HTML CODE -->
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Rian Bergen - Add Post</title>
	<meta name="description" content="The official home for everything related to Rian-Pascal Bergen!">
	<link rel="icon" sizes="16x16" href="../_res/images/16x16-Logo.png">
	<link rel="icon" sizes="32x32" href="../_res/images/32x32-Logo.png">
	<link rel="icon" sizes="192x192" href="../_res/images/192x192-Logo.png">
	
	<link rel="stylesheet" href="../_res/styles/rb-engine.css">
	
	<script src="tinymce/tinymce.min.js"></script>
	<script>
		tinymce.init({
			selector: "textarea",
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table paste"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            height : "500px"
		});
	</script>
</head>
<body>
<div id="rb-admin-container">
	<div class="rb-card" id="rb-admin-content">
	
	<?php
		// Display Menu
		include('menu.php');
	?>
	
	<!-- Admin Page Link -->
	<p><a href="./">Go Back</a></p>
	<h2>Add Post</h2>
	
	<?php
		// Process Submitted Form
		if(isset($_POST['submit'])) {
			// Collect Form Data
			extract($_POST);
			
			// Very Basic Vallidation
			if($postTitle == '') {
				$error[] = 'Please Enter A Title';
			}
			
			if($postDescription == '') {
				$error[] = 'Please Enter A Description';
			}
			
			if($postContent == '') {
				$error[] = 'Please Enter The Content';
			}
            
            // Image Validation For Post Banner
            if(isset($_FILES['postImage'])) {
                // Find The Image Type
                switch ($_FILES["postImage"]["type"]) {
                    case $_FILES["postImage"]["type"] == "image/gif":
                        break;
                    case $_FILES["postImage"]["type"] == "image/jpeg":
                        break;
                    case $_FILES["postImage"]["type"] == "image/pjpeg":
                        break;
                    case $_FILES["postImage"]["type"] == "image/png":
                        break;
                    case $_FILES["postImage"]["type"] == "image/x-png":
                        break;
                    default:
                        $error[] = 'Improper Image Upload For Post: Not A JPG, PNG Or GIF';
                }
            }
            
			// Form Handling
			if(!isset($error)) {
				try {
					// Create Post Slug
					$postSlug = createPostSlug($postTitle);
					
					// Insert Data Into Database
					$stmt = $connection->prepare('INSERT INTO blog_posts (postTitle, postSlug, postDescription, postContent, postDate, postTags) VALUES (:postTitle, :postSlug, :postDescription, :postContent, :postDate, :postTags)') ;
					$stmt->execute(array(
						':postTitle' => $postTitle,
						':postSlug' => $postSlug,
						':postDescription' => $postDescription,
						':postContent' => $postContent,
						':postDate' => date('Y-m-d H:i:s'),
                        ':postTags' => $postTags
					));
					
					$postID = $connection->lastInsertId();
					
					// Attach Categories
					if(is_array($categoryID)) {
						foreach($_POST['categoryID'] as $categoryID) {
							$stmt = $connection->prepare("INSERT INTO blog_post_categories (pcPostID, pcCategoryID) VALUES(:postID, :categoryID)");
							$stmt->execute(array(
								':postID' => $postID,
								':categoryID' => $categoryID
							));
						}
					}
                    
                    // Add Image If Uploaded
                    if(isset($_FILES['postImage']) && $_FILES['postImage'] != NULL) {
                        // Select The New File Location For The Images
                        $target = "_res/images/posts/".$postID."/".$_FILES['postImage']['name'];
                        $path = '../'.$target;
                        
                        // Move Image
                        mkdir("../_res/images/posts/".$postID."/", 0755);
                        move_uploaded_file($_FILES["postImage"]["tmp_name"], $path);
                        
                        // Connect Image
                        $stmt2 = $connection->prepare('UPDATE blog_posts SET postImage = :image WHERE postID = :postID') ;
                        $stmt2->execute(array(
                            ':postID' => $postID,
                            ':image' => $target
                            ));
                    }
                    
					// Redirect To Admin Page
					header('Location: index.php?action=added');
					exit;
				} catch(PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
		
		// Check For Any Errors
		if(isset($error)) {
			foreach($error as $error) {
				echo '<p class="rb-error">'.$error.'</p>';
			}
		}
	?>
	
	<!-- Add Post Form -->
	<form action='' method='post' enctype="multipart/form-data">
		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php if(isset($error)){echo $_POST['postTitle'];}?>'></p>
		
		<p><label>Description</label><br />
		<textarea name='postDescription' cols='60' rows='10'><?php if(isset($error)){echo $_POST['postDescription'];}?></textarea></p>
		
		<p><label>Content</label><br />
		<textarea name='postContent' cols='60' rows='10'><?php if(isset($error)){echo $_POST['postContent'];}?></textarea></p>
		
        <p><label>Tags (Comma Seperated)</label><br />
        <input type='text' name='postTags' value='<?php if(isset($error)){ echo $_POST['postTags'];}?>' style="width:400px;"></p>
        
		<!-- List Of All Categories -->
		<fieldset>
			<legend>Categories</legend>
			
			<?php	
				$stmt2 = $connection->query('SELECT categoryID, categoryTitle FROM blog_categories ORDER BY categoryTitle');
				while($row2 = $stmt2->fetch()) {
					if(isset($_POST['categoryID'])) {
						if(in_array($row2['categoryID'], $_POST['categoryID'])) {
							$checked="checked='checked'";
						} else {
							$checked = null;
						}
					}
					echo "<input type='checkbox' name='categoryID[]' value='".$row2['categoryID']."' $checked>".$row2['categoryTitle']."<br />";
				}
			?>
		</fieldset>
		
        <!-- Images -->
        <p><label>Banner Image (Recommended Size: 1920x1080)(Recommended File Type: JPEG, PNG, GIF)</label><br />
        <input type='file' name='postImage' multiple></p>
        
		<p><input type='submit' name='submit' value='Submit'></p>
	</form>
	</div>
</div>
</body>
</html>