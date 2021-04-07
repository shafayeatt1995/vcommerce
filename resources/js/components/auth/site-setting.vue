<template>
    <div class="dashboard" v-if="$middleware.admin()">
        <!--Dashboard area start-->
        <div class="dashboard-container container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="fas fa-tools"></i> Site Setting</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-3">
                    <div class="site-setting-sidebar b-2">
                        <div class="site-setting-menu">
                            <h4 class="cursor-pointer" :class="activeMenu === 'about' ? 'active':''" @click="activeMenu = activeMenu === 'about' ? '':'about'"><i class="fas fa-address-card"></i> About <span class="float-right small"><i class="fas fa-chevron-right transition-2" :class="activeMenu === 'about' ? 'rotate-90':''"></i></span></h4>
                            <transition name="slide">
                                <div v-if="activeMenu === 'about'">
                                    <form @submit.prevent="submitAbout">
                                        <vue-editor v-model="about.message"></vue-editor>
                                        <div class="form-group">
                                            <label for="about-title">About Title <span class="small">(Optional)</span></label>
                                            <input v-model="about.title" id="about-title" class="form-control" :class="{ 'is-invalid': about.errors.has('title') }"/>
                                            <has-error :form="about" field="title"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="about-sub-title">About Sub Title <span class="small">(Optional)</span></label>
                                            <input v-model="about.sub_title" id="about-sub-title" class="form-control" :class="{ 'is-invalid': about.errors.has('sub_title') }"/>
                                            <has-error :form="about" field="sub_title"></has-error>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mb-3">Update About Page</button>
                                        </div>
                                    </form>
                                    <form @submit.prevent="submitMember">
                                        <div class="image-upload">
                                            <label for="member-image">
                                                Select Member Image
                                                <span class="upload-frame">
                                                    <img :src="member.image" class="img-fluid max-height-200 mt-4"/>
                                                </span>
                                            </label>
                                            <input type="file" accept="image/*" id="member-image" name="image" @change="memberImage($event)"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="member-name">Member Name</label>
                                            <input v-model="member.name" id="member-name" class="form-control" :class="{ 'is-invalid': member.errors.has('name') }"/>
                                            <has-error :form="member" field="name"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="member-designation">Member Designation</label>
                                            <input v-model="member.designation" id="member-designation" class="form-control" :class="{ 'is-invalid': member.errors.has('designation') }"/>
                                            <has-error :form="member" field="designation"></has-error>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mb-3">Add New Member</button>
                                        </div>
                                    </form>
                                </div>
                            </transition>
                        </div>
                        <div class="site-setting-menu">
                            <h4 class="cursor-pointer" :class="activeMenu === 'contactUs' ? 'active':''" @click="activeMenu = activeMenu === 'contactUs' ? '':'contactUs'"><i class="fas fa-users"></i> Contact Us <span class="float-right small"><i class="fas fa-chevron-right transition-2" :class="activeMenu === 'contactUs' ? 'rotate-90':''"></i></span></h4>
                            <transition name="slide">
                                <div v-if="activeMenu === 'contactUs'">
                                    <form @submit.prevent="submitContactUs">
                                        <vue-editor v-model="contactUsMessage"></vue-editor>
                                        <div class="form-group">
                                            <label for="contact-us-map">Map</label>
                                            <input v-model="contact.map" id="contact-us-map" class="form-control" :class="{ 'is-invalid': about.errors.has('title') }"/>
                                            <has-error :form="contact" field="map"></has-error>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 mb-3" @click="addInfo">Add Info</button>
                                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mb-3">Update Contact Page</button>
                                        </div>
                                    </form>
                                </div>
                            </transition>
                        </div>
                        <div class="site-setting-menu">
                            <h4 class="cursor-pointer" :class="activeMenu === 'faq' ? 'active':''" @click="activeMenu = activeMenu === 'faq' ? '':'faq'"><i class="fas fa-question"></i> FAQ <span class="float-right small"><i class="fas fa-chevron-right transition-2" :class="activeMenu === 'faq' ? 'rotate-90':''"></i></span></h4>
                            <transition name="slide">
                                <div v-if="activeMenu === 'faq'">
                                    <div class="form-group">
                                        <label for="faq">Faq Section Name</label>
                                        <input v-model="faqSection" id="faq" class="form-control"/>
                                        <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 mt-2" @click="addFaqSection">Create New Faq Section</button>
                                    </div>
                                    <div class="faq-section-title">
                                        <ul>
                                            <li :class="faqSelectSection === faq.title ? 'active':''" v-for="(faq, key) in faq.faq" :key="key" @click="faqSelectSection = faqSelectSection === faq.title ? '':faq.title">{{faq.title}}</li>
                                        </ul>
                                    </div>
                                    <transition name="slide">
                                        <form @submit.prevent="submitFaq" v-if="faqSelectSection !== ''">
                                            <vue-editor v-model="faqInfo"></vue-editor>
                                            <div class="text-center mt-2">
                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 mb-3" @click="addFaqInfo">Add Info</button>
                                                <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mb-3">Update FAQ Page</button>
                                            </div>
                                        </form>
                                    </transition>
                                </div>
                            </transition>
                        </div>
                        <div class="site-setting-menu">
                            <h4 class="cursor-pointer" :class="activeMenu === 'term' ? 'active':''" @click="activeMenu = activeMenu === 'term' ? '':'term'"><i class="fas fa-book"></i> Terms & Condition <span class="float-right small"><i class="fas fa-chevron-right transition-2" :class="activeMenu === 'term' ? 'rotate-90':''"></i></span></h4>
                            <transition name="slide">
                                <div v-if="activeMenu === 'term'">
                                    <form @submit.prevent="submitTerms">
                                        <vue-editor v-model="term.term"></vue-editor>
                                        <div class="text-center mt-2">
                                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mb-3">Update Terms & Condition Page</button>
                                        </div>
                                    </form>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 mt-3 mt-lg-0">
                    <!--Site setting area start-->
                    <transition name="fade" mode="out-in">
                        <div class="about" v-if="activeMenu === 'about'" :key="'1'">
                            <div class="container">
                                <div class=" site-preview">
                                    <div class="section-title">
                                        <h3><i class="fas fa-address-card"></i> About Us</h3>
                                    </div>
                                    <hr>
                                    <div v-html="about.message" class="ql-editor"></div>

                                    <div class="mt-80"></div>

                                    <div class="team">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h1 class="text-center">{{about.title}}</h1>
                                                    <p class="text-center">{{about.sub_title}}</p>
                                                    <div class="team-member">
                                                        <ul>
                                                            <li v-if="member.image || member.name || member.designation  !== ''">
                                                                <img :src="member.image" :alt="member.name" class="img-fluid">
                                                                <h4 class="text-center">{{member.name}}</h4>
                                                                <p class="text-center">{{member.designation}}</p>
                                                                <h6 class="text-center cursor-pointer mt-2" @click="deleteMember('clear')"><i class="fas fa-times"></i></h6>
                                                            </li>
                                                            <li v-for="(aboutMember, key) in aboutMembers" :key="key">
                                                                <img :src="aboutMember.image" :alt="aboutMember.name" class="img-fluid">
                                                                <h4 class="text-center">{{aboutMember.name}}</h4>
                                                                <p class="text-center">{{aboutMember.designation}}</p>
                                                                <h6 class="text-center cursor-pointer mt-2" @click="deleteMember(aboutMember.id)"><i class="fas fa-times"></i></h6>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contact-us" v-else-if="activeMenu === 'contactUs'" :key="'2'">
                            <!-- Contact us area start-->
                            <div class="contact-us">
                                <div class="container">
                                    <div class="section-title">
                                        <h3><i class="fas fa-users"></i> Contact Us</h3>
                                    </div>
                                    <hr>
                                    <div class="mt-40"></div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="contact-address" v-for="(contactInfo, key) in contact.info" :key="key">
                                                <span class="cursor-pointer" @click="removeInfo(key)"><i class="fas fa-times"></i></span>
                                                <div v-html="contactInfo"></div>
                                            </div>
                                            <div class="contact-address">
                                                <div v-html="contactUsMessage"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="contact-us-form">
                                                <h3>Leave us a Message</h3>
                                                <form action="#">
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" class="form-control" id="name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email address</label>
                                                        <input type="email" class="form-control" id="email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subject">Subject</label>
                                                        <input type="text" class="form-control" id="subject">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message">Your Message</label>
                                                        <textarea class="form-control" id="message" rows="4"></textarea>
                                                    </div>
                                                    <button type="button" class="cta-blue-btn">Send</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact us area end-->

                            <!-- Map area start-->
                            <div class="map">
                                <div class="container" v-html="contact.map"></div>
                            </div>
                            <!-- Map area end-->
                        </div>
                        <div class="faq" v-else-if="activeMenu === 'faq'" :key="'3'">
                            <div class="container">
                                <div class="section-title">
                                    <h3><i class="fas fa-question"></i> Frequently Asked Questions</h3>
                                </div>
                                <hr>
                                <div class="faq-body" v-for="(faq, key) in faq.faq" :key="key">
                                    <h4>{{faq.title}} <span class="small cursor-pointer" @click="removeFaqSection(key)"><i class="fas fa-times"></i></span></h4>
                                    <div class="faq-box">
                                        <div class="row">
                                            <div class="col-lg-6" v-for="(faqInfo, infoKey) in faq.info" :key="infoKey">
                                                <span class="small cursor-pointer" @click="removeFaqInfo(faq.title, infoKey)"><i class="fas fa-times"></i></span>
                                                <div v-html="faqInfo"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="terms" v-else-if="activeMenu === 'term'" :key="'4'">
                            <div class="container">
                                <div class="section-title">
                                    <h3><i class="fas fa-book"></i> Terms And Conditions</h3>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-lg-8 offset-lg-2">
                                        <div class="terms-body" v-html="term.term"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center flex-center" :key="'5'" v-else><h2 class="m-0">Select A Page</h2></div>
                    </transition>
                    <!--Site setting area end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end-->
    </div>
    <div class="dashboard" v-else>
        <error></error>
    </div>
</template>

<script>
export default {
    title: 'Site Setting',
    data(){
        return{
            activeMenu: '',
            about: new Form({
                title:'',
                sub_title:'',
                message:'',
            }),
            member: new Form({
                image:'',
                name:'',
                designation:'',
            }),
            contact: new Form({
                info:[],
                map:'',
            }),
            faq: new Form({
                faq:[],
            }),
            term: new Form({
                term:'',
            }),
            aboutMembers:[],
            contactUsMessage:'',
            faqSection:'',
            faqSelectSection:'',
            faqInfo:'',
        }
    },

    methods:{
        // Load Setting Data
        loadSetting(){
            this.$Progress.start();
            axios.get('/api/setting').then(response=>{
                this.about.title = response.data.about.title;
                this.about.sub_title = response.data.about.sub_title;
                this.about.message = response.data.about.message;
                this.aboutMembers = response.data.members;
                this.contact.map = response.data.contact.map;
                this.contact.info = JSON.parse(response.data.contact.info);
                this.faq.faq = JSON.parse(response.data.faq.faq);
                this.term.term = JSON.parse(response.data.term.term);
                this.$Progress.finish()
            },
            ()=>{
                Fire.$emit('error', 'Something Wrong! Please try Again');
                this.$Progress.fail();
            })
        },

        // Submit About Info
        submitAbout(){
            this.$Progress.start();
            this.about.post('/api/about').then(()=>{
                Fire.$emit('success', 'About Info Successfully Updated');
                this.$Progress.finish();
            },
            error=>{
                Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                this.$Progress.fail();
            });
        },

        // Submit Member Info
        submitMember(){
            this.$Progress.start();
            this.member.post('/api/about-member').then(response=>{
                this.member.reset();
                this.aboutMembers = response.data;
                Fire.$emit('success', 'Member Successfully Added');
                this.$Progress.finish();
            },
            error=>{
                Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                this.$Progress.fail();
            });
        },

        // Delete Member
        deleteMember(event){
            this.$Progress.start();
            if(event === 'clear'){
                this.member.reset();
                this.$Progress.finish();
            }else{
                axios.post('/api/about-member-delete/'+event).then(response=>{
                    this.aboutMembers = response.data;
                    Fire.$emit('success', 'Member Successfully Deleted');
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            }
        },

        // Instant Show Image
        memberImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onloadend = () => {
                if (file.size < 2000000) {
                    this.member.image = reader.result;
                } else {
                    Fire.$emit('info', 'Please Use Less Than 2 MB Size Image');
                }
            };
            reader.readAsDataURL(file);
        },

        // Add Info To List
        addInfo(){
            this.contact.info.push(this.contactUsMessage);
            this.contactUsMessage = '';
        },

        // Remove Info To List
        removeInfo(key) {
            this.contact.info.splice(key, 1);
        },

        // Add Contact us page info
        submitContactUs(){
            this.$Progress.start();
            this.contact.post('/api/contact-us').then(()=>{
                Fire.$emit('success', 'Contact Us Details Successfully Updated');
                this.$Progress.finish()
            },
            error=>{
                Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                this.$Progress.fail();
            })
        },

        // Add New Faq Section
        addFaqSection(){
            this.faq.faq.some(faq => faq.title === this.faqSection) ? Fire.$emit('error', 'FAQ Section Title Already Exist'):this.faq.faq.push({'title':this.faqSection, info:[]});
            this.faqSection = '';
        },

        // Remove Faq Section
        removeFaqSection(key){
            this.faq.faq.splice(key, 1);
        },

        // Add Faq Info
        addFaqInfo(){
            this.faq.faq.find(faq=>faq.title === this.faqSelectSection ? faq.info.push(this.faqInfo):'');
            this.faqInfo = '';
        },

        // Remove Faq Info
        removeFaqInfo(data, key){
            this.faq.faq.find(faq=>faq.title === data ? faq.info.splice(key, 1):'');
        },

        // Submit Faq Information
        submitFaq(){
            this.$Progress.start();
            this.faq.post('/api/faq').then(()=>{
                Fire.$emit('success', 'FAQ Information Successfully Updated');
                this.$Progress.finish();
            },
            error=>{
                Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                this.$Progress.fail();
            });
        },

        // Submit Terms & Condition
        submitTerms(){
            this.$Progress.start();
            this.term.post('/api/terms').then(()=>{
                Fire.$emit('success', 'Terms & Condition Page Successfully Updated');
                this.$Progress.finish();
            },
            error=>{
                Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                this.$Progress.fail();
            })
        }
    },

    created() {
        this.$Progress.start();
        if (this.$middleware.admin()) {
            this.loadSetting();
            this.$Progress.finish();
        }else{
            this.$router.push('/login');
            this.$Progress.fail();
        }
    }
}
</script>

<style scoped>
    .slide-enter-active,
    .slide-leave-active {
        transition: 0.5s;
    }

    .slide-enter-to, .slide-leave {
        max-height: 700px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s;
    }
</style>