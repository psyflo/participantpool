/*
 * App users controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async users_all() {
            this.$app.log('Get users', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`users`));
                return response.data;

            } catch (error) {
                
                this.error('Failed to get users', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async users_get(id = null) {
            this.$app.log('Get user', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.get(this.$app.http_apiURL(`users/${id}`));
                    return response.data;

                } catch (error) {
                    
                    this.$app.error('Failed to get user', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async users_save(id = null, data = {}) {
            this.$app.log('Save user', id, data, arguments);

            if (id !== null) {

                try {
                    const response = await axios.put(this.$app.http_apiURL(`users/${id}`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to save user', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async users_add(data = null) {
            this.$app.log('Add user', data, arguments);

            if (data !== null) {

                try {
                    const response = await axios.post(this.$app.http_apiURL(`users`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to add user', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async users_delete(id = null) {
            this.$app.log('Delete user', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.delete(this.$app.http_apiURL(`users/${id}`), { id });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to delete user', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
    },
};
