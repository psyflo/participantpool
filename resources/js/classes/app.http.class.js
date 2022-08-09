/*
 * App HTTP class
 */
export default {
    data() {
        return {
            http: {},
        };
    },
    methods: {
        http_appURL(path) {
            return `${this.config_appURL()}/admin/${path}`;
        },
        http_getURL(path) {
            return `${this.config_appURL()}/admin/api/${path}`;
        },
        http_apiURL(path) {
            return `${this.config_appURL()}/admin/api/${path}`;
        },
    },
};
