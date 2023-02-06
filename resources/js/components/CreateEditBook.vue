<template>
    <div class="card">
        <div class="bg-primary card-header p-3 text-white d-flex align-items-center gap-2">
            <a :href="`${baseUrl}/books`" class="btn btn-sn btn-primary"><i class="fas fa-chevron-left"></i></a>
            <h4 style="margin:0"><strong>{{isEditMode ? 'Edit book' : 'Create a new book'}}</strong></h4>
        </div>

        <div class="card-body">
            <form ref="bookForm" @submit.prevent="createNewBook" enctype="multipart/form-data">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="title" class="form-label">Title</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="formDataErr.title ? 'is-invalid' : ''"
                            id="title"
                            name="title"
                            placeholder="Enter Title"
                            v-model="book.title"
                            :autofocus="!isEditMode"
                        />
                        <div v-if="formDataErr.title" class="invalid-feedback">{{formDataErr.title}}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input
                            type="text"
                            ref="isbnInput"
                            class="form-control"
                            :class="formDataErr.isbn ? 'is-invalid' : ''"
                            id="isbn"
                            name="isbn"
                            placeholder="Enter ISBN"
                            v-model="book.isbn"
                        />
                        <div v-if="formDataErr.isbn" class="invalid-feedback">{{formDataErr.isbn}}</div>
                    </div>

                    <div class="col-md-4">
                        <label for="image" class="form-label">Image</label>
                        <input
                            type="file"
                            class="form-control"
                            :class="formDataErr.image ? 'is-invalid' : ''"
                            id="image"
                            name="image"
                            accept="image/x-png,image/gif,image/jpeg"
                        />
                        <div v-if="formDataErr.image" class="invalid-feedback">{{formDataErr.image}}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div :class="isEditMode ? 'col-md-3' : 'col-md-4'">
                        <label for="author" class="form-label">Author</label>
                        <select ref="author" id="author" name="author" class="form-select">
                            <option>Select</option>
                            <template v-for="(author, i) in authors" :key="i">
                                <option :value="author.id" :selected="author.id == book.author_id">{{author.name}}</option>
                            </template>
                        </select>
                        <div v-if="formDataErr.author" class="custom-invalid">{{formDataErr.author}}</div>
                    </div>
                    <div :class="isEditMode ? 'col-md-3' : 'col-md-4'">
                        <label for="genre" class="form-label">Genre</label>
                        <select ref="genre" id="genre" name="genre" class="form-select">
                            <option>Select</option>
                            <template v-for="(genre, i) in genres" :key="i">
                                <option :value="genre.id" :selected="genre.id == book.genre_id">{{genre.name}}</option>
                            </template>
                        </select>
                        <div v-if="formDataErr.genre" class="custom-invalid">{{formDataErr.genre}}</div>
                    </div>
                    <div :class="isEditMode ? 'col-md-3' : 'col-md-4'">
                        <label for="publisher" class="form-label">Publisher</label>
                        <select ref="publisher" id="publisher" name="publisher" class="form-select">
                            <option>Select</option>
                            <template v-for="(publisher, i) in publishers" :key="i">
                                <option :value="publisher.id" :selected="publisher.id == book.publisher_id">{{publisher.name}}</option>
                            </template>
                        </select>
                        <div v-if="formDataErr.publisher" class="custom-invalid">{{formDataErr.publisher}}</div>
                    </div>
                    <div v-if="isEditMode" class="col-md-3">
                        <label for="published" class="form-label">Published</label>
                        <input type="text" ref="dateInput" class="form-control" :class="formDataErr.published ? 'is-invalid' : ''" id="published" name="published" v-model="book.published">
                        <div v-if="formDataErr.published" class="invalid-feedback">{{formDataErr.published}}</div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" :class="formDataErr.description ? 'is-invalid' : ''" id="description" name="description" rows="5" placeholder="Enter Description" v-model="book.description"></textarea>
                        <div v-if="formDataErr.description" class="invalid-feedback">{{formDataErr.description}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary" :disabled="appiInProgress">
                            <i v-if="appiInProgress" class="fa fa-spinner fa-spin fa-fw"></i> {{ isEditMode ? 'Update Book' : 'Create Book' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <book-details v-if="isEditMode" :book="book"></book-details>
</template>

<style scoped>
.custom-invalid {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
</style>

<script>
    import axios from "axios";
    import BookDetails from "./BookDetails.vue";

    export default {
        props: ["book", "authors", "genres", "publishers"],
        components: { "book-details": BookDetails },
        data() {
            return {
                baseUrl: BASE_URL,
                isEditMode: this.book.id,
                appiInProgress: false,
                formDataErr: {
                    title: '',
                    isbn: '',
                    author: '',
                    genre: '',
                    publisher: '',
                    description: '',
                    image: '',
                }
            };
        },
        methods: {
            createNewBook() {
                this.clearErrors();
                this.appiInProgress = true;
                let formData = new FormData(this.$refs.bookForm);

                const promise = this.isEditMode
                    ? axios.post(`${BASE_URL}/books/${this.book.id}`, formData)
                    : axios.post(`${BASE_URL}/books`, formData);

                promise.then(res => {
                    showToast(TOAST_DEFAUT_SUCCESS_HEADER, `Book ${this.isEditMode ? 'updated' : 'created'} successfully.`, TOAST_SUCCESS);

                    setTimeout(() => window.location.href = `${this.baseUrl}/books`, 800);
                }).catch(err => this.showFormErrors(err.response.data));
            },
            showFormErrors(errData) {
                this.appiInProgress = false;

                if (errData && errData.errors) {
                    showToast(TOAST_DEFAUT_ERROR_HEADER, TOAST_DEFAUT_ERROR_BODY, TOAST_ERROR);

                    for (var property in errData.errors) {
                        this.formDataErr[property] = errData.errors[property].join(',');
                    }
                }
            },
            clearErrors() {
                this.formDataErr = {
                    title: '',
                    isbn: '',
                    author: '',
                    genre: '',
                    publisher: '',
                    description: '',
                    image: '',
                };
            }
        },
        mounted() {
            $(this.$refs.author).select2();
            $(this.$refs.genre).select2();
            $(this.$refs.publisher).select2();

            $(this.$refs.isbnInput).inputmask("999-9-99-999999-9");
            if (this.isEditMode) $(this.$refs.dateInput).inputmask("99-99-9999");
        }
    }
</script>
