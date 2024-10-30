<?php

// Register page in menu.
function maaq__register_settings_page()
{
    add_submenu_page(
        'options-general.php',
        'Maaq - Pair app',
        'Maaq',
        'administrator',
        'maaq',
        'maaq__settings_page_structure'
    );
}
add_action('admin_menu', 'maaq__register_settings_page');

// Register regenerate ajax function.
function maaq__manually_regenerate_secrets()
{
    do_action('maaq_generate_secrets');
    wp_die();
}
add_action('wp_ajax_maaq_manually_regenerate_secrets', 'maaq__manually_regenerate_secrets');

// The page callback.
function maaq__settings_page_structure()
{
    // Sanitize input.
    $remote_address = $_SERVER['REMOTE_ADDR'];
    $disallowed_addresses = ['127.0.0.1', '::1', '0.0.0.0', 'localhost'];
    $is_localhost = in_array($remote_address, $disallowed_addresses);

    // Check if WordPress site is not on localhost.
    if (!$is_localhost) {
        $website_data = [
            'name' => get_option('blogname'),
            'baseUrl' => get_site_url(),
            'secretToken' => get_option('maaq_secret_token'),
            'secretPath' => get_option('maaq_secret_path'),
        ];
        $website_data_encoded = wp_json_encode($website_data);

        $qr_code_data = maaq__generate_qr_code($website_data_encoded);

        // Check if QR code data is not empty.
        if (!empty($qr_code_data)) {
?>
            <div class='wrap'>
                <h1>Maaq</h1>
                <hr>
                <p>Scan the QR Code below to pair this website with the Maaq app!</p>
                <div style='padding: 8px; border-radius: 12px; background-color: white; width: 375px;'>
                    <img src='<?php echo esc_attr($qr_code_data); ?>' alt='Maaq paring QR code' width="375" height="375" />
                </div>
                <p style='margin-top: 28px'>Or you can copy the website pair data below and paste it within the Maaq app!</p>
                <div>
                    <input type='submit' name='submit' id='submit' onclick='copy_website_data()' class='button button-primary' value='Copy website pair data' />
                </div>

                <hr style='margin: 28px 0px' />

                <p>Regenerate all secrets 🤫. This can be handy for when you want to change all the Maaq secrets. <br />Be aware, after regenerating you will have to remove this site from your Maaq account to pair it again.</p>
                <button type='submit' id="manually_regenerate_secrets" class="button button-primary">Regenerate</button>
            </div>
            <script>
                function copy_website_data() {
                    var website_data = '<?php echo $website_data_encoded; ?>';

                    try {
                        navigator.clipboard.writeText(website_data).then(function() {
                            if (website_data) {
                                alert('Successfully copied the website pair data! You can now paste the pair data in the Maaq app.');
                            } else {
                                copy_website_data_error();
                            }
                        }, function(err) {
                            copy_website_data_error();
                        });
                    } catch (e) {
                        copy_website_data_error();
                    }
                }

                function copy_website_data_error() {
                    if (navigator.userAgent.search("Firefox")) {
                        alert('Sadly Firefox does not allow copying to your clipboard :( You can try opening this page in another browser.');
                    } else {
                        alert('Ohno... Something went wrong with copying the website pair data. Try to scan the QR Code instead!');
                    }
                }

                jQuery("#manually_regenerate_secrets").click(function() {
                    if (confirm('Are you sure that you want to regenerate all the secrets? To pair this site again you will have to remove this site from your Maaq account and pair it again.') == true) {
                        var ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';

                        jQuery.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: {
                                action: 'maaq_manually_regenerate_secrets',
                            },
                            success: function(output) {
                                location.reload();
                            }
                        });
                    }
                });
            </script>
        <?php
        } else {
            // Handle the case where QR code data is empty.
        ?>
            <div class="wrap">
                <h1>Maaq</h1>
                <hr>
                <p>Failed to generate the QR Code. Please try again.</p>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="wrap">
            <h1>Maaq</h1>
            <hr>
            <p>You cannot pair a WordPress website that is on a localhost... Try it with a live website!</p>
        </div>
<?php
    }
}
