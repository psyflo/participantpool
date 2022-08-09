/*
 * App settings controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async settings_all() {
            this.$app.log('Get settings', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`settings`));
                return response.data;

            } catch (error) {
                
                this.error('Failed to get settings', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async settings_save(id = null, data = {}) {
            this.$app.log('Save setting', id, data, arguments);

            if (id !== null) {

                try {
                    const response = await axios.put(this.$app.http_apiURL(`settings/${id}`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to save setting', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
    },
};
