<template>
  <div id="app">
    <component :is="layout" v-if="currentUser">
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </component>
    <router-view v-else></router-view>
  </div>
</template>

<script>

export default {
  created() {
    if (!this.$store.state.auth.user) {
      this.$router.push("/login");
    }
  },
  computed: {
    layout() {
      return "default-layout";
    },
    currentUser() {
      return this.$store.state.auth.user;
    }
  }
};
</script>