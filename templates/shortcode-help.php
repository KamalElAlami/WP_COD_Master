<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="fcod-shortcode-help-wrapper">
    <h3>Shortcode Usage</h3>
    <p>Use the shortcode <code>[flexible_cod_form]</code> to add the form to any page, post, or widget area.</p>
    
    <div class="fcod-shortcode-examples">
        <h4>Basic Usage</h4>
        <div class="fcod-code-example">
            <pre><code>[flexible_cod_form]</code></pre>
            <p>This will display a simple form without any product attached. Useful for custom orders or inquiries.</p>
        </div>
        
        <h4>Linking to a Product</h4>
        <div class="fcod-code-example">
            <pre><code>[flexible_cod_form product_id="123"]</code></pre>
            <p>Replace <code>123</code> with the actual product ID. This will display the form with product information.</p>
        </div>
        
        <h4>Customizing the Form</h4>
        <div class="fcod-code-example">
            <pre><code>[flexible_cod_form product_id="123" template="smartlook" title="طلب منتج جديد" button_text="اطلب الآن"]</code></pre>
            <p>You can customize the form by adding these parameters:</p>
            <ul>
                <li><code>product_id</code> - The WooCommerce product ID (optional)</li>
                <li><code>template</code> - Template to use (default, smartlook, clean)</li>
                <li><code>title</code> - Custom form title</li>
                <li><code>button_text</code> - Custom button text</li>
            </ul>
        </div>
        
        <h4>Using for Product Landing Pages</h4>
        <p>For creating custom landing pages, you can use the shortcode within any page builder:</p>
        <div class="fcod-code-example">
            <ol>
                <li>Create a new page</li>
                <li>Use your page builder to design the landing page</li>
                <li>Add the shortcode <code>[flexible_cod_form product_id="123" template="clean"]</code> where you want the form to appear</li>
                <li>Optionally add product images, testimonials, and other marketing elements around the form</li>
            </ol>
        </div>
    </div>
    
    <div class="fcod-php-usage">
        <h4>PHP Usage in Theme Files</h4>
        <p>You can also display the form directly in your theme templates using PHP:</p>
        <div class="fcod-code-example">
            <pre><code>&lt;?php 
echo do_shortcode('[flexible_cod_form product_id="123" template="smartlook"]'); 
?&gt;</code></pre>
        </div>
    </div>
    
    <div class="fcod-block-usage">
        <h4>Gutenberg Block</h4>
        <p>You can also add the form using the "Flexible COD Form" block in the Gutenberg editor.</p>
        <ol>
            <li>Add a new block and search for "Flexible COD Form"</li>
            <li>Select the block to add it to the page</li>
            <li>Configure the settings in the block sidebar</li>
        </ol>
    </div>
    
    <div class="fcod-form-preview">
        <h4>Form Preview</h4>
        <p>Below is a preview of your form with the current settings:</p>
        
        <div class="fcod-preview-container">
            <?php 
            $handler = new Flexible_COD_Handler();
            $handler->display_form(array(
                'product_id' => 0,
                'template' => get_option('fcod_default_template', 'default'),
                'title' => get_option('fcod_form_title', 'أدخل معلوماتك للطلب'),
                'button_text' => get_option('fcod_button_text', 'اشتري الآن - J\'achète')
            ));
            ?>
        </div>
    </div>
</div>

<style>
.fcod-shortcode-help-wrapper {
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.fcod-code-example {
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.fcod-code-example pre {
    background: #282c34;
    color: #abb2bf;
    padding: 15px;
    border-radius: 4px;
    overflow-x: auto;
    margin: 0 0 15px;
}

.fcod-code-example code {
    font-family: Consolas, Monaco, 'Andale Mono', monospace;
    font-size: 14px;
}

.fcod-shortcode-examples h4,
.fcod-php-usage h4,
.fcod-block-usage h4,
.fcod-form-preview h4 {
    margin-top: 25px;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.fcod-preview-container {
    border: 1px dashed #ddd;
    padding: 20px;
    margin-top: 15px;
    border-radius: 4px;
    background: #fafafa;
}
</style>