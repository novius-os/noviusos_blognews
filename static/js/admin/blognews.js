/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

define([
  'jquery-nos-appdesk'
], function($nos) {
    "use strict";
    return function(appDesk) {
        var placeholderReplace = function (obj) {
            if ($.type(obj) === 'string') {
                return obj.replace(/{{blognews.([\w]+)}}/g, function(str, p1, offset, s) {
                        return appDesk.blognews[p1];
                    });
            } else if ($.isPlainObject(obj) || $.isArray(obj)) {
                $.each(obj, function(key, value) {
                    obj[key] = placeholderReplace(value);
                });
            }
            return obj;
        };
        var appdesk = placeholderReplace(appDesk);

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
