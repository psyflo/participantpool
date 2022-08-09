/*
 * App profile controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async profile_get() {
            this.$app.log('Get profile', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`profile`));
                return response.data;

            } catch (error) {
                
                this.error('Failed to get profile', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async profile_save(data = {}) {
            this.$app.log('Save profile', data, arguments);

            try {
                const response = await axios.post(this.$app.http_apiURL(`profile`), { data });
                return response.data;

            } catch (error) {

                this.$app.error('Failed to save profile', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
    },
};
