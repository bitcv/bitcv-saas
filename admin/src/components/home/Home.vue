<template>
  <div class="home">
    <vue-header class="header"></vue-header>
    <div class="main-container">
      <vue-nav class="menu"></vue-nav>
      <router-view class="content"/>
    </div>
  </div>
</template>

<script>
import vueHeader from '@/components/header/Header.vue'
import vueNav from '@/components/nav/Nav.vue'
export default {
  name: 'App',
  components: {
    vueHeader,
    vueNav
  }
}
</script>

<style lang="scss" scoped>
html {
  height: 100%;
  width: 100%;
  body {
    width: 100%;
    height: 100%;
    #app {
      width: 100%;
      height: 100%;
      .header {
        height: 60px;
      }
      .main-container {
        position: absolute;
        top: 60px;
        bottom: 0;
        width: 100%;
        .menu {
          width: 200px;
          height: 100%;
          float: left;
        }
        .content {
          height: 100%;
          width: auto;
          margin-left: 200px;
          box-sizing: border-box;
          overflow: scroll;
          padding: 20px;
        }
      }
    }
  }
}
</style>
