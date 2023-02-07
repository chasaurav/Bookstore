<template>
    <div class="card" style="width: 18rem;">
        <img :src="book.image" :alt="book.image" class="card-img-top">
        <div class="card-body">
            <h3 class="card-title"> <strong>{{book.title}}</strong></h3>
            <p class="card-text">
                Genre: {{book.genre_name}} <br>
                Published On: {{book.published}}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["book"]
    }
</script>
