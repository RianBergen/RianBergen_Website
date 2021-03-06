<?php?>

<!-- START - Footer Content -->
<footer class="rb-page-footer">
    <div class="rb-page-footer-container">
        <div>
            <h1 class="rb-page-footer-container-element"><?php echo ''.TITLE.'';?></h1>
            <h6 class="rb-page-footer-container-element"><?php echo ''.COPYRIGHT.'';?></h6>
            <a class="rb-page-footer-container-element" href="/">Home</a><br />
            <?php if ($showTimeline == 0) {echo '<a class="rb-page-footer-container-element" href="/feed/">Posts</a><br />';}?>
            <a class="rb-page-footer-container-element" href="/action/about">About</a><br />
            <a class="rb-page-footer-container-element" href="/action/contact">Contact</a><br />
            <a class="rb-page-footer-container-element" href="/action/subscribe">Subscribe/Unsubscribe</a><br />
        </div>
        <div class="rb-margin-1rem-top">
            <a class="rb-page-footer-container-element" href="/info/terms-and-conditions">Terms and Conditions</a><br />
            <a class="rb-page-footer-container-element" href="/info/software-eula">Software EULA</a><br />
            <a class="rb-page-footer-container-element" href="/info/privacy-policy">Privacy Policy</a><br />
            <a class="rb-page-footer-container-element" href="/info/help">Help</a><br />
        </div>
		<div class="rb-margin-1rem-top">
            <p id="theme-change-button" class="rb-page-footer-container-element rb-page-footer-pointer" onclick="SwitchTheme()">Enable Dark Mode</p>
        </div>
        <div>
            <a class="rb-page-footer-container-element" href="https://github.com/RianBergen/RB-Blog-Template">GitHub Link</a>
        </div>
    </div>
</footer>
<!-- END   - Footer Content -->