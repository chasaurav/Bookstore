<template>
    <div class="card">
        <div class="bg-primary card-header p-3 text-white d-flex align-items-center justify-content-between">
            <h4 style="margin:0"><strong>Book List</strong></h4>
            <a :href="`${baseUrl}/books/create`" class="btn btn-sm btn-outline-light"><i class="fas fa-plus"></i> Create New Book</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table ref="booklistDatatable" class="table table-striped table-borderless" style="padding: 10px 6px;">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="dont-show">id</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Description</th>
                            <th>ISBN</th>
                            <th>Publisher</th>
                            <th>Published On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tableContext: null,
                baseUrl: BASE_URL,
                datatableConfig: {
                    pagingType: "full_numbers",
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    sort: false,
                    info: true,
                    ajax: `${BASE_URL}/books/datatable`,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                    columns: [
                        {data: "id"},
                        {data: "title"},
                        {data: "author_name"},
                        {data: "genre_name"},
                        {data: "short_description"},
                        {data: "isbn"},
                        {data: "publisher_name"},
                        {data: "published"},
                        {data: "action"},
                    ],
                    columnDefs: [{
                        width: '8%',
                        targets: [1, 2, 3, 5, 6, 8],
                        className: "dt-center",
                    }, {
                        width: '20%',
                        targets: [4],
                        className: "dt-center",
                    }, {
                        width: '10%',
                        targets: [7],
                        className: "dt-center",
                    },
                    {
                        'targets': 'dont-show',
                        visible: false
                    }],
                }
            };
        },
        methods: {
            loadBookList() {
                if (this.tableContext) this.tableContext.destroy();

                this.tableContext = $(this.$refs.booklistDatatable).DataTable(this.datatableConfig);
            }
        },
        mounted() {
            this.loadBookList();
            CustomEvent.listen('refresh-book-list', this.loadBookList);
        }
    }
</script>
