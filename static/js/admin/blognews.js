define([
  'jquery-nos-appdesk'
], function($nos) {
    "use strict";
    return function(appDesk) {
        var appdesk = {

            /**
             * Titre et icône affiché dans l'onglet
             */
            tab : {
                label   : appDesk.i18n('Posts'),
                iconUrl : 'static/apps/'+appDesk.blognews.dir+'/img/'+appDesk.blognews.icon_name+'-32.png'
            },

            /**
             * Liste des actions qu'on peut faire sur les élements.
             * On a : un nom d'action; un label ; et l'action (ce que ça fait quandd on clique sur le button)
             */
            actions : {
                edit : {
                    name : 'edit',
                    primary : true,
                    icon : 'pencil',
                    label : appDesk.i18n('Edit'),
                    action : {
                        action : 'nosTabs',
                        tab : {
                            url: 'admin/'+appDesk.blognews.dir+'/post/insert_update/{{id}}',
                            label: appDesk.i18n('Edit this post')._(),
                            iconUrl : 'static/apps/'+appDesk.blognews.dir+'/img/'+appDesk.blognews.icon_name+'-16.png'
                        }
                    }
                },
                'delete' : {
                    name : 'delete',
                    primary : true,
                    icon : 'trash',
                    label : appDesk.i18n('Delete'),
                    action : {
                        action : 'nosConfirmationDialog',
                        dialog : {
                            contentUrl: 'admin/'+appDesk.blognews.dir+'/post/delete/{{id}}',
                            title: appDesk.i18n('Delete this post')._(),
                            confirmedUrl: 'admin/'+appDesk.blognews.dir+'/post/delete_confirm',
                            appDesk: appDesk
                        }
                    }
                },
                'visualise' : {
                    label : 'Visualise',
                    name : 'visualise',
                    primary : true,
                    iconClasses : 'nos-icon16 nos-icon16-eye',
                    action : {
                        action : 'window.open',
                        url : '{{url}}?_preview=1'
                    }
                }
            },

            // Nom de l'évènement pour recharger les données de la grid
            reloadEvent : appDesk.blognews.namespace+'\\Model_Post',
            appdesk :
            {
                texts :
                {
                    items: appDesk.i18n("posts"),
                    item: appDesk.i18n("post")
                },
                adds:
                {
                    post :
                    {
                        label : appDesk.i18n('Add a post'),
                        action : {
                            action : 'nosTabs',
                            method : 'add',
                            tab : {
                                url: 'admin/'+appDesk.blognews.dir+'/post/insert_update?lang={{lang}}',
                                label: appDesk.i18n('Add a post')._(),
                                iconUrl : 'static/apps/'+appDesk.blognews.dir+'/img/'+appDesk.blognews.icon_name+'-16.png'
                            }
                        }
                    },
                    category :
                    {
                        label : appDesk.i18n('Add a category'),
                        action : {
                            action : 'nosTabs',
                            method : 'add',
                            tab : {
                                url: 'admin/'+appDesk.blognews.dir+'/category/insert_update?lang={{lang}}',
                                label: appDesk.i18n('Add a post')._(),
                                iconUrl : 'static/apps/'+appDesk.blognews.dir+'/img/'+appDesk.blognews.icon_name+'-16.png'
                            }
                        }
                    }
                },

                // Largeur de la colonne des inspecteurs de gauche en px
                splittersVertical :  250,
                grid :
                {
                    proxyUrl : 'admin/'+appDesk.blognews.dir+'/appdesk/json',

                    /**
                     * Liste des colonnes du affichées dans la grid. Les clés sont celles du dataset définies dans le fichier de config PHP
                     */
                    columns :
                    {
                        title :
                        {
                            headerText : appDesk.i18n('Title'),
                            dataKey : 'title'
                        },
                        lang : {
                            lang : true
                        },
                        author : {
                            headerText : appDesk.i18n('Author'),
                            dataKey : 'author'
                        },
                        published : {
                            headerText : appDesk.i18n('Status'),
                            dataKey : 'publication_status'
                        },
                        post_created_at : {
                            headerText : appDesk.i18n('Date'),
                            dataKey : 'post_created_at',
                            dataFormatString  : 'MM/dd/yyyy HH:mm:ss',
                            showFilter : false,
                            sortDirection : 'descending'
                        },
                        actions :
                        {
                            actions : ['edit', 'delete', 'visualise']
                        }
                    }
                },

                /**
                 * Liste des inspecteurs autour de la grid
                 */
                inspectors :
                {
                    startdate : {
                        vertical:   true,
                        label:      appDesk.i18n('Created date'),
                        url:        'admin/noviusos_blognews/inspector/date/list',
                        inputName:  'startdate'
                    },
                    categories :
                    {
                        vertical:   true,
                        reloadEvent : appDesk.blognews.namespace+'\\Model_Category',
                        url:  'admin/'+appDesk.blognews.dir+'/inspector/category/list',
                        inputName:  'cat_id[]',
                        label:      appDesk.i18n('Categories'),
                        treeGrid:
                        {
                            treeUrl: 'admin/'+appDesk.blognews.dir+'/inspector/category/json',
                            sortable: true,
                            columns :
                            {
                                title :
                                {
                                  headerText: appDesk.i18n('Categories'),
                                  dataKey:    'title'
                                },
                                actions :
                                {
                                    actions : [
                                        {
                                            name : 'edit',
                                            primary : true,
                                            label : appDesk.i18n('Edit this category'),
                                            icon : 'pencil',
                                            action : {
                                                action : 'nosTabs',
                                                tab : {
                                                    url: 'admin/' + appDesk.blognews.dir + '/category/insert_update/{{id}}',
                                                    label: 'Edit the "{{title}}" folder'
                                                }
                                            }
                                        },
                                        {
                                            name : 'delete',
                                            label : appDesk.i18n('Delete this category'),
                                            icon : 'trash',
                                            action : {
                                                action : 'nosConfirmationDialog',
                                                dialog : {
                                                    contentUrl: 'admin/'+appDesk.blognews.dir+'/category/delete/{{id}}',
                                                    title: appDesk.i18n('Delete this category')._(),
                                                    confirmedUrl: 'admin/'+appDesk.blognews.dir+'/category/delete_confirm',
                                                    appDesk: appDesk
                                                }
                                            }
                                        }
                                    ]
                                }
                            }
                        } // ~treeGrid
                    },
                    tags : {
                        reloadEvent : appDesk.blognews.namespace+'\\Model_Tag',
                        label : appDesk.i18n('Tags'),
                        url : 'admin/'+appDesk.blognews.dir+'/inspector/tag/list',
                        grid : {
                            urlJson : 'admin/'+appDesk.blognews.dir+'/inspector/tag/json',
                            columns : {
                                title : {
                                    headerText : appDesk.i18n('Tag'),
                                    dataKey : 'title'
                                },
                                actions : {
                                    actions : [
                                        {
                                            name : 'delete',
                                            action : {
                                                action : 'nosConfirmationDialog',
                                                dialog : {
                                                    contentUrl: 'admin/'+appDesk.blognews.dir+'/tag/delete/{{id}}',
                                                    title: appDesk.i18n('Delete a tag')._(),
                                                    confirmedUrl: 'admin/'+appDesk.blognews.dir+'/tag/delete_confirm',
                                                    appDesk: appDesk
                                                }
                                            },
                                            label : appDesk.i18n('Delete'),
                                            primary : true,
                                            icon : 'trash'
                                        }
                                    ]
                                }
                            }
                        },
                        inputName : 'tag_id[]',
                        vertical: true
                    },
                    authors : {
                        reloadEvent : appDesk.blognews.namespace+'\\Model_User',
                        label : appDesk.i18n('Authors'),
                        url : 'admin/'+appDesk.blognews.dir+'/inspector/author/list',
                        grid : {
                            columns : {
                                title : {
                                    headerText : appDesk.i18n('Author'),
                                    dataKey : 'title'
                                }
                            },
                            urlJson : 'admin/'+appDesk.blognews.dir+'/inspector/author/json'
                        },
                        inputName : 'author_id[]',
                        vertical : true
                    }
                } // ~inspectors
            }
        };
        if (!Noviusdev.BlogNews.withTags)
        {
           delete appdesk.appdesk.inspectors.tags;
        }
        if (!Noviusdev.BlogNews.withAuthors)
        {
           delete appdesk.appdesk.inspectors.authors;
           delete appdesk.appdesk.grid.columns.author;
        }
        return appdesk;
    };
});
