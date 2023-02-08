<template>
    <input type="text" class="form-control" @input="debounceInput" placeholder="Search Books..." />

    <p v-if="paginatedBookData.data && paginatedBookData.data.length == 0" class="mt-3">No Books Found! Try searching again.</p>

    <div v-if="paginatedBookData.data && paginatedBookData.data.length" class="mt-4">
        <nav v-if="paginatedBookData.last_page > 1">
            <ul class="pagination pagination-lg justify-content-center">
                <li v-for="(link, i) in paginatedBookData.links" :key="i" class="page-item" :class="!link.url ? 'disabled' : ''">
                    <a class="page-link" :class="link.active ? 'active' : ''" @click="searchBooks(link.url)" href="#" v-html="link.label"></a>
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
</style>

<script>
    import axios from "axios";
    import BookCard from './BookCard.vue';

    export default {
        components: {
            "book-card": BookCard
        },
        data() {
            return {
                baseUrl: BASE_URL,
                appiInProgress: false,
                searchParam: '',
                paginatedBookData: {},
                selectedBook: null,
            };
        },
        methods: {
            debounceInput: _.debounce(function({target}) {
                if (this.appiInProgress || target.value.trim().length < 2) return;
                this.searchBooks(null, target.value.trim());
            }, 500),
            searchBooks(url, searchParam) {
                this.appiInProgress = true;

                axios.get(url || `${BASE_URL}/search?query=${searchParam}&page=1`)
                    .then(({data}) => {
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
            }
        }
    }
</script>
