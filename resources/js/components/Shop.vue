<template>
    <input type="text" class="form-control" @input="debounceInput" placeholder="Search Books..." />
    <div style="margin: 20px">
        <nav>
            <ul class="pagination pagination-lg justify-content-center">
                <li v-for="(link, i) in paginatedBookData.links" :key="i" class="page-item" :class="!link.url ? 'disabled' : ''">
                    <a class="page-link" :class="link.active ? 'active' : ''" @click="searchBooks(link.url)" href="#" v-html="link.label"></a>
                </li>
            </ul>
        </nav>

        <div class="flexed-container">
            <book-card v-for="(book, i) in paginatedBookData.data" :key="i" :book="book"></book-card>
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
        components: { "book-card": BookCard },
        data() {
            return {
                baseUrl: BASE_URL,
                appiInProgress: false,
                searchParam: '',
                paginatedBookData: {},
            };
        },
        methods: {
            debounceInput: _.debounce(function({target}) {
                if (this.appiInProgress || target.value.length < 2) return;
                this.searchBooks(null, target.value);
            }, 500),
            searchBooks(url, searchParam) {
                this.appiInProgress = true;
                this.paginatedBookData = {};

                axios.get(url || `${BASE_URL}/search?query=${searchParam}&page=1`)
                    .then(({data}) => this.paginatedBookData = data)
                    .catch(({response}) => {
                        if (response.data && response.data.errors) {
                            showToast(TOAST_DEFAUT_ERROR_HEADER, TOAST_DEFAUT_ERROR_BODY, TOAST_ERROR);
                        }
                    })
                    .finally(() => this.appiInProgress = false); 
            }
        }
    }
</script>
