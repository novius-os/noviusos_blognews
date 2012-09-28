<?php
return array (
    'controller_url'  => 'admin/{{application_name}}/category',
    'model' => '{{namespace}}\\Model_Category',
    'messages' => array(
        'successfully added' => __('Category successfully added.'),
        'successfully saved' => __('Category successfully saved.'),
        'successfully deleted' => __('The category has successfully been deleted!'),
        'you are about to delete, confim' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the category <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple lang' => __('This category exists in <strong>{count} languages</strong>.'),
        'delete in the following languages' => __('Delete this category in the following languages:'),
        'item deleted' => __('This category has been deleted.'),
        'not found' => __('category not found'),
        'error added in lang not parent' => __('This category cannot be added {lang} because its {parent} is not available in this language yet.'),
        'error added in lang' => __('This category cannot be added {lang}.'),
        'item inexistent in lang yet' => __('This category has not been added in {lang} yet.'),
        'add an item in lang' => __('Add a new category in {lang}'),
    ),
    'tab' => array(
        'iconUrl' => 'static/apps/{{application_name}}/img/16/post.png',
        'labels' => array(
            'insert' => __('Add a category'),
            'blankSlate' => __('Translate a category'),
        ),
    ),
    'layout' => array(
        'title' => 'cat_title',
        'large' => true,
        'content' => array(
            'expander' => array(
                'view' => 'nos::form/expander',
                'params' => array(
                    'title'   => 'Propriétés',
                    'nomargin' => true,
                    'options' => array(
                        'allowExpand' => false,
                    ),
                    'content' => array(
                        'view' => 'nos::form/fields',
                        'params' => array(
                            'begin' => '<table class="fieldset">',
                            'fields' => array(
                                'cat_virtual_name',
                                'cat_parent_id'
                            ),
                            'end' => '</table>',
                        ),
                    ),
                ),
            ),
        ),
        'save' => 'save',
    ),
    'fields' => array(
        'cat_title' => array (
            'label' => __('category'),
            'form' => array(
                'type' => 'text',
            ),
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'cat_virtual_name' => array(
            'label' => __('URL: '),
            'widget' => 'Nos\Widget_Virtualname',
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
            'template' => "\t\t<tr><th class=\"{error_class}\">{label}{required}</th><td class=\"{error_class}\">{field} {use_title_checkbox} {error_msg}</td></tr>\n"
        ),
        'cat_parent_id' => array(
            'label' => __('Location: '),
            'widget' => 'Nos\BlogNews\Widget_Category_Selector',
            'widget_options' => array(
                'width'                 => '100%',
                'height'                => '350px',
                'namespace'             => '{{namespace}}',
                'sortable'              => true,
                'application_name'      => '{{application_name}}'
            ),
        ),
        'save' => array(
            'label' => '',
            'form' => array(
                'type' => 'submit',
                'tag' => 'button',
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
    ),
);
