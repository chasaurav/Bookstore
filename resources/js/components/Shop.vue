<template>
    <input type="text" class="form-control" @input="debounceInput" placeholder="Search Books..." />
    <div class="col-12 filter-box">
        <div class="row form-group">
            <div class="col-3">
                <label>Author</label>
                <select ref="author" class="form-select" v-model="filters.authorId" @change="searchBooks(lastUsedAPI || null)">
                    <option value="">Select</option>
                    <option v-for="(author, i) in authors" :key="i" :value="author.id">{{author.name}}</option>
                </select>
            </div>
            <div class="col-2">
                <label>Genre</label>
                <select ref="genre" class="form-select" v-model="filters.genreId" @change="searchBooks(lastUsedAPI || null)">
                    <option value="">Select</option>
                    <option v-for="(genre, i) in genres" :key="i" :value="genre.id">{{genre.name}}</option>
                </select>
            </div>
            <div class="col-2">
                <label>ISBN</label>
                <select ref="isbn" class="form-select" v-model="filters.isbn" @change="searchBooks(lastUsedAPI || null)">
                    <option value="">Select</option>
                    <option v-for="(isbn, i) in isbns" :key="i" :value="isbn.isbn">{{isbn.isbn}}</option>
                </select>
            </div>
            <div class="col-2">
                <label>Date From</label>
                <input type="date" class="form-control" v-model="filters.dateFrom" @change="searchBooks(lastUsedAPI || null)" />
            </div>
            <div class="col-2">
                <label>Date To</label>
                <input type="date" class="form-control" v-model="filters.dateTo" @change="searchBooks(lastUsedAPI || null)" />
            </div>
            <div class="col-1 d-flex align-items-end">
                <button type="btn" class="btn btn-sm btn-link" @click="clearFilters">Clear All</button>
            </div>
        </div>
    </div>

    <p v-if="paginatedBookData.data && paginatedBookData.data.length == 0" class="mt-3">No Books Found! Try searching again.</p>

    <div v-if="paginatedBookData.data && paginatedBookData.data.length" class="mt-4">
        <nav v-if="paginatedBookData.last_page > 1">
            <ul class="pagination pagination-lg justify-content-center">
                <li v-for="(link, i) in paginatedBookData.links" :key="i" class="page-item" :class="!link.url ? 'disabled' : ''">
                    <a class="page-link" :class="link.active ? 'active' : ''" @click="searchBooks(link.url || null)" href="#" v-html="link.label"></a>
                </li>
            </ul>
        </nav>

        <div class="flexed-container">
            <a href="#" v-for="(book, i) in paginatedBookData.data" :key="i" data-bs-toggle="modal" data-bs-target="#staticBackdrop" @click.prevent="setSelectedBookData(book)">
                <book-card :book="book"></book-card>
            </a>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">{{selectedBook ? selectedBook.title : ''}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <book-details class="mt-auto" :book="selectedBook || {}"></book-details>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.flexed-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin: 20px;
}
.filter-box {
    background: #eee;
    padding: 10px;
    margin: 15px auto;
    border-radius: 6px;
}
</style>

<script>
    import axios from "axios";
    import BookCard from './BookCard.vue';

    export default {
        props: ["authors", "genres", "isbns"],
        components: {
            "book-card": BookCard
        },
        data() {
            return {
                baseUrl: BASE_URL,
                appiInProgress: false,
                paginatedBookData: {},
                selectedBook: null,
                lastUsedAPI: '',
                filters: {
                    authorId: '',
                    genreId: '',
                    isbn: '',
                    dateFrom: '',
                    dateTo: '',
                }
            };
        },
        methods: {
            debounceInput: _.debounce(function({target}) {
                this.searchBooks(null, target.value.trim());
            }, 500),
            searchBooks(url, searchParam) {
                this.appiInProgress = true;
                const generatedUrl = this.generateUrl(url || `${BASE_URL}/search?query=${searchParam}&page=1`);

                axios.get(generatedUrl)
                    .then(({data}) => {
                        this.lastUsedAPI = generatedUrl;
                        this.paginatedBookData = data;
                    })
                    .catch(({response}) => {
                        if (response.data && response.data.errors) {
                            showToast(TOAST_DEFAUT_ERROR_HEADER, TOAST_DEFAUT_ERROR_BODY, TOAST_ERROR);
                        }
                    })
                    .finally(() => this.appiInProgress = false); 
            },
            setSelectedBookData(book) {
                this.selectedBook = book;
            },
            generateUrl(url) {
                var parsedURI = new URLSearchParams(url);

                for (const key in this.filters) {
                    var value = this.filters[key].toString().trim();

                    if (value == '') {
                        parsedURI.delete(key);
                    } else {
                        parsedURI.set(key, value);
                    }
                }

                return `${BASE_URL}/search?${parsedURI.toString().split('search%3F')[1]}`;
            },
            clearFilters() {
                this.filters = {
                    authorId: '',
                    genreId: '',
                    isbn: '',
                    dateFrom: '',
                    dateTo: '',
                };

                this.searchBooks(this.lastUsedAPI || null);
            }
        }
    }
</script>
