<template>
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center gap-3">
                <img :src="book.image" :alt="book.image" class="rounded border-2" style="width: 18%" />
                <div>
                    <h2><strong>{{book.title}}</strong></h2>
                    <p>
                        <strong>ISBN:</strong> {{book.isbn}} <br>
                        <strong>Author:</strong> {{book.author_name}} <br>
                        <strong>Genre:</strong> {{book.genre_name}} <br>
                        <strong>Publisher:</strong> {{book.publisher_name}} <br>
                        <strong>Published On:</strong> {{book.published}}
                    </p>
                    <p><strong>Description:</strong> <br> {{book.description}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["book"]
    }
</script>
