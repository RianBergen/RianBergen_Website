<?php
// Include Config
require_once 'includes/config.php';

// Get Variables
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = NULL;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = NULL;
}

if (isset($_GET['month'])) {
    $month = $_GET['month'];
} else {
    $month = NULL;
}

if (isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = NULL;
}

// Get Sidebar And Background Information
try {
    // Get SQL Data
    $statement = $connection->query('
        SELECT
            settingsID,
            settingsValue,
            settingsImage
        FROM
            blog_settings
        WHERE
            settingsID >= 1 AND settingsID <= 8
        ORDER BY
            settingsID
    ');
    
    $rows = array();
    
    while($row = $statement->fetch()) {
        array_push($rows, $row);
    }
} catch(PDOException $e) {
    echo $e->getMessage();
}

if($rows[0][1] || $rows[1][1] || $rows[2][1] || $rows[3][1]) {
    $showSidebar = 1;
} else {
    $showSidebar = 0;
}

$sidebarRight = $rows[4][1];
$backgroundImage = $rows[5][2];
$showTimeline = $rows[6][1];
?>

<!-- HTML CODE -->
<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
	
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
    <title><?php echo ''.HTMLTITLE.'';?></title>
    <meta name="description" content=<?php echo '"'.HTMLDECRIPTION.'"';?>>
    <link rel="icon" sizes="16x16" href=<?php echo '"/_res/images/icon/16x16-Logo.png?v='.CSSVERSION.'"';?>>
    <link rel="icon" sizes="32x32" href=<?php echo '"/_res/images/icon/32x32-Logo.png?v='.CSSVERSION.'"';?>>
    <link rel="icon" sizes="192x192" href=<?php echo '"/_res/images/icon/192x192-Logo.png?v='.CSSVERSION.'"';?>>
    
    <link id="theme-style" rel="stylesheet" type="text/css" onload="this.media='all'" href="/_res/styles/rb-engine.<?php echo ''.ISDARKMODE.'';?>.css?v=<?php echo ''.CSSVERSION.'';?>">
    <link rel="stylesheet" type="text/css" onload="this.media='all'" href="/_res/styles/rb-engine.css?v=<?php echo ''.CSSVERSION.'';?>">
    
    <link id="theme-style-comments" rel="stylesheet" type="text/css" onload="this.media='all'" href="<?php if(ISDARKMODE == 'dark'){echo '/hashover/themes/default-dark-borderless/comments.css';}else{echo '/hashover/themes/default-borderless/comments.css';}?>?v=<?php echo ''.CSSVERSION.'';?>">

    <?php
    if ($backgroundImage != NULL) {
        $stmt2 = $connection->query('
			SELECT
				imageID,
				imagePath
			FROM
				blog_images
			WHERE
				imageID = '.$backgroundImage
		);

		$stmt2->execute(array());
        $row2 = $stmt2->fetch();
        
        echo '<style>body, html{background-color: transparent !important; background-image: url("/'.$row2["imagePath"].'") !important;}</style>';
    }
    ?>
    
    <meta name="theme-color" content="#242424">
</head>
<body>
<div class="rb-main-flex-grid-initializer">
	<div class="rb-main-flex-grid-container">
   
	<?php
		// Include Page Header
		include 'pagecomp-header.php';
        
        
        // Include Sidebar Left
        if ($sidebarRight == 0 && $showSidebar) {
            if ($page != 'info') {
                include 'pagecomp-sidebar.php';
            }
        }
        
        // Decide Main Column Width
        echo '<!-- START - Left Column: Blog Post Column -->';
        echo '<div class="'; if($showSidebar == 0 || $page == 'info') {echo 'rb-main-flex-grid-column';} else { echo 'rb-main-flex-grid-left-column';}; echo '">';
		
		// Check Variables And Include The Left Column
		if ($page == NULL) {
            if ($showTimeline == 1) {
                // View All Posts
                include 'pagecomp-posts.php';
            } else {
                // View Home
                include 'pagecomp-home.php';
            }
		} else if ($page == 'feed') {
            // View All Posts
            include 'pagecomp-posts.php';
        } else if ($page == 'post') {
            // View Selected Post
			include 'pagecomp-viewpost.php';
		} else if ($page == 'archive') {
            // View All Posts In Selected Archive
			include 'pagecomp-archives.php';
		} else if ($page == 'tag') {
            // View All Posts In Selected Tag
            include 'pagecomp-tags.php';
        } else if ($page == 'action') {
            if ($id == 'about') {
                include 'pagecomp-viewabout.php';
            } else {
                include 'pagecomp-action.php';
            }
        } else if ($page == 'info') {
            // View Selected Information Page
            include 'pagecomp-info.php';
        }
        
		// Include Sidebar
        if ($sidebarRight == 1 && $showSidebar) {
            if ($page != 'info') {
                include 'pagecomp-sidebar.php';
            }
        }
	?>
		
	</div>

<?php
	// Include Page Footer
	include 'pagecomp-footer.php';
?>

</div>

<!-- Light/Dark Mode Manager -->
<script src="/_res/js/rb-theme-manager.js?v=<?php echo ''.CSSVERSION.'';?>"></script>
</body>
</html>