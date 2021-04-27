<template>
    <div v-if="!notFound">
        <transition name="fade" mode="out-in">
            <div class="text-center display-block" v-if="loading" key="1">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div key="2" v-else>
                <!-- Single Product info area start-->
                <div class="single-product-info">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-xl-6">
                                <!-- Single Product View area start-->
                                <div class="single-product-view">
                                    <viewer :images="product.product_images" v-if="product.product_images.length > 0">
                                        <carousel :autoplay="false" :nav="true" :dots="false" :items="1" :navText="['〱', '〱']" :loop="true" :autoplayHoverPause="true">
                                            <img v-for="image in product.product_images" :src="'/' + image.image" :key="image.id" class="product-image"/>
                                        </carousel>
                                    </viewer>
                                    <h3 class="text-center" v-else>This product has no image</h3>
                                </div>
                                <!-- Single Product View area end-->
                            </div>
                            <div class="col-lg-7 col-xl-6">
                                <div class="single-product-info">
                                    <h2>{{ product.name }}</h2>
                                    <ul class="single-product-rating">
                                        <li><i class="fa-star" :class="product.rating >= '1' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '2' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '3' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '4' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '5' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li>
                                            <p>({{product.reviews_count}} {{product.reviews_count > 1 ? 'Reviews':'Review'}})</p>
                                        </li>
                                    </ul>
                                    <h3 v-if="product.discount_fixed !== null">
                                        <del><span v-html="icon"></span>{{ product.price * rate | currency }}</del> <span v-html="icon"></span>{{(product.price - product.discount_fixed) * rate | currency }}
                                    </h3>
                                    <h3 v-else><span v-html="icon"></span>{{ product.price * rate | currency}}</h3>
                                    <ul class="list-inside">
                                        <li v-for="data in product.feature_descriptions" :key="data.id">{{ data.feature_description }}</li>
                                    </ul>
                                    <hr/>
                                    <div class="single-product-brand-info">
                                        <ul>
                                            <li>
                                                <p>Sold By: <router-link to="/">{{product.user.name}}</router-link></p>
                                            </li>
                                            <li>
                                                <p>Sold: {{ product.orders.length}} {{product.orders.length > 0 ? 'times':'time'}}</p>
                                            </li>
                                            <li>
                                                <p v-if="product.brand_name == null">Brand: <router-link to="/">{{product.brand.name}}</router-link></p>
                                                <p v-else>Brand: {{ product.brand_name }}</p>
                                            </li>
                                            <li>
                                                <p>Model: {{ product.model_name }}</p>
                                            </li>
                                            <li>
                                                <p class="available">Availability: <span :class="product.stock_status ? 'color-green':'color-red'"> {{ product.stock_status ? 'In Stock':'Out Of Stock' }}</span></p>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--Add to cart area start-->
                                    <div class="single-product-size">
                                        <p>Variant</p>
                                        <ul>
                                            <li v-for="(data, key) in product.variants" :key="data.id">
                                                <input type="radio" :id="'variant' + key" name="variant" :value="data.id" :disabled="!product.stock_status" v-model="cart.variantId" @change="variant(data)"/>
                                                <label :for="'variant' + key">{{data.variant}}</label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-color">
                                        <p>Color</p>
                                        <ul>
                                            <li v-for="(data, key) in product.colors" class="mr-2" :key="data.id">
                                                <input type="radio" :id="'color' + key" name="color" :value="data.id" :disabled="!product.stock_status" v-model="cart.colorId" @change="color(data)"/>
                                                <label :for="'color' + key" :style="'background-color:' + data.color + ';'"></label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-purchase">
                                        <ul>
                                            <li>
                                                <div class="cart-plus-minus">
                                                    <div class="dec qtybutton" @click="cart.quantity > 1 ? cart.quantity-- : ''">-</div>
                                                    <span class="cart-plus-minus-box" type="text" name="qtybutton">{{cart.quantity}}</span>
                                                    <div class="inc qtybutton" @click="cart.quantity++">+</div>
                                                </div>
                                            </li>
                                            <li>
                                                <button type="button" class="cta-grey-btn" @click="carts.some(cart => cart.id === product.id) ? removeCart(carts.findIndex(cart => cart.id === product.id)) : product.stock_status ? addCart(): outOfStock()">Add To Cart <span v-if="carts.some(cart => cart.id === product.id)"><i class="far fa-check-circle"></i></span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="cta-blue-btn" @click="buyNow">Buy Now</button>
                                            </li>
                                            <li>
                                                <wishlist :product="product" activeClass="cta-grey-btn" deactiveClass="cta-grey-btn" activeText="<i class='fas fa-heart'></i>" deactiveText="<i class='far fa-heart'></i>"></wishlist>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--Add to cart area end-->
                                    <div class="single-product-category">
                                        <ul>
                                            <li>
                                                <p>Categories: {{ product.category.name }} <i class="fas fa-arrow-right"></i> {{ product.sub_category.name }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-share">
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
                </div>
                <!-- Single Product info area end-->

                <div class="mt-80"></div>

                <!-- Single Product description area end-->
                <div class="single-product-description">
                    <div class="container">
                        <div class="product-tab-slider">
                            <ul class="nav product-tab-title" id="myTab" role="tablist">
                                <li>
                                    <a data-toggle="tab" href="#description" aria-selected="true" class="active">Description</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#specification" aria-selected="false">Specification</a>
                                </li>
                                <li>
                                    <a @click="Object.keys(reviews).length === 0 ? loadReview():''" data-toggle="tab" href="#reviews" aria-selected="false">Reviews</a>
                                </li>
                                <li>
                                    <a @click="Object.keys(qnas).length === 0 ? getQnas():''" data-toggle="tab" href="#q-a" aria-selected="false">Question</a>
                                </li>
                            </ul>
                            <hr/>
                            <div class="tab-content">
                                <div class="tab-pane fade show active description-tab" id="description">
                                    <h2>Product Full Description</h2>
                                    <div v-html="product.description"></div>
                                </div>
                                <div class="tab-pane fade" id="specification">
                                    <h2>{{ product.name }} Specification</h2>
                                    <table class="table table-bordered table-striped" :class="product.specifications.length > 0 ? '' : 'border-none'">
                                        <tbody v-if="product.specifications.length > 0">
                                        <tr v-for="data in product.specifications" :key="data.id">
                                            <th>{{ data.title }}</th>
                                            <td colspan="3">{{ data.description }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody v-else>
                                        <p>This Product Have No Specifications</p>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="reviews">
                                    <h2>Customer Reviews</h2>
                                    <transition name="fade">
                                        <div key="1" v-if="!reviewLoading">
                                            <div v-if="reviews.data.length > 0">
                                                <div class="review" v-for="review in reviews.data" :key="review.id">
                                                    <div class="review-avatar">
                                                        <img :src="'/'+review.user.image" :alt="review.user.name" class="img-fluid"/>
                                                    </div>
                                                    <div class="review-info">
                                                        <h5 class="text-bold">
                                                            {{ review.user.name }}
                                                            <span class="review-remove ml-2" @click="reviewDelete(review.id)" v-if="$userId == review.user_id"><i class="fas fa-trash-alt"></i></span>
                                                        </h5>
                                                        <h6>{{ review.created_at | date }}</h6>
                                                        <ul class="single-product-rating">
                                                            <li><i class="fa-star" :class="review.rating >= '1' ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="review.rating >= '2' ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="review.rating >= '3' ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="review.rating >= '4' ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="review.rating >= '5' ? 'fas color-yellow' : 'far'"></i></li>
                                                        </ul>
                                                        <p v-if="review.message.length > 220">{{reviewBigMessage == null ? review.message.substring(0, 220) + "..." : review.id == reviewBigMessage ? review.message : review.message.substring(0, 220) + "..."}}
                                                            <span class="pointer ml-2" @click="reviewBigMessageTrigger(review.id)">{{reviewBigMessage == null ? "See More" : review.id == reviewBigMessage ? "...less Message" : "See More"}}</span>
                                                        </p>
                                                        <p v-if="review.message.length < 220">{{ review.message }}</p>
                                                        <div class="review-image" v-if="review.images.length > 0">
                                                            <viewer :images="review.images">
                                                            <div class="row">
                                                                <div class="col-lg-2 col-md-3 col-6 text-center mb-2" v-for="image in review.images" :key="image.id">
                                                                    <img :src="'/'+image.image" class="img-fluid max-height-100 cursor-pointer">
                                                                </div>
                                                            </div>
                                                            </viewer>
                                                        </div>
                                                        <button class="review-replay" @click="reviewReplyForm(review.id)" v-if="$userId == product.user_id || $userId == review.user_id"><i class="fas fa-reply"></i> Replay</button>

                                                        <transition name="slide">
                                                            <form @submit.prevent="reviewReplayPost" class="review-form mb-0" v-if="reviewReplay.review_id == review.id">
                                                                <div class="form-group">
                                                                    <input v-model="reviewReplay.message" type="text" class="form-control" :class="{'is-invalid': form.errors.has('message')}"/>
                                                                    <has-error :form="reviewReplay" field="message"></has-error>
                                                                    <button type="button" @click="reviewReplyForm(review.id)" class="cta-red-btn p-2 mt-2">close</button>
                                                                    <button type="submit" class="cta-blue-btn p-2 ml-2 mt-2">Submit</button>
                                                                </div>
                                                            </form>
                                                        </transition>
                                                        <div class="review-replay" v-for="reviewReplay in review.review_replays" :key="reviewReplay.id">
                                                            <div class="review-avatar">
                                                                <img :src="'/' + reviewReplay.user.image" alt="avatar" class="img-fluid height-50"/>
                                                            </div>
                                                            <div class="review-info">
                                                                <h5 class="text-bold">
                                                                    {{ reviewReplay.user.name }}<span class="qna-remove ml-2" v-if="$userId == reviewReplay.user_id" @click="reviewReplayDelete(reviewReplay.id)"><i class="fas fa-trash-alt"></i></span>
                                                                </h5>
                                                                <h6>{{ reviewReplay.created_at | date }}</h6>
                                                                <p v-if="reviewReplay.message.length > 220">
                                                                    {{reviewReplayBigMessage == null ? reviewReplay.message.substring(0, 220) + "..." : reviewReplay.id == reviewReplayBigMessage ? reviewReplay.message : reviewReplay.message.substring(0, 220) + "..."}}
                                                                    <span class="pointer ml-2" @click="reviewReplayBigMessageTrigger(reviewReplay.id)">{{reviewReplayBigMessage == null ? "See More" : reviewReplay.id == reviewReplayBigMessage ? "...less Message" : "See More"}}</span>
                                                                </p>
                                                                <p v-if="reviewReplay.message.length < 220">{{ reviewReplay.message }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                </div>
                                                <pagination :data="reviews" @pagination-change-page="getReviews"></pagination>
                                            </div>
                                            <div v-else>
                                                <empty message="This Product Have No Review"></empty>
                                            </div>
                                            <div class="mt-80"></div>
                                            <div class="row" v-if="$middleware.authCheck()">
                                                <div class="col-lg-4">
                                                    <div class="review-count">
                                                        <h1 class="color-blue">{{reviews.data.length > 0 ? reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) : '0.00' | currency}}</h1>
                                                        <ul class="single-product-rating">
                                                            <li><i class="fa-star" :class="reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) >= 1 ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) >= 1.5 ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) >= 2.5 ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) >= 3.5 ? 'fas color-yellow' : 'far'"></i></li>
                                                            <li><i class="fa-star" :class="reviews.data.reduce((total, rating)=>{return total + (rating.rating/reviews.data.length)},0) >= 4.5 ? 'fas color-yellow' : 'far'"></i></li>
                                                        </ul>
                                                        <h6>{{reviews.data.length}} Review{{reviews.data.length > 1 ? 's':''}}</h6>
                                                        <div class="review-bar">
                                                            <ul>
                                                                <li>
                                                                    <div class="review-bar-left">5 Star</div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-blue"  role="progressbar"  :style="'width: ' + reviews.data.filter(rating=>{return rating.rating === 5}).length*100/reviews.data.length + '%'"></div>
                                                                    </div>
                                                                    <div class="review-bar-right">
                                                                        {{reviews.data.filter(rating=>{return rating.rating === 5}).length}} Review{{reviews.data.filter(rating=>{return rating.rating === 5}).length > 1 ? 's':''}}</div>
                                                                </li>
                                                                <li>
                                                                    <div class="review-bar-left">4 Star</div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-blue" role="progressbar" :style="'width: ' + reviews.data.filter(rating=>{return rating.rating === 4}).length*100/reviews.data.length + '%'"></div>
                                                                    </div>
                                                                    <div class="review-bar-right">{{reviews.data.filter(rating=>{return rating.rating === 4}).length}} Review{{reviews.data.filter(rating=>{return rating.rating === 4}).length > 1 ? 's':''}}</div>
                                                                </li>
                                                                <li>
                                                                    <div class="review-bar-left">3 Star</div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-blue" role="progressbar" :style="'width: ' + reviews.data.filter(rating=>{return rating.rating === 3}).length*100/reviews.data.length + '%'"></div>
                                                                    </div>
                                                                    <div class="review-bar-right">{{reviews.data.filter(rating=>{return rating.rating === 3}).length}} Review{{reviews.data.filter(rating=>{return rating.rating === 3}).length > 1 ? 's':''}}</div>
                                                                </li>
                                                                <li>
                                                                    <div class="review-bar-left">2 Star</div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-blue" role="progressbar" :style="'width: ' + reviews.data.filter(rating=>{return rating.rating === 2}).length*100/reviews.data.length + '%'"></div>
                                                                    </div>
                                                                    <div class="review-bar-right">{{reviews.data.filter(rating=>{return rating.rating === 2}).length}} Review{{reviews.data.filter(rating=>{return rating.rating === 2}).length > 1 ? 's':''}}</div>
                                                                </li>
                                                                <li>
                                                                    <div class="review-bar-left">1 Star</div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-blue" role="progressbar" :style="'width: ' + reviews.data.filter(rating=>{return rating.rating === 1}).length*100/reviews.data.length + '%'"></div>
                                                                    </div>
                                                                    <div class="review-bar-right">{{reviews.data.filter(rating=>{return rating.rating === 1}).length}} Review{{reviews.data.filter(rating=>{return rating.rating === 1}).length > 1 ? 's':''}}</div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="review-post">
                                                        <h2>Post Your review</h2>
                                                        <form @submit.prevent="reviewPost">
                                                            <div class="row">
                                                                <div class="form-group col-lg-4">
                                                                    <label for="review-stars">Review Stars</label>
                                                                    <select id="review-stars" class="form-select form-control" v-model="review.rating" :class="{'is-invalid': form.errors.has('rating')}" style="background-image: url('/images/download.svg')" required>
                                                                        <option value="">Select a Rating</option>
                                                                        <option value="1">1 Stars Rating</option>
                                                                        <option value="2">2 Stars Rating</option>
                                                                        <option value="3">3 Stars Rating</option>
                                                                        <option value="4">4 Stars Rating</option>
                                                                        <option value="5">5 Stars Rating</option>
                                                                    </select>
                                                                    <has-error :form="review" field="rating"></has-error>
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label for="review-text">Type Your Review</label>
                                                                    <textarea class="form-control form-fix" id="review-text" rows="5" v-model="review.message" :class="{'is-invalid': form.errors.has('message')}" required></textarea>
                                                                    <has-error :form="review" field="message"></has-error>
                                                                </div>
                                                                <transition name="slide">
                                                                <div class="review-sample-image display-block pl-3 pr-3" v-if="review.images.length > 0">
                                                                    <div class="row">
                                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 text-center" v-for="(image, key) in review.images">
                                                                            <img :src="image" class="img-fluid max-height-100">
                                                                            <span class="cursor-pointer" @click="removeImage(key)"><i class="fas fa-times"></i></span>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-5 text-center" >
                                                                            <label for="review-image" :class="review.images.length < 5 ? 'cursor-pointer':''"><i class="fas" :class="review.images.length < 5 ? 'fa-plus':'fa-check'"></i></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </transition>
                                                                <div class="review-submit">
                                                                    <button type="submit" class="cta-btn-blue p-1 pl-5 pr-5" v-if="reviewPostLoading">
                                                                        <span class="spinner-border" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </span>
                                                                    </button>
                                                                    <button type="submit" class="cta-blue-btn pt-2 pb-2 mb-2" v-else>Submit</button>
                                                                    <label for="review-image" class="cta-blue-btn cursor-pointer p-2 pl-3 pr-3 ml-2" v-if="review.images.length < 1">Select an image</label>
                                                                    <input :type="review.images.length < 5 ? 'file':'hidden'" id="review-image" class="display-none" accept="image/*" @change="reviewImage($event)" multiple>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" v-if="!$middleware.authCheck()">
                                                <div class="col-lg-12">
                                                <h4 class="text-center">Please <span><router-link to="/login">Login</router-link></span> For Submit Your Review</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else key="2">
                                            <loading></loading>
                                        </div>
                                    </transition>
                                </div>
                                <div class="tab-pane fade" id="q-a">
                                    <h2>Questions and Answers</h2>
                                    <transition name="fade">
                                        <div key="2" v-if="!qnaLoading">
                                            <div v-if="qnas.data.length > 0">
                                                <div class="review" v-for="qna in qnas.data" :key="qna.id">
                                                    <div class="review-avatar">
                                                        <img :src="'/' + qna.user.image" :alt="qna.user.name" class="img-fluid height-50"/>
                                                    </div>
                                                    <div class="review-info">
                                                        <h5 class="text-bold">
                                                            {{ qna.user.name }}
                                                            <span class="qna-remove ml-2" @click="qnaDelete(qna.id)" v-if="$userId == qna.user_id"><i class="fas fa-trash-alt"></i></span>
                                                        </h5>
                                                        <h6>{{ qna.created_at | date }}</h6>
                                                        <p v-if="qna.message.length > 220">{{qnaBigMessage == null ? qna.message.substring(0, 220) + "..." : qna.id == qnaBigMessage ? qna.message : qna.message.substring(0, 220) + "..."}}
                                                            <span class="pointer ml-2" @click="qnaBigMessageTrigger(qna.id)">{{qnaBigMessage == null ? "See More" : qna.id == qnaBigMessage ? "...less Message" : "See More"}}</span>
                                                        </p>
                                                        <p v-if="qna.message.length < 220">{{ qna.message }}</p>
                                                        <button class="review-replay" @click="qna_replay(qna.id)" v-if="$userId == product.user_id"><i class="fas fa-reply"></i> Replay</button>
                                                        <transition name="slide">
                                                            <form @submit.prevent="qnaReplayPost" class="qna-form" v-if="qnaId == qna.id">
                                                                <div class="form-group">
                                                                    <input v-model="qnaReplay.message" type="text" class="form-control"/>
                                                                    <has-error :form="qnaReplay" field="message"></has-error>
                                                                    <button type="button" @click="qnaReplayClose" class="cta-red-btn p-2 mt-2">close</button>
                                                                    <button type="submit" class="cta-blue-btn p-2 ml-2 mt-2">Submit</button>
                                                                </div>
                                                            </form>
                                                        </transition>
                                                        <div class="review-replay" v-for="qnaReplayData in qna.qna_replys" :key="qnaReplayData.id">
                                                            <div class="review-avatar">
                                                                <img :src="'/' + qnaReplayData.user.image" alt="avatar" class="img-fluid height-50"/>
                                                            </div>
                                                            <div class="review-info">
                                                                <h5 class="text-bold">
                                                                    {{ qnaReplayData.user.name }}<span class="qna-remove ml-2" v-if="$userId ==qnaReplayData.user_id" @click="qnaReplayDelete(qnaReplayData.id)"><i class="fas fa-trash-alt"></i></span>
                                                                </h5>
                                                                <h6>{{ qnaReplayData.created_at | date }}</h6>
                                                                <p v-if="qnaReplayData.message.length > 220">
                                                                    {{qnaReplayBigMessage == null ? qnaReplayData.message.substring(0, 220) + "..." : qnaReplayData.id == qnaReplayBigMessage ? qnaReplayData.message : qnaReplayData.message.substring(0, 220) + "..."}}
                                                                    <span class="pointer ml-2" @click="qnaReplayBigMessageTrigger(qnaReplayData.id)">{{qnaReplayBigMessage == null ? "See More" : qnaReplayData.id == qnaReplayBigMessage ? "...less Message" : "See More"}}</span>
                                                                </p>
                                                                <p v-if="qnaReplayData.message.length < 220">{{ qnaReplayData.message }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                </div>
                                                <div class="pagination">
                                                    <pagination :data="qnas" @pagination-change-page="getResults"></pagination>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <empty message="This Product Have No Question"></empty>
                                            </div>

                                            <div class="mt-80"></div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="review-post" v-if="$middleware.authCheck()">
                                                        <h2>Post Your Question</h2>
                                                        <form @submit.prevent="postQna">
                                                            <div class="row">
                                                                <div class="form-group col-lg-12">
                                                                    <label for="qna-text">Type Your Question</label>
                                                                    <textarea class="form-control form-fix" id="qna-text" rows="5" v-model="form.message" :class="{'is-invalid': form.errors.has('message')}"></textarea>
                                                                    <has-error :form="form" field="message"></has-error>
                                                                </div>
                                                                <div class="review-submit">
                                                                    <button type="submit" v-if="!qnaPostLoading">Submit</button>
                                                                    <button type="button" class="qna-btn-fix" v-else>
                                                                        <span class="spinner-border" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div v-else>
                                                        <h4 class="text-center">Please <span><router-link to="/login">Login</router-link></span> For Submit Your Question</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else key="1">
                                            <loading></loading>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </div>

                        <div class="mt-80"></div>

                        <!-- Related Product area start-->
                        <div class="section-title">
                            <h3><i class="fas fa-book-reader"></i> Related Product</h3>
                        </div>
                        <hr/>
                        <div class="single-product-related-item">
                            <carousel :autoplay="false" :nav="true" :dots="false" :items="5" :navText="['〱', '〱']" :loop="true" :margin="10" :responsive="{0: { items: 2 },768: { items: 3 },992: { items: 4 },1200: { items: 5 },}" :autoplayHoverPause="true">
                                <div v-for="data in products" :key="data.id">
                                    <product :product="data"></product>
                                </div>
                            </carousel>
                        </div>
                        <!-- Related Product area end-->
                    </div>
                </div>
                <!-- Single Product description area start-->
            </div>
        </transition>
    </div>
    <div v-else>
        <error></error>
    </div>
</template>


<script>
    import carousel from 'vue-owl-carousel';
    export default {
        title: 'Product',
        components: {carousel},
        data() {
            return {
                cart: {
                    product: {},
                    quantity: '1',
                    colorId: '',
                    colorName: '',
                    colorCode: '',
                    variantId: '',
                    variantName: '',
                },
                form: new Form({
                    product_id: '',
                    message: '',
                }),
                qnaReplay: new Form({
                    qna_id: '',
                    message: '',
                }),
                review: new Form({
                    product_id: '',
                    rating: '',
                    message: '',
                    images: [],
                }),
                reviewReplay: new Form({
                    review_id: '',
                    rating: '',
                    message: '',
                }),
                product: {},
                products: [],
                qnas: {},
                reviews: {},
                reviewReplays: [],
                loading: true,
                qnaId: null,
                qnaLoading: true,
                qnaPostLoading: false,
                notFound: false,
                qnaBigMessage: null,
                qnaReplayBigMessage: null,
                reviewLoading: true,
                reviewPostLoading: false,
                reviewBigMessage: null,
                reviewReplayBigMessage: null,
            };
        },

        methods: {
            // Load Product Information
            loadProduct() {
                this.$Progress.start();
                axios.get('/api/' + this.$route.params.slug + '/product').then(response => {
                    this.product = response.data.product;
                    this.products = response.data.products;
                    this.review.product_id = response.data.product.id;
                    this.cart.product = response.data.product;
                    this.cart.colorId = response.data.product.colors[0].id;
                    this.cart.colorName = response.data.product.colors[0].name;
                    this.cart.colorCode = response.data.product.colors[0].color;
                    this.cart.variantId = response.data.product.variants[0].id;
                    this.cart.variantName = response.data.product.variants[0].variant;
                    this.loading = false;
                    this.$Progress.finish();
                },
                () => {
                    this.notFound = true;
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                }
                );
            },

            // Product View Count
            viewCount(){
                setTimeout(()=>{
                    if(localStorage.getItem(btoa(this.product.id+'product')) === null) {
                        localStorage.setItem(btoa(this.product.id+'product'), btoa(this.product.id+'product'));
                        axios.post('/api/product/view-count/'+this.product.id);
                    }
                }, 15000);
            },

            // Load Question And Answer Data
            getQnas() {
                axios.get('/api/product/' + this.product.id + '/qna').then(response => {
                    this.qnas = response.data.qnas;
                    this.form.product_id = this.product.id;
                    this.qnaPostLoading = false;
                    this.qnaLoading = false;
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                });
            },
            getResults(page = 1) {
                this.$Progress.start();
                axios.get('/api/product/' + this.product.id + '/qna?page=' + page).then(
                    (response) => {
                        this.qnas = response.data;
                        this.$Progress.finish();
                    });
            },

            // Submit New Question
            postQna() {
                this.qnaPostLoading = true;
                this.form.post('/api/product/qna').then(() => {
                    this.form.reset();
                    this.qnaPostLoading = false;
                    Fire.$emit('getQna');
                    Fire.$emit('success', 'Question Posted Successfully.');
                },
                error => {
                    this.qnaPostLoading = false;
                    Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'You Can not Ask More than 5 question');
                });
            },

            // Delete Question
            qnaDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$Progress.start();
                        axios.post('/api/product/' + id + '/qna').then(() => {
                            Fire.$emit('getQna');
                            Swal.fire("Deleted!", "Your Question has been deleted.", "success");
                            this.$Progress.finish();
                        },
                        () => {
                            this.$Progress.fail();
                        });
                    }
                });
            },

            // Show QNA Replay form
            qna_replay(event) {
                this.qnaId = event;
                this.qnaReplay.qna_id = event;
            },

            // Hide QNA Replay Form
            qnaReplayClose() {
                this.qnaId = null;
                this.qnaReplay.reset();
            },

            // Submit QNA Replay Information
            qnaReplayPost() {
                this.$Progress.start();
                this.qnaReplay.post('/api/product/qna/replay').then(() => {
                    this.qnaId = null;
                    this.qnaReplay.reset();
                    Fire.$emit('success', 'Replay Successfully Posted');
                    Fire.$emit('getQna');
                });
            },

            // Delete QNA Replay Information
            qnaReplayDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this Q & A",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$Progress.start();
                        axios.post('/api/product/' + id + '/qna-replay').then(() => {
                            Fire.$emit('getQna');
                            Swal.fire(
                                "Deleted!",
                                "Your Question Replay Deleted Successfully.",
                                "success"
                            );
                            this.$Progress.finish();
                        },
                        () => {
                            this.$Progress.fail();
                        });
                    }
                });
            },

            // QNA Big Message Show/Minimizer
            qnaBigMessageTrigger(event) {
                this.qnaBigMessage = this.qnaBigMessage === event ? null : event;
            },

            // QNA Replay Big Message Show/Minimizer
            qnaReplayBigMessageTrigger(event) {
                this.qnaReplayBigMessage = this.qnaReplayBigMessage === event ? null : event;
            },

            // Load Product Review
            loadReview(){
                axios.get('/api/product/' + this.product.id + '/review').then(response => {
                    this.reviews = response.data.reviews;
                    this.reviewReplays = response.data.reviewReplys;
                    this.review.product_id = this.product.id;
                    this.reviewPostLoading = false;
                    this.reviewLoading = false;
                    this.$Progress.finish();
                });
            },
            getReviews(page = 1) {
                this.$Progress.start();
                axios.get('/api/product/' + this.product.id + '/review?page=' + page)
                    .then(response => {
                        this.reviews = response.data.reviews;
                        this.$Progress.finish();
                    });
            },

            // Post New Review Information
            reviewPost(){
                this.$Progress.start();
                this.reviewPostLoading = true;
                this.review.post('/api/product/review').then(()=>{
                    Fire.$emit('getReview');
                    this.review.reset();
                    this.reviewPostLoading = false;
                    Fire.$emit('success', 'Review Successfully Posted');
                    this.$Progress.finish();
                },
                error=>{
                    this.reviewPostLoading = false;
                    Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:error.response.data.error);
                    this.$Progress.fail();
                });
            },

            // Show/Hide Review Replay Form
            reviewReplyForm(event){
                this.reviewReplay.review_id === event ? this.reviewReplay.review_id = '': this.reviewReplay.review_id = event;
            },

            // Submit New Review Replay Information
            reviewReplayPost(){
                this.$Progress.start();
                this.reviewReplay.post('/api/product/review/replay').then(() => {
                    Fire.$emit('getReview');
                    this.reviewReplay.reset();
                    Fire.$emit('success', 'Replay Successfully Posted');
                    this.$Progress.finish();
                },
                ()=>{
                    this.$Progress.fail();
                });
            },

            // Delete Review Information
            reviewDelete(id){
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$Progress.start();
                        axios.post('/api/product/' + id + '/review').then(() => {
                            Fire.$emit('getReview');
                            Swal.fire("Deleted!", "Your Review has been deleted.", "success");
                            this.$Progress.finish();
                        },
                        () => {
                            Fire.$emit('error', 'Something Wrong! Please try Again');
                            this.$Progress.fail();
                        });
                    }
                });
            },

            // Delete Review Replay
            reviewReplayDelete(id){
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3366ee",
                    cancelButtonColor: "#F72748",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$Progress.start();
                        axios.post('/api/product/' + id + '/review-replay').then(() => {
                            Fire.$emit('getReview');
                            Swal.fire("Deleted!", "Your Review Replay has been deleted.", "success");
                            this.$Progress.finish();
                        },
                        () => {
                            Fire.$emit('error', 'Something Wrong! Please try Again');
                            this.$Progress.fail();
                        });
                    }
                });
            },

            // Review Big Message Show/Minimizer
            reviewBigMessageTrigger(event) {
                this.reviewBigMessage = this.reviewBigMessage === event ? null : event;
            },

            // Review Replay Big Message Show/Minimizer
            reviewReplayBigMessageTrigger(event) {
                this.reviewReplayBigMessage = this.reviewReplayBigMessage === event ? null : event;
            },

            // Instant Show Review Image
            reviewImage(event){
                for (let file of Object.entries(event.target.files)) {
                    let reader = new FileReader();
                    reader.onloadend = () => {
                        file[1].size < 2097153 ? this.review.images.length < 5 ? this.review.images.push(reader.result):Fire.$emit('error', 'You Can Upload Maximum 5 Images') : Fire.$emit('error', 'Maximum Image Size 2 MB');
                    };
                    file !== undefined ? reader.readAsDataURL(file[1]) : '';
                }
            },

            // Remove Review Image
            removeImage(key){
                this.review.images.splice(key, 1);
            },

            // Variant Change
            variant(data){
                this.cart.variantName = data.variant;
            },

            // color Change
            color(data){
                this.cart.colorName = data.name;
                this.cart.colorCode = data.color;
            },

            // Product Add To Cart
            addCart(){
                this.$store.dispatch('addCart', this.cart);
                Fire.$emit('success', 'Product Successfully Add To Cart')
            },

            // Product Remove From Cart
            removeCart(key){
                this.$store.dispatch('removeCart', key);
                Fire.$emit('success', 'Product Successfully Remove From Cart');
            },

            // Show Out Of Stock Message
            outOfStock(){
                Fire.$emit('info', 'Product is out of stock')
            },

            // Buy now
            buyNow(){
                let anik = this.carts.some(cart => cart.id === this.cart.product.id);
                anik ? '' : this.$store.dispatch('addCart', this.cart);
                this.$router.push('/cart');
            },
        },

        created() {
            this.loadProduct();
            this.viewCount();

            Fire.$on('getQna', () => {
                this.getQnas();
            });

            Fire.$on('getReview', () => {
                this.loadReview();
            });
        },

        computed: {
            // Load Cart Information
            carts() {
                return this.$store.state.carts;
            },

            // Load Currency Rate
            rate(){
                return this.$store.state.currency.rate;
            },

            // Load Currency Icon
            icon(){
                return this.$store.state.currency.icon;
            },
        },

        watch: {
            // Watch route Changes
            $route(to, from) {
                this.form.reset();
                this.qnaReplay.reset();
                this.review.reset();
                this.reviewReplay.reset();
                this.product = {};
                this.products = [];
                this.qnas = {};
                this.reviews= {};
                this.reviewReplays= [];
                this.loading = true;
                this.qnaId = null;
                this.qnaLoading = true;
                this.qnaPostLoading = false;
                this.notFound = false;
                this.qnaBigMessage = null;
                this.qnaReplayBigMessage = null;
                this.reviewLoading = true;
                this.reviewPostLoading = false;
                this.reviewBigMessage = null;
                this.reviewReplayBigMessage = null;
                this.loadProduct();
                this.viewCount();
            },
        },
    };
</script>

<style scoped>
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .fade-enter-active ,
    .fade-leave-active {
        transition: opacity 0.3s;
    }

    .slide-enter-active,
    .slide-leave-active {
        transition: 0.3s;
    }

    .slide-enter-to, .slide-leave {
        max-height: 300px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }


    .slideLeft-enter-active,
    .slideLeft-leave-active {
        transition: 0.3s;
    }

    .slideLeft-enter-to, .slideLeft-leave {
        min-width: 100%;
        overflow: hidden;
    }

    .slideLeft-enter, .slideLeft-leave-to {
        overflow: hidden;
        max-height: 0;
    }
</style>