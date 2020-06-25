<template>
  <div class="app-container app-theme-white">
    <div class="h-100 bg-plum-plate bg-animation">
        <div class="d-flex vh-100 justify-content-center align-items-center">
            <b-col md="8" class="mx-auto app-login-box">
                <div class="logo-src mx-auto mb-3">
                    <img src="/storage/logo.png" alt="Logo NB Widgets" title="Logo" />
                </div>

                <div class="modal-dialog w-100 mx-auto">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="h5 modal-title text-center">
                                <h4 class="mt-2">
                                  <div>Welcome back,</div>
                                  <span>Please sign in to your account below.</span>
                                </h4>
                            </div>
                            <b-form-group id="exampleInputGroup1"
                                          label-for="exampleEmail"
                                          >
                                <b-form-input type="email"
                                            name="email"
                                            v-model="user.email"
                                            id="exampleEmail"
                                            placeholder="Email...">
                                </b-form-input>
                            </b-form-group>
                            <b-form-group id="exampleInputGroup2"
                                          label-for="examplePassword">
                                <b-form-input v-model="user.password"
                                                type="password"
                                                name="password"
                                                id="examplePassword"
                                                placeholder="Password...">
                                </b-form-input>
                            </b-form-group>
                            <!-- <b-form-checkbox name="check" id="exampleCheck">
                                Keep me logged in
                            </b-form-checkbox>
                            <div class="divider"/>
                            <h6 class="mb-0">
                                No account?
                                <a href="javascript:void(0);" class="text-primary">Sign up now</a>
                            </h6>-->
                        </div>
                        <div class="modal-footer clearfix">
                            <!-- <div class="float-left">
                                <a href="javascript:void(0);" class="btn-lg btn btn-link">Recover
                                    Password</a>
                            </div> -->
                            <div class="float-right">
                                <b-button @click="handleLogin()" variant="primary" size="lg">Login to Dashboard</b-button>
                            </div>
                        </div>
                        <div class="form-group">
                          <div v-if="message" class="alert alert-danger" role="alert">{{message}}</div>
                        </div>
                    </div>
                </div>
                <!-- <div class="text-center text-white opacity-8 mt-3">
                    Copyright &copy; ArchitectUI 2019
                </div> -->
            </b-col>
        </div>
    </div>
  </div>
</template>

<script>
import Slick from "vue-slick";

import User from "../models/user";

export default {
  components: {
    Slick
  },
  data: () => ({
    slickOptions6: {
      dots: true,
      infinite: true,
      speed: 500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      fade: true,
      initialSlide: 0,
      autoplay: true,
      adaptiveHeight: true
    },
    user: new User("", "", ""),
    message: "",
    slide: 0,
    sliding: null
  }),

  methods: {
    handleClick(newTab) {
      this.currentTab = newTab;
    },
    next() {
      this.$refs.slick.next();
    },

    prev() {
      this.$refs.slick.prev();
    },

    reInit() {
      this.$nextTick(() => {
        this.$refs.slick.reSlick();
      });
    },

    onSlideStart(slide) {
      this.sliding = true;
    },
    onSlideEnd(slide) {
      this.sliding = false;
    },
    handleLogin() {
      if (this.user.email && this.user.password) {
        this.$store.dispatch("auth/login", this.user).catch(error => {
          this.loading = false;
          this.message = error.response.statusText
            ? error.response.statusText + ": check your credentials"
            : "Error: check your credentials";
        });
      }
    }
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  mounted() {
    if (this.currentUser) {
      this.$router.push("/nations");
    }
  }
};
</script>
