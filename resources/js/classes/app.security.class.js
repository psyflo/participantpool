/*
 * App security class
 */
export default {
    data() {
        return {
            security: {
                current: null,
                context: { PARTICIPANT: 'participant', STUDY: 'study', MAILING: 'mailing', USER: 'user' },
                ability: { CREATE: 'create', READ: 'read', UPDATE: 'update', DELETE: 'delete' },
                role: { ADMIN: 'admin', MANAGER: 'manager' },
            }
        };
    },
    computed: {
        security_authenticated() {

            return this.security.current && this.security.current.user && this.security.current.user.id !== null;
        }
    },
    methods: {
        security_can({ context = null, ability = null } = {}) {

            if (this.security.current && this.security.current.abilities[context] && this.security.current.abilities[context][ability]) {

                return this.security.current.abilities[context][ability];
            }

            return false;
        },
        security_role({ role = null} = {}) {

            if (this.security.current && this.security.current.roles[role]) {

                return this.security.current.roles[role];
            }

            return false

        },
        security_owner({ object = null } = {}) {

            if (object !== null && object['user_id'] !== null && this.security.current.user.id !== null) {

                return object['user_id'] === this.security.current.user.id;
            }

            return false
        },
        security_user() {

            return this.security_authenticated ? this.security.current.user : { id: null };
        },
        security_clear() {

            this.security.current = null;
        },
        async security_load() {
            
            try {
                const data = await this.security_get();
                this.security.current = data;

            } catch (error) {

                this.error('Security loading failed.');
                this.security_clear();

            } finally {

                this.log('Security loaded.');
            }
        },
    },
    created() {
        this.log('Security class created.');

        this.security_load();
    },
};
