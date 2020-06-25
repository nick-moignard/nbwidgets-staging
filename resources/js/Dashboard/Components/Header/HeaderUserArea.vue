<template>
  <div class="d-flex">
    <div class="header-btn-lg pr-0">
      <div class="widget-content p-0">
        <div class="widget-content-wrapper">
          <div class="widget-content-left ml-3 header-user-info">
            <div class="widget-heading">{{currentUser.user.name}}</div>
            <div class="widget-subheading">{{currentUser.user.email}}</div>
          </div>
          <div class="widget-content-right header-user-info ml-3">
            <button type="button" class="btn btn-icon btn-icon-only btn-outline-link" @click.prevent="toggleMobile2_logout('header-menu-open')">
              <font-awesome-icon icon="sign-out-alt" size="2x" color="#FFFFFF" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleDown,
  faCalendarAlt,
  faTrashAlt,
  faCheck,
  faFileAlt,
  faCloudDownloadAlt,
  faFileExcel,
  faFilePdf,
  faFileArchive,
  faEllipsisH,
  faUser,
  faSignOutAlt
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(
  faUser,
  faAngleDown,
  faCalendarAlt,
  faTrashAlt,
  faCheck,
  faFileAlt,
  faCloudDownloadAlt,
  faFileExcel,
  faFilePdf,
  faFileArchive,
  faEllipsisH,
  faSignOutAlt
);

export default {
  components: {
    "font-awesome-icon": FontAwesomeIcon,
  },
  data: () => ({
    fill1: { gradient: ["#00b09b", "#96c93d"] },
    fill2: { gradient: ["#ff0844", "#ffb199"] },
    fill3: { gradient: ["#f6d365", "#fda085"] },
    showDrawerSection: false
  }),
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    /*logOut() {
      this.$store.dispatch("auth/logout");
      this.$router.push("/login");
    },*/
    toggleMobile2_logout(className) {
      const el = document.body;
      this.isOpenMobileMenu = this.isOpenMobileMenu;

      if (this.isOpenMobileMenu) {
          el.classList.add(className);
      } else {
          el.classList.remove(className);
      }
      this.$store.dispatch("auth/logout");
      this.$router.push("/login");
    },
  }
};
</script>
