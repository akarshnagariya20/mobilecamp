<?php
function insurance_func_add()
{
?>
    <link rel="stylesheet" href="<?php echo plugins_url();?>/pianoman/css/bootstrap-3.0.0.min.css">
    <link rel="stylesheet" href="<?php echo plugins_url();?>/pianoman/css/pick-a-color-1.2.3.min.css">

    <style type="text/css">

        .pick-a-color-markup {
            margin:20px 0px;
        }
    </style>

    <script src="<?php echo plugins_url();?>/insurance/js/ins_jquery.js"></script>
    <script src="<?php echo plugins_url();?>/insurance/js/ins_min_jquery.js"></script>
    <h2>Add New Insurance </h2>

<form id="form" method="post" action="">
    <table>
        <tr>
            <td style="width: 150px">Plan Name</td>
            <td><input type="text" name="ins_plans" size="60" /></td>
        </tr>
        <tr>
            <td>Age</td>
            <td><input type="text" name="ins_age" size="10"/></textarea></td>
        </tr>
        <tr>
            <td>Policy Term
            </td>
            <td><input type="text" size="10" name="ins_policy"/></td>
        </tr>
        <tr>
            <td>Premium Paying Term
            </td>

            <td><input type="text" size="10" name="ins_term"/></td>

        </tr>
        <tr>
            <td>Accident Term Check
            </td>

            <td>
                <select name="accident">
                    <option  value="1">Checked</option>
                    <option value="0">Unchecked</option>
                </select>
            </td>

        </tr>
    </table>
    <input type="submit" value="Submit" class="button-primary"/>
</form>


<?php


        if (@$_POST) {
            $insplans = $_POST['ins_plans'];
            $insage = $_POST['ins_age'];
            $inspolicy = $_POST['ins_policy'];
            $insterm = $_POST['ins_term'];
            $accident = $_POST['accident'];

            $array_limt

            global $wpdb;
            $insurance1 = $wpdb->prefix . 'insurance';
            $wpdb->insert($insurance1, array(
                "ins_plans" => $insplans,
                "ins_age" => $insage,
                "ins_Policy_term" => $inspolicy,
                "ins_Premium_Paying_Term" => $insterm,
                "ins_Accident" => $accident

            ));
            echo "Data is successfully Inserted";
            wp_die();
        }
    }


function insurance_func(){
    ?>

    <!--<link rel="stylesheet" href="<?php echo plugins_url();?>/pianoman/css/bootstrap-3.0.0.min.css">
        <link rel="stylesheet" href="<?php echo plugins_url();?>/pianoman/css/pick-a-color-1.2.3.min.css">

        <style type="text/css">

            .pick-a-color-markup {
        margin:20px 0px;
            }
        </style>

    <script src="<?php echo plugins_url();?>/insurance/js/ins_jquery.js"></script>
    <script src="<?php echo plugins_url();?>/insurance/js/ins_min_jquery.js"></script>-->

    <?php
	global $wpdb;
	$insurance_table = $wpdb->prefix .'insurance';

 $myrows = $wpdb->get_results( "SELECT * FROM $insurance_table" );
	?>
    <h2>Choose Insurance To Edit</h2>
	<select name="per1" id="per1">
		<option selected="selected">Choose one</option>
		<?php
		foreach($myrows as $name) { ?>
			<option value="<?php echo $name->id ?>"><?php echo $name->ins_plans ?></option>
		<?php
		} ?>
	</select>

    <img id="wait_img" alt="Please Wait" src="<?php echo plugins_url();?>/insurance/img/load2.GIF"/>

<div id="num1"><div id="num"></div></div>

    <script type="text/javascript" >
        jQuery(document).ready(function($) {
            $('#wait_img').hide();

            $( "#per1" ).change(function () {
                $('#num1').fadeOut("slow");
                $('#wait_img').show();
                var str = "";
                $( "select option:selected" ).each(function() {
                    str += $( this ).val() + " ";
                    var data = {
                        'action': 'my_action',
                        'whatever': str
                    };
                    $.post(ajaxurl, data, function(response) {
                        $('#wait_img').hide();
                        $('#num1').fadeIn("slow");
                        $("#num").html(response);
                    });

                })
            });




            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

        });
    </script>
<?php



}

?>
<?php

add_action( 'wp_ajax_my_action', 'my_action_callback' );

function my_action_callback()
{
    global $wpdb; // this is how you get access to the database

    $whatever = ($_POST['whatever']);

    global $wpdb;
    $insurance_table = $wpdb->prefix . 'insurance';

    $myrows = $wpdb->get_results("SELECT * FROM $insurance_table WHERE id='$whatever'");

    foreach ($myrows as $name) {?>
        <form method="post" action="">
    <table>
        <tr>
            <td style="width: 150px">Plan Name</td>
            <td><input type="text" value="<?php echo $name->ins_plans ?>" name="ins_plans" size="60" /></td>
        </tr>
        <tr>
            <td>Age</td>
            <td><input value="<?php echo $name->ins_age ?>" type="text" name="ins_age" size="10"/></textarea></td>
        </tr>
        <tr>
            <td>Policy Term
        </td>
            <td><input value="<?php echo $name->ins_Policy_term ?>" type="text" size="10" name="ins_policy"/></td>
        </tr>
        <tr>
            <td>Premium Paying Term
        </td>

            <td><input value="<?php echo $name->ins_Premium_Paying_Term ?>" type="text" size="10" name="ins_term"/></td>

        </tr>
        <tr>
            <td>Accident Term Check
        </td>

            <td>
                <select  name="accident">
                    <option  value="1">Checked</option>
                    <option value="0">Unchecked</option>
                </select>
            </td>

        </tr>
    </table>
    <input type="submit" value="Submit" class="button-primary"/>
</form>
<?php
        wp_die(); // this is required to terminate immediately and return a proper response
    }
}
?>