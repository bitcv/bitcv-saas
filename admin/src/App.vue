<template>
  <div id="app">
    <router-view class="content"/>
  </div>
</template>

<script>
export default {
  name: 'App'
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
    }
  }
}
</style>

<style lang="scss"> @import "./App.scss"; </style>
