<template>
    <!-- FAQ area start-->
    <div class="faq" v-if="!loading">
        <div class="container">
            <div class="section-title">
                <h3><i class="fas fa-question"></i> Frequently Asked Questions</h3>
            </div>
            <hr>

            <div class="faq-body mb-3" v-for="(faq, key) in faq" :key="key">
                <h4 class="strong">{{faq.title}}</h4>
                <div class="faq-box">
                    <div class="row">
                        <div class="col-lg-6" v-for="(faqInfo, infoKey) in faq.info" :key="infoKey" v-html="faqInfo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <loading v-else></loading>
    <!-- FAQ area end-->
</template>

<script>
    export default {
        title: 'FAQ',
        data(){
            return {
                faq: {},
                loading: true,
            }
        },

        methods: {
            // Load FAQ Data
            loadFaq(){
                this.$Progress.start();
                axios.get('/api/faq').then(response=>{
                    this.faq = JSON.parse(response.data.faq);
                    this.loading = false;
                    this.$Progress.finish();
                },
                ()=>{
                    this.$Progress.fail();
                })
            }
        },

        created(){
            this.loadFaq();
        }
    }
</script>