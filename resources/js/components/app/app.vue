<template>
    <div>
        <header-view></header-view>

        <div class="mt-40"></div>

        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>

        <div class="mt-40"></div>

        <footer-view></footer-view>

        <quick-view></quick-view>

        <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
    export default {
        created(){
            this.$store.dispatch('getCurrencies');
            this.$store.dispatch('getCart');
            this.$store.dispatch('getWishlist');

            Fire.$on('success', success => {
                Toast.fire({icon: 'success', title: success,});
            });

            Fire.$on('error', error => {
                Toast.fire({icon: 'error', title: error,});
            });

            Fire.$on('info', info => {
                Toast.fire({icon: 'info', title: info,});
            });
        },

        mounted() {
            // Mobile Menu Active
            $(function () {
                $('#navigation').slicknav({
                    label: '',
                    closedSymbol: '+',
                    openedSymbol: '-',
                    prependTo: '.mobile-menu',
                });
            });

            // Click To Top Button Show/Hide
            $(window).on("scroll", function () {
                if ($(window).scrollTop() >= 200) {
                    $(".back-to-top").fadeIn(100);
                } else {
                    $(".back-to-top").fadeOut(500);
                }
            });

            // Click To Top Button Trigger
            $(".back-to-top").on("click", function () {
                $("html, body").animate({scrollTop: 0,}, 600);
                return false;
            });
        },
    }
</script>

<style scoped>
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s;
    }
</style>
