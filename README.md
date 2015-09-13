# Dokuwiki-Nav-Overlay
Creates a window at the template level that can be opened and closed into which navigation plugins can be inserted

This is not technically a plugin but a facilitator for other plugins, in particular navigation/menu plugins.  First create
a Dokuwiki page with your navigation plugin (this has been tested with the indexmenu and simplenave plugins).
Then in your main.php create a div using the the following HTML:

        <div id='overlay'>
        <div  class = "close">
        <a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="close">Close</a>
        </div>
         <?php echo p_wiki_xhtml('your:nav_page'); ?>
        </div>

 Again, in your template create either a link or a button that will open and close the window.
      LINK:   <a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="Index">Index</a></li>  
      BUTTON:  <button onclick="jQuery('#overlay').toggle();void(0);">Index</button>
    
 If you are using the default dokuwiki template, you can create a link at the top of your template by appending it to
 the user tools section tpl/dokluwiki/tpl_header.php:
 
       <!-- USER TOOLS -->
                    <ul>
                    <?php
                        if (!empty($_SERVER['REMOTE_USER'])) {
                            echo '<li class="user">';
                            tpl_userinfo(); /* 'Logged in as ...' */
                            echo '</li>';
                        }
                            tpl_toolsevent('usertools', array(
                            tpl_action('admin', true, 'li', true),
                            tpl_action('profile', true, 'li', true),
                            tpl_action('register', true, 'li', true),
                            tpl_action('login', true, 'li', true)
                        ));                        
                    ?>                 
                  <li><a href="javascript:jQuery('#overlay').toggle();void(0);"  rel="nofollow" title="Index">Index</a></li> 
                </ul>
 
 In the overlay plugin folder, you will find a style.css file.  You can make changes as needed for your particular template.