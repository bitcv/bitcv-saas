<template>
  <div class="nav container">
    <el-menu default-active="0" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
      <el-menu-item v-for="(item, index) in itemList" :index="index + ''" :key="index">
        <a class="router" :href="item.url">
          <i :class="item.icon"></i>
          <span slot="title">{{ item.text }}</span>
        </a>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: [{
        icon: 'el-icon-menu',
        url: '#/info',
        text: '项目管理'
      }, {
        icon: 'el-icon-info',
        url: '#/profile',
        text: '个人信息'
      }]
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  width: 100%;
  height: 100%;
  .el-menu {
    height: 100%;
    border-right: none;
    .router {
      display: block;
      width: 100%;
      height: 100%;
    }
  }
}
</style>
