/**
 * This file is part of SmartWork.
 *
 * SmartWork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SmartWork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with SmartWork.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   SmartWork
 * @author    Marian Pollzien <map@wafriv.de>
 * @copyright (c) 2015, Marian Pollzien
 * @license   https://www.gnu.org/licenses/lgpl.html LGPLv3
 */

/**
 * @param {jQuery} $ The jQuery object
 *
 * @returns void
 */
(function($) {
    $.widget('sw.ajax', {
        options: {
            target: '',
            ajaxParameter: 'ajax',
            success: function () {},
            error: function () {},
            sendCallback: function () {}
        },

        /**
         * Create the ajax widget
         *
         * @returns {void}
         */
        _create: function () {
            this._config = {
                target: this._getTarget()
            };

            this._addListener();
        },

        /**
         * Get the ajax target.
         *
         * @returns {String}
         */
        _getTarget: function () {
            var url = document.createElement('a');

            if (this.options.target)
            {
                url.href = this.options.target;
            }
            else
            {
                if (this.element.attr('href'))
                {
                    url.href = this.element.attr('href');
                }

                if (this.element.attr('src'))
                {
                    url.href = this.element.attr('src');
                }

                if (url.href === '')
                {
                    console.error('No target found!');
                }
            }

            if (url.search.search(this.options.ajaxParameter) === -1)
            {
                if (url.search === '')
                {
                    url.search = '?' + this.options.ajaxParameter + '=1';
                }
                else
                {
                    url.search += '&' + this.options.ajaxParameter + '=1';
                }
            }

            return url.href;
        },

        /**
         * Add event listener.
         *
         * @returns {void}
         */
        _addListener: function () {
            this.element.on('click.a touch.a', $.proxy(function (event) {
                var data = {};
                event.preventDefault();

                this._sendAjaxRequest($.extend(data, this.options.sendCallback(this.element)));
            }, this));
        },

        /**
         * Send an asyncronous ajax request via GET.
         *
         * @returns {void}
         */
        _sendAjaxRequest: function (data) {
            $.ajax(
                this._config.target,
                {
                    data: data,
                    success: $.proxy(function (results) {
                        this.options.success(this.element, results);
                    }, this),
                    error: $.proxy(function (results) {
                        this.options.error(this.element, results);
                    }, this)
                }
            );
        },

        /**
         * Remove registered event listener.
         *
         * @returns {void}
         */
        _destroy: function () {
            this.element.off('click.a touch.a');
        }
    });
}(jQuery));