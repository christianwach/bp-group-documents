<?php

/**
 * bp_group_documents_admin()
 *
 * Checks for form submission, saves component settings and outputs admin screen HTML.
 * @version 2, 13/5/2013, stergatu
 */
function bp_group_documents_admin() {
    global $bp, $bbpress_live;

    do_action('bp_group_documents_admin');

    /* If the form has been submitted and the admin referrer checks out, save the settings */
    if (isset($_POST['submit']) && check_admin_referer('group-documents-settings')) {

        if ($_POST['nav_page_name']) {
            update_option('bp_group_documents_nav_page_name', $_POST['nav_page_name']);
        } else {
            update_option('bp_group_documents_nav_page_name', __('Documents', 'bp-group-documents'));
        }

        //strip whitespace from comma separated list
        $formats = preg_replace('/\s+/', '', $_POST['valid_file_formats']);
        //keep everything lowercase for consistancy
        $formats = strtolower($formats);
        update_option('bp_group_documents_valid_file_formats', $formats);

        //turn absense of true into an explicit false
        if (isset($_POST['display_file_size']) && $_POST['display_file_size']) {
            $size = 1;
        } else {
            $size = 0;
        }
        update_option('bp_group_documents_display_file_size', $size);


        //turn absense of true into an explicit false
        if (isset($_POST['display_icons']) && $_POST['display_icons']) {
            $icons = 1;
        } else {
            $icons = 0;
        }
        update_option('bp_group_documents_display_icons', $icons);

        //turn absense of true into an explicit false
        if (isset($_POST['use_categories']) && $_POST['use_categories']) {
            $categories = 1;
        } else {
            $categories = 0;
        }
        update_option('bp_group_documents_use_categories', $categories);

        $valid_upload_permissions = array('members', 'mods_only', 'mods_decide');
        if (in_array($_POST['upload_permission'], $valid_upload_permissions)) {
            update_option('bp_group_documents_upload_permission', $_POST['upload_permission']);
        }

        if (ctype_digit($_POST['items_per_page'])) {
            update_option('bp_group_documents_items_per_page', $_POST['items_per_page']);
        }

        //turn absense of true into an explicit false
        if (isset($_POST['display_file_downloads']) && $_POST['display_file_downloads']) {
            $download_count = 1;
        } else {
            $download_count = 0;
        }
        update_option('bp_group_documents_display_download_count', $size);



        $updated = true;
    }
    $nav_page_name = get_option('bp_group_documents_nav_page_name');
    $valid_file_formats = get_option('bp_group_documents_valid_file_formats');
    //add consistant whitepace for readability
    $valid_file_formats = str_replace(',', ', ', $valid_file_formats);
    $display_file_size = get_option('bp_group_documents_display_file_size');
    $display_icons = get_option('bp_group_documents_display_icons');
    $use_categories = get_option('bp_group_documents_use_categories');
    $items_per_page = get_option('bp_group_documents_items_per_page');
    $upload_permission = get_option('bp_group_documents_upload_permission');
    $display_file_downloads = get_option('bp_group_documents_display_download_count');
    ?>
    <div class="wrap">
        <h2>Buddypress Group Documents: <?php _e('Settings'); ?></h2>
        <br />

        <?php
        if (isset($moved_count))
            echo "<div id='message' class='updated fade'><p>" . sprintf(__('%s Documents Moved.', 'bp-group-documents'), $moved_count) . "</p></div>";
        ?>
        <?php
        if (isset($updated))
            echo "<div id='message' class='updated fade'><p>" . __('Settings Updated.', 'bp-group-documents') . "</p></div>";
        ?>

        <form action="" name="group-documents-settings-form" id="group-documents-settings-form" method="post">				
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="target_uri"><?php _e('Use this name instead of "documents" ', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="text" name="nav_page_name" id="nav_page_name" value="<?php echo esc_attr($nav_page_name) ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="target_uri"><?php _e('Valid File Formats', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <textarea style="width:95%" cols="45" rows="5" name="valid_file_formats" id="valid_file_formats"><?php echo esc_attr($valid_file_formats); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th><label><?php _e('Items per Page', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="text" name="items_per_page" id="items_per_page" value="<?php echo $items_per_page; ?>" /></td>
                </tr>
                <tr>
                    <th><label><?php _e('Upload Permission:', 'bp-group-documents'); ?>:</label></th>
                    <td><input type="radio" name="upload_permission" value="members" <?php
                        if ('members' == $upload_permission)
                            echo 'checked="checked"';
                        ?> /><?php _e('Members &amp; Moderators', 'bp-group-documents'); ?><br />
                        <input type="radio" name="upload_permission" value="mods_only" <?php
                        if ('mods_only' == $upload_permission)
                            echo 'checked="checked"';
                        ?> /><?php _e('Moderators Only', 'bp-group-documents'); ?><br />
                        <input type="radio" name="upload_permission" value="mods_decide" <?php
                        if ('mods_decide' == $upload_permission)
                            echo 'checked="checked"';
                        ?> /><?php _e('Let individual moderators decide', 'bp-group-documents'); ?><br />
                </tr>
                <tr>
                    <th><label><?php _e('Use Categories', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="checkbox" name="use_categories" id="use_categories" <?php
                        if ($use_categories)
                            echo 'checked="checked"';
                        ?> value="1" /></td>
                </tr>
                <tr>
                    <th><label><?php _e('Display Icons', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="checkbox" name="display_icons" id="display_icons" <?php
                        if ($display_icons)
                            echo 'checked="checked"';
                        ?> value="1" /></td>
                </tr>
                <tr>
                    <th><label><?php _e('Display File Size', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="checkbox" name="display_file_size" id="display_file_size" <?php
                        if ($display_file_size)
                            echo 'checked="checked"';
                        ?> value="1" /></td>
                </tr>
                <tr>
                    <th><label><?php _e('Display File Downloads ', 'bp-group-documents') ?>:</label></th>
                    <td>
                        <input type="checkbox" name="display_file_downloads" id="display_file_downloads" <?php
                        if ($display_file_downloads)
                            echo 'checked="checked"';
                        ?> value="1" /></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" value="<?php _e('Save Settings', 'bp-group-documents') ?>"/>
            </p>
            <hr/>
            <div class='info'>Check for plugin update / say thanks, leave comments etc at: <a href="http://lenasterg.wordpress.com" target="_blank">http://lenasterg.wordpress.com</a>
                <div>
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=Q4VCLDW4BFW6L"><img style="border:0;" alt="" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" width="74" height="21" border="0"></a>
                </div>

                <?php wp_nonce_field('group-documents-settings'); ?>
        </form>
        <?php do_action('bp_group_documents_admin_end'); ?>
    </div><!-- .wrap -->
    <?php
}

/**
 * Finds the url of settings page 
 * @global type $wpdb
 * @global type $bp
 * @return string
 * @since v 0.6 
 * @author lenasterg
 * @version 1, 4/6/2013
 */
function bp_group_documents_find_admin_location() {
    global $wpdb, $bp;
    if (!is_super_admin())
        return false;
    // test for BP1.6+ (truncated to allow testing on beta versions)
    if (version_compare(substr(BP_VERSION, 0, 3), '1.6', '>=')) {
        // BuddyPress 1.6 moves its admin pages elsewhere, so use Settings menu
        $locationMu = 'settings.php';
    } else {
        // versions prior to 1.6 have a BuddyPress top-level menu
        $locationMu = 'bp-general-settings';
    }
    $location = bp_core_do_network_admin() ? $locationMu : 'options-general.php';
    return $location;
}

/**
 * 
 * @global type $wpdb
 * @global type $bp
 * @return boolean
 * @version 3, 4/6/2013, stergatu, fix the admin menu link for single wp installation
 * @since 0.5
 * @todo write the bp_group_documents_add_admin_style (minor)
 */
function bp_group_documents_group_add_admin_menu() {
    global $wpdb, $bp;
    /* Add the administration tab under the "Site Admin" tab for site administrators */
    $page = add_submenu_page(
            bp_group_documents_find_admin_location(), 'Buddypress Group Documents ' . __('Settings'), '<span class="bp-group-documents-admin-menu-header">' . __('Buddypress Group Documents', 'bp-group-documents') . '</span>', 'manage_options', 'bp-group-documents-settings', 'bp_group_documents_admin');

    // add styles only on bp-group-documents admin page, see:
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Load_scripts_only_on_plugin_pages
    //add_action( 'admin_print_styles-'.$page, 'bp_group_documents_add_admin_style' );
}

add_action(bp_core_admin_hook(), 'bp_group_documents_group_add_admin_menu', 10);

/**
 *  Add settings link on plugin page
 *  @param type $links
 * @param type $file
 * @return array
 * @since version 0.6
 * @version 1, 4/6/2013 stergtu
 * 
 */
function bp_group_documents_settings_link($links, $file) {
    $this_plugin = 'buddypress-group-documents/loader.php';
    if ($file == $this_plugin) {
        return array_merge($links, array(
            'settings' => '<a href="' . add_query_arg(array('page' => 'bp-group-documents-settings'), 
                    bp_group_documents_find_admin_location()) . '">' . esc_html__('Settings','bp-group-documents') . '</a>',
        ));
    }

    return $links;
}

/// Add link to settings page
add_filter('plugin_action_links', 'bp_group_documents_settings_link', 10, 2);
add_filter('network_admin_plugin_action_links', 'bp_group_documents_settings_link', 10, 2);