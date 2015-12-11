<?php
/*******************************************

phpFlickr authentication tool v1.0
created by Dan Coulter (http://www.dancoulter.com)
for use with phpFlickr (http://www.phpflickr.com).

This tool allows you to get an authentication token that you can use with your
phpFlickr install to allow a certain kind of access to one Flickr account
through the API to anyone who visits your website.  In other words...You can
create a photo gallery on your website that shows photos you've marked as
private and visitors to your website won't have to do any sort of
authentication to Flickr.

This tool is free for you to use and matches the code used at
http://www.phpflickr.com/tools/auth/ exactly.  You may not use this to collect
other users' login information without their permission or knowledge.

This file is packaged with a full distribution of phpFlickr v1.5 that you may use
in your application.

*******************************************/

require_once(dirname(__FILE__) . "/phpFlickr.php");

if (isset($_POST['api_key']) && isset($_POST['secret'])) {
  if (empty($_POST['api_key']) && empty($_POST['secret'])) {
    echo '<span color="red">Please fill in both the api key and the secret key.</span><hr />';
  }
  else {
    // Start the session and set vars for after authentication redirect
    setcookie('flickr_api_key', $_POST['api_key'], time()+3600);
    setcookie('flickr_secret', $_POST['secret'], time()+3600);

    // Setup with secret & api key
    $flickr = new phpFlickr($_POST['api_key'], $_POST['secret'], true);

    // Authenticate; need the "IF" statement or an infinite redirect will occur
    if(empty($_GET['frob'])) {
      $flickr->auth('read');
    }
  }
}
elseif(isset($_COOKIE['flickr_api_key']) && isset($_COOKIE['flickr_secret']) && isset($_GET['frob'])) {
  $flickr = new phpFlickr($_COOKIE['flickr_api_key'], $_COOKIE['flickr_secret'], true);
  $r = $flickr->auth_getToken($_GET['frob']);
  if ($r['token']) {
    echo '<strong style="font-size:22px;">You\'ve succesfully generated a token!</strong><br />Copy paste this token into your flickr field settings:<br /><span style="font-size:22px">' . $r['token'] . '</span><br /><span style="font-size:11px">(don\'t lose it!)</span>';
  }
  else {
    echo 'Token generation failed..';
  }
  echo '<hr />';
}
?>
<table border="0" width="800"><tr><td>
  To authenticate your application to one user in order to get private photos/albums you'll need go through the following steps:
  <ul>
      <li>Generate an API Key with Flickr: <a href="http://www.flickr.com/services/api/key.gne" target="_blank">Link</a></li>
      <li>Setup authentication information : <a href="http://www.flickr.com/services/api/registered_keys.gne" target="_blank">Link</a></li>
      <ol type="A">
          <li>Be sure to note your API Key and Secret.  You'll need these in a moment.</li>
          <li>Set the callback url to:<br /><strong>http://<?php echo $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']); ?>/generate_flickr_token.php</strong></li>
      </ol>
      <li>Fill out the form below.  You will be redirected to Flickr for authentication. </li>
  </ul>

  <br />

  <form method='post'>
    API Key<br />
    <input type="text" name="api_key" size="40" /><br />
    Secret<br />
    <input type="text" name="secret" size="40" /><br />
    <input type="hidden" name="perms" value="write" />
    <input type="submit" value="Submit">
  </form>
</td></tr></table>
<?