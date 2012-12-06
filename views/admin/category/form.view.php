<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$fieldset->populate_with_instance($category);
$fieldset->form()->set_config('field_template', "\t\t<tr><th class=\"{error_class}\">{label}{required}</th><td class=\"{error_class}\">{field} {error_msg}</td></tr>\n");

echo $fieldset->open(Uri::current());
//<?= $fieldset->open('admin/noviusos_blog/blog/form'.($blog->is_new() ? '' : '/'.$blog->blog_id));

Event::register_function('config|noviusos_blognews::views/admin/category/form', 1, function(&$config) use ($fieldset, $category) {
    $config['fieldset'] = $fieldset;
    $config['item'] = $category;
    $config['content'][0]->set('content', $fieldset->field('parent_id'));
});

$config = Config::load('noviusos_blognews::views/admin/category/form', true);

echo View::forge('nos::form/layout_standard', $config, false);

echo $fieldset->close();

?>

<script type="text/javascript">
    require(
        ['jquery-nos-ostabs'],
        function ($) {
            $(function () {
                var tabInfos = <?= \Format::forge()->to_json($tabInfos) ?>;

                var $container = $('#<?= $fieldset->form()->get_attribute('id') ?>');
                $container.nosOnShow('bind', function() {
                    $container.nosTabs('update', tabInfos);
                });
            });
        });
</script>
