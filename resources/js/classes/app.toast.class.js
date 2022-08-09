/**
 * Define Toast mixin
 */
 export default {
    data() {
        return {
            toast: {
                autoHideDelay: 5000,
                toaster: 'b-toaster-top-right',
            },
        };
    },
    methods: {
        toast_showSuccess(message) {
            this.$bvToast.toast(`${message}`, { title: 'Success', variant: 'success', autoHideDelay: this.toast.autoHideDelay, appendToast: true, toaster: this.toast.toaster });
        },
        toast_showError(message) {
            this.$bvToast.toast(`${message}`, { title: 'Error', variant: 'danger', autoHideDelay: this.toast.autoHideDelay, appendToast: true, toaster: this.toast.toaster });
        },
    },
};
