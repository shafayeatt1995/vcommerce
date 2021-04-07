<template>
    <div>
        <!--Footer area start-->
        <footer>
            <div class="container">
                <hr/>
                <div class="mt-40"></div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="footer-contact">
                            <h3>Contact Us</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
                                in feugiat lorem. Pellentque ac placerat tellus.
                            </p>

                            <ul>
                                <li>
                                    <i class="fas fa-globe-europe"></i> Naogaon, Dhaka, Bangladesh
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i> shafayetalanik@gmail.com
                                </li>
                                <li><i class="fas fa-phone-alt"></i> +88017-28293635</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="footer-link">
                            <h3>Information</h3>
                            <ul>
                                <li>
                                    <router-link to="/about-us"><span></span> About Us</router-link>
                                </li>
                                <li>
                                    <router-link to="/terms-condition"><span></span> Terms & Condition</router-link>
                                </li>
                                <li>
                                    <router-link to="/contact-us"><span></span> Contact Us</router-link>
                                </li>
                                <li>
                                    <a href="#"><span></span> Returns</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="footer-link">
                            <h3>My Account</h3>
                            <ul>
                                <li>
                                    <router-link to="/store"><span></span> Create Store</router-link>
                                </li>
                                <li>
                                    <router-link to="/order-history"><span></span> Order History</router-link>
                                </li>
                                <li>
                                    <router-link to="/wishlist"><span></span> Wish List</router-link>
                                </li>
                                <li>
                                    <a href="#"><span></span> Newsletter</a>
                                </li>
                                <li>
                                    <a href="#"><span></span> Specials</a>
                                </li>
                                <li>
                                    <a href="#"><span></span> Gift Certificates</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-newslatter">
                            <h3>Newsletter</h3>
                            <p>
                                Praesent pellentesque volutpat ex, vitae auctor lorem pulvinar
                                mollis felis at lacinia. Email Address
                            </p>
                            <form action="#" class="footer-subscriber-form">
                                <input type="email"/>
                                <input type="submit" value="Subscribe"/>
                            </form>
                            <div class="footer-social-media">
                                <p>Follow us on social networks</p>
                                <ul>
                                    <li>
                                        <a href="#"><i class="fab fa-facebook bg-blue"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fab fa-twitter bg-ltblue"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fab fa-youtube bg-red"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fab fa-instagram bg-purple"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-40"></div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="footer-bottom-copyright">
                                Copyright © 2020 &amp; All Rights Reserved &amp; Design By
                                <a href="#">Shafayet Al-Anik.</a>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="footer-bottom-img">
                                <img src="#" data-src="" alt="" class="img-fluid lazy"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Footer area end-->

        <!--Back to top area start-->
        <a href="" class="back-to-top"><i class="fas fa-arrow-up"></i></a>
        <!--Back to top area end-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                store: null,
            };
        },
    };
</script>