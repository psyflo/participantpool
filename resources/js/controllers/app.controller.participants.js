/*
 * App participants controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async participants_all(skip = 0, take = 0) {
            this.$app.log('Get participants', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`participants?skip=${skip}&take=${take}`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed to get participants', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async participants_get(id = null) {
            this.$app.log('Get participant', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.get(this.$app.http_apiURL(`participants/${id}`));
                    return response.data;

                } catch (error) {
                    
                    this.$app.error('Failed to get participant', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async participants_add(data = null) {
            this.$app.log('Add participant', data, arguments);

            if (data !== null) {

                try {
                    const response = await axios.post(this.$app.http_apiURL(`participants`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to add participant', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async participants_save(id = null, data = {}) {
            this.$app.log('Save participant', id, data, arguments);

            if (id !== null) {

                try {
                    const response = await axios.put(this.$app.http_apiURL(`participants/${id}`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to save participant', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async participants_delete(id = null) {
            this.$app.log('Delete participant', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.delete(this.$app.http_apiURL(`participants/${id}`), { id });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to delete participant', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async participants_validate_email(email = null) {
            this.$app.log('Validate participant email address', email, arguments);

            if (email !== null) {

                try {
                    const response = await axios.get(this.$app.http_apiURL(`participants/validate/email?address=${email}`));
                    return response.data;

                } catch (error) {
                    
                    this.$app.error('Failed to validate participant email address', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async participants_statistics() {
            this.$app.log('Get participants statistics', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`participants/statistics`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed to get participants statistics', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async participants_sendlink(id = null) {
            this.$app.log('Send link to participant', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.get(this.$app.http_apiURL(`participants/send/link/${id}`), { id });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to send link to participant', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
    },
};
