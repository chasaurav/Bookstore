@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <book-list></book-list>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
    $(document).on('click', '.delete-book-btn', function() {
            const id = $(this).data('id');

            AlertModal(modalContext => {
                modalContext.hide();

                axios.delete(`${BASE_URL}/books/${id}`)
                    .then(res => {
                        CustomEvent.fire('refresh-book-list');
                        showToast(TOAST_DEFAUT_SUCCESS_HEADER, "Book has been deleted successfully.",TOAST_SUCCESS);
                    })
                    .catch(err => showToast(TOAST_DEFAUT_ERROR_HEADER, TOAST_DEFAUT_ERROR_BODY, TOAST_ERROR));
            });
        });

        const AlertModal = (func) => {
            const context = new bootstrap.Modal('#alertModal', {
                backdrop: 'static',
                focus: true,
                keyboard: false,
            });

            context.show();

            $("#positive-btn").off('click');
            $("#positive-btn").on('click', () => func(context));
        };
</script>
@endsection