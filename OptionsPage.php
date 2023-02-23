<?php


namespace codeListing;


class OptionsPage
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_submenu_page'));
    }

    public function add_submenu_page()
    {
        add_submenu_page(
            'options-general.php', // parent slug
            __('Code listing', textDomain()), // page title
            __('Code listing', textDomain()), // menu title
            'manage_options', // capability
            'code-listing', // menu slug
            array($this, 'render_submenu_page') // callback function
        );
    }

    public function render_submenu_page()
    {
        if (isset($_POST['code_listing'])) {
            update_option('code_listing', $_POST['code_listing'], false);
        }
        // Set class property
        $options = get_option('code_listing', true);
        $variants = [
            [
                'label' => __('Auto', textDomain()),
                'value' => '',
            ],
            [
                'label' => __('Light', textDomain()),
                'value' => 'light',
            ],
            [
                'label' => __('Dark', textDomain()),
                'value' => 'dark',
            ],
        ];
        ?>
        <div class="wrap">
            <h1><?php echo __('Color scheme', textDomain()); ?></h1>
            <form method="post">
                <?php
                foreach ($variants as $index => $item) {
                    $item['checked'] = $options === $item['value'] ? 'checked' : '';
                    echo '<div>'
                         . '<label for="code_listing_' . $index . '">'
                         . '<input type="radio" name="code_listing" id="code_listing_' . $index . '" value="' . $item['value'] . '"
                                                        ' . $item['checked'] . '> ' . $item['label']
                         . '</label>'
                         . '</div>';
                }
                ?>

                <?php
                submit_button(__('Save'));
                ?>
            </form>
        </div>
        <?php
    }

}
