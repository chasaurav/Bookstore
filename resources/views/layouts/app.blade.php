<!doctype html>
<html lang="en">

    @include('layouts.partials.htmlHeader')

    <body>
        <div id="app">

            @include('layouts.partials.mainHeader')

            <main class="py-4">
                <toaster></toaster>

                @yield('content')
            </main>

            @include('layouts.partials.alertModal')
        </div>
    </body>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <!-- Datatable -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    
    <!-- Inputmask -->
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

    <script>
        BASE_URL = '{{url("/")}}';
        TOAST_SUCCESS = 1;
        TOAST_ERROR = 2;
        TOAST_WARNING = 3;
        TOAST_INFO = 4;

        TOAST_DEFAUT_SUCCESS_HEADER = "Success";

        TOAST_DEFAUT_ERROR_HEADER = "Error";
        TOAST_DEFAUT_ERROR_BODY = "Something went wrong. Please try again.";

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
</html>
