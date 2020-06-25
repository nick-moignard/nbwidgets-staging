<template>
  <div
    :class="sidebarbg"
    class="app-sidebar sidebar-shadow"
    @mouseover="toggleSidebarHover('add','closed-sidebar-open')"
    @mouseleave="toggleSidebarHover('remove','closed-sidebar-open')"
  >
    <div class="app-header__logo">
        <div class="logo-src" @click="$router.push('/nations')">
            <img src="/storage/logo.png" alt="Logo NB Widgets" title="Logo" />
        </div>
      <div class="header__pane ml-auto">
        <button
          type="button"
          class="hamburger close-sidebar-btn hamburger--elastic"
          v-bind:class="{ 'is-active' : isOpen }"
          @click="toggleBodyClass('closed-sidebar')"
        >
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </button>
      </div>
    </div>
    <div class="app-sidebar-content">
      <VuePerfectScrollbar class="app-sidebar-scroll" v-once>
        <sidebar-menu showOneChild :menu="currentUser.user.role=='admin'?menu_admin:menu_user" />
      </VuePerfectScrollbar>
    </div>
  </div>
</template>

<script>
import { SidebarMenu } from "vue-sidebar-menu";
import VuePerfectScrollbar from "vue-perfect-scrollbar";

export default {
  components: {
    SidebarMenu,
    VuePerfectScrollbar
  },
  data:function() {
    return {
      isOpen: false,
      sidebarActive: false,
      menu_user: [
        {
          header: true,
          title: "Main Navigation"
        },
        {
          title: "Nations",
          icon: "pe-7s-network",
          child: [
            {
              href: "/nations",
              title: "View all Nations"
            },
            {
              href: "/nations/create",
              title: "Add New Nation"
            }
          ]
        },
        {
          title: "Logs",
          icon: "pe-7s-rocket",
          child: [
            {
              href: "/logs",
              title: "View all Logs"
            }
          ]
        },
        {
          title: "Settings",
          icon: "pe-7s-note",
          child: [
            {
              href: "/password/edit/"+this.$store.state.auth.user.user.id,
              title: "Change Password"
            }
          ]
        }
      ],
      menu_admin: [
        {
          header: true,
          title: "Main Navigation"
        },
        {
          title: "Nations",
          icon: "pe-7s-network",
          child: [
            {
              href: "/nations",
              title: "View all Nations"
            },
            {
              href: "/nations/create",
              title: "Add New Nation"
            }
          ]
        },
        {
          title: "Users",
          icon: "pe-7s-users",
          child: [
            {
              href: "/users",
              title: "View all Users"
            },
            {
              href: "/users/create",
              title: "Add new User"
            }
          ]
        },
        {
          title: "Logs",
          icon: "pe-7s-search",
          child: [
            {
              href: "/logs",
              title: "View all Logs"
            }
          ]
        },
        {
          title: "Settings",
          icon: "pe-7s-note",
          child: [
            {
              href: "/password/edit/"+this.$store.state.auth.user.user.id,
              title: "Change Password"
            },
            {
              href: "/change/logo/",
              title: "Change Logo"
            }
          ]
        }
      ],
      collapsed: true,

      windowWidth: 0
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  props: {
    sidebarbg: String
  },
  methods: {
    toggleBodyClass(className) {
      const el = document.body;
      this.isOpen = !this.isOpen;

      if (this.isOpen) {
        el.classList.add(className);
      } else {
        el.classList.remove(className);
      }
    },
    toggleSidebarHover(add, className) {
      const el = document.body;
      this.sidebarActive = !this.sidebarActive;

      this.windowWidth = document.documentElement.clientWidth;

      if (this.windowWidth > "992") {
        if (add === "add") {
          el.classList.add(className);
        } else {
          el.classList.remove(className);
        }
      }
    },
    getWindowWidth() {
      const el = document.body;

      this.windowWidth = document.documentElement.clientWidth;

      if (this.windowWidth < "1350") {
        el.classList.add("closed-sidebar", "closed-sidebar-md");
      } else {
        el.classList.remove("closed-sidebar", "closed-sidebar-md");
      }
    }
  },
  mounted() {
    this.$nextTick(function() {
      window.addEventListener("resize", this.getWindowWidth);

      //Init
      this.getWindowWidth();
    });
  },

  beforeDestroy() {
    window.removeEventListener("resize", this.getWindowWidth);
  }
};
</script>
