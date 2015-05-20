<?php function insurance_func_add(){ ?>
<form method="post"
      action="">
    <input type="hidden" name="action" value="save_ch7bt_bug" />

    <!-- Adding security through hidden referrer field -->
    <?php wp_nonce_field( 'ch7bt_add_edit' ); ?>
    <!-- Display bug editing form -->
    <table>
        <tr>
            <td style="width: 150px">Title</td>
            <td><input type="text" name="bug_title" size="60"
                       value="<?php echo esc_attr(
                           $bug_data['bug_title'] ); ?>"/></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name="bug_description" cols="60">
                    <?php echo esc_textarea(
                        $bug_data['bug_description'] ); ?></textarea></td>
        </tr>
        <tr>
            <td>Version</td>
            <td><input type="text" name="bug_version"
                       value="<?php echo esc_attr(
                           $bug_data['bug_version'] ); ?>" /></td>
        </tr>
        <tr>
            Chapter 7
            215
            <td>Status</td>
            <td>
                <select name="bug_status">
                    <?php
                    // Display drop-down list of bug statuses
                    // from list in array
                    $bug_statuses = array( 0 => 'Open', 1 => 'Closed',
                        2 => 'Not-a-Bug' );
                    foreach( $bug_statuses as $status_id => $status ) {
// Add selected tag when entry matches
// existing bug status
                        echo '<option value="' . $status_id . '" ';
                        selected( $bug_data['bug_status'],
                            $status_id );
                        echo '>' . $status;
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" value="Submit" class="button-primary"/>
</form>
<?php } ?>