<template>
    <!-- Terms & condition area start-->
    <div class="terms" v-if="!loading">
        <div class="container">
            <div class="section-title">
                <h3><i class="fas fa-book"></i> Terms And Conditions</h3>
            </div>

            <hr>
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="terms-body" v-html="terms"></div>
                </div>
            </div>
        </div>
    </div>
    <loading v-else></loading>
    <!-- Terms & condition area end-->
</template>

<script>
    export default {
        title: 'Terms & Condition',
        data(){
            return {
                terms: '',
                loading: true
            }
        },

        methods: {
            loadTerms(){
                this.$Progress.start();
                axios.get('/api/terms').then(response=>{
                    this.terms = JSON.parse(response.data.term);
                    this.loading = false;
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            }
        },

        created(){
            this.loadTerms();
        }
    }
</script>