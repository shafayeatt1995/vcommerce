<template>
    <!--Not found area start-->
    <div class="error">
        <div class="container text-center">
            <img src="/images/404.svg" alt="404 not found" class="img-fluid h-350">
            <p>Oops! Error 404</p>
            <h1>Page Not Found</h1>
            <h5>We can't seem to find the page you're looking for.</h5>
            <router-link to="/" class="cta-blue-btn">Go To Home</router-link>
        </div>
    </div>
    <!--Not found area end-->
</template>

<script>
    export default{
        title: 'Not Found'
    }
</script>