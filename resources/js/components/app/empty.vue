<template>
    <!--Empty message area start-->
    <div class="text-center">
        <img src="/images/empty.svg" :alt="message" class="img-fluid h-350 w-auto">
        <h4>{{ message }}</h4>
    </div>
    <!--Empty message area end-->
</template>

<script>
    export default {
        props: {
            message: '',
        },
    };
</script>