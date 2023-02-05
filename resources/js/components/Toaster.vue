<template>
    <div class="end-0 p-3 position-fixed toast-container top-0">
        <div ref="toastHtml" class="toast" :class="cssClass" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">{{header}}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{msg}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                header: '',
                msg: '',
                cssClass: '',
            }
        },
        mounted() {
            window.showToast = (header, msg, type) => {
                this.header = header;
                this.msg = msg;

                switch (type) {
                    case TOAST_SUCCESS:
                        this.cssClass = "bg-success text-bg-success";
                        break;
                    case TOAST_ERROR:
                        this.cssClass = "bg-danger text-bg-danger";
                        break;
                    case TOAST_WARNING:
                        this.cssClass = "bg-warning text-bg-warning";
                        break;
                    case TOAST_INFO:
                        this.cssClass = "bg-info text-bg-info";
                        break;
                    default:
                        this.cssClass = "";
                }

                const context = new bootstrap.Toast($(this.$refs.toastHtml))
                setTimeout(() => context.show());
            }
        }
    }
</script>
