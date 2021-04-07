<template>
    <div>
        <h1 v-if="$middleware.admin()">Ami Admin Home Page</h1>
        <h1 v-if="$middleware.seller()">Ami seller Home Page</h1>
        <h1>Ami Home</h1>
    </div>
</template>


<script>
    export default {
        title: 'Home',
        data() {
            return {};
        },

        methods: {},
    };
</script>