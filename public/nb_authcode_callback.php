<?php
/**
 *  DominoLink Leader Directories
 *    by Dubtempo @ DominoLink
 *
 *    "nb_authcode_callback.php"
 *
 *    this files recieves the auth code from the
 *    NB iframe then uses POST message to deliver to the parent frame
 *
 */

?>

<html>
    <head>
        <style type="text/css">
        </style>
        <script>
            var urlStr = window.location.href;
            var urlCB = urlStr.replace(/^.*code=/gmi , '');
            window.parent.postMessage({ messagePerm: urlCB }, '*');
            <?php /* if( isset($_GET['code']) ) echo 'var urlCB = "' . $_GET['code'] . '"'; */ ?>;
        </script>
    </head>
    <body>
        <?php
        /*
            this file sends the users NB authorization code which was generated in the iframes used by the plugin
            since it is sending a key which can be used to view and modify a nations ENTIRE member list, let's err on the side of caution
            - there is no storage of the key on this server
            - if the connection isn't ssh, bounce the eff out
            - use whatever mechanism you to prefer to log the accessing of this file
        */
        // verify connection is ssh
        // if( $_SERVER['HTTPS'] !== 'on' ) {
        //     echo 'For some reason, your connection to this plugin is insecure.  Please contact the provider of the WP2NB plugin.';
        // }

        ?>
    </body>
</html>