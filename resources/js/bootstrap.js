/**
 * Install lodash helper library
 * 
 * See: https://lodash.com/
 */
window._ = require('lodash');

/**
 * 
 * Install jQuery
 * 
 * See: https://jquery.com/
 */
window.$ = window.jQuery = require('jquery');

/**
 * Install bootstrap scripting
 * 
 * See: https://getbootstrap.com/docs/5.1/getting-started/introduction/
 */
window.bootstrap = require('bootstrap');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Install popper (Tooltip & Popover Positioning Engine)
 * 
 * See: https://popper.js.org/
 */
window.Popper = require('@popperjs/core');

/**
 * Install moment
 * 
 * See: https://momentjs.com/docs/
 */
window.moment = require('moment');
