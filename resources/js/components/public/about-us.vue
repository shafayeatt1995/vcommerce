<template>
    <!--About us area start-->
    <div class="about" v-if="!loading">
        <div class="container">
            <div class="about-us">
                <div class="section-title">
                    <h3><i class="fas fa-address-card"></i> About Us</h3>
                </div>
                <hr>
                <div v-html="message"></div>
            </div>
        </div>
        <div class="mt-80"></div>
        <div class="team">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="text-center">{{title}}</h1>
                        <p class="text-center">{{sub_title}}</p>
                        <div class="team-member">
                            <ul>
                                <li v-for="(member, key) in members" :key="key">
                                    <img :src="member.image" :alt="member.name" class="img-fluid">
                                    <h4 class="text-center">{{member.name}}</h4>
                                    <p class="text-center">{{member.designation}}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <loading v-else></loading>
    <!--About us area end-->
</template>

<script>
    export default {
        title: 'About Us',
        data(){
            return {
                title:'',
                sub_title:'',
                message:'',
                members:[],
                loading: true,
            }
        },

        methods: {
            // Load About Us Data
            loadAboutUs(){
                this.$Progress.start();
                axios.get('/api/about').then(response=>{
                    this.title = response.data.about.title;
                    this.sub_title = response.data.about.sub_title;
                    this.message = response.data.about.message;
                    this.members = response.data.members;
                    this.$Progress.finish();
                    this.loading = false;
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    thi.$Progress.fail()

                })
            },
        },

        created(){
            this.loadAboutUs();
        }
    }
</script>