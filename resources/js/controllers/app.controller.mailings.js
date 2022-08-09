/*
 * App studies controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async mailings_all() {
            try {
                const response = await axios.get(this.$app.http_apiURL(`mailings`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed get mailings', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async mailings_participant_remove(id = 0, participant_id = 0) {
            try {
                const response = await axios.delete(this.$app.http_apiURL(`mailings/${id}/participants/${participant_id}`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed remove mailing participant', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async mailings_get(id = 0) {
            try {
                const response = await axios.get(this.$app.http_apiURL(`mailings/${id}`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed get mailing', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async mailings_add({ mailing = { name: null, study_id: null, random: null }, participants = [] } = {}) {
            if (name !== null) {

                try {
                    const response = await axios.post(this.$app.http_apiURL(`mailings`), { mailing, participants });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed add mailing', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async mailings_save({ id = null, name = null, subject = null, content = null, state = null } = {}) {
            if (id !== null && name !== null) {

                try {
                    const response = await axios.put(this.$app.http_apiURL(`mailings/${id}`), { id, name, subject, content, state });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed save mailing', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async mailings_delete({ id = null } = {}) {
            if (id !== null) {

                try {
                    const response = await axios.delete(this.$app.http_apiURL(`mailings/${id}`), { id });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to delete mailing', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
    },
};
