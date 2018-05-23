<template>
  <div class="nav container">
    <el-collapse v-model="activeName" accordion>
      <el-collapse-item class="zhedie" v-for="(item, index) in itemList" :index="index + ''" :key="index" :title="item.text" :name="index === 0 ? '1' : index + 1 ">
        <el-menu default-active="0" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
          <el-menu-item v-for="(item, index) in item.child" :index="index + ''" :key="index">
            <router-link :to="item.url" class="router">
              <div>
                <i :class="item.icon"></i>
                <span slot="title">{{ item.text }}</span>
              </div>
            </router-link>
          </el-menu-item>
        </el-menu>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: [],
      activeName: '1'
    }
  },
  mounted () {
    this.authUserData()
  },
  methods: {
    // 自己的后台
    authUserData () {
      this.$http.post('/api/getAuthUser', {}).then((res) => {
        var resData = res.data
        if (resData.errcode === 0) {
          if (resData.data.menu.length === 0) {
            this.$message({ type: 'success', message: '请重新登录!' })
            this.$router.push('/admin/signin')
          } else {
            this.$router.push(resData.data.menu[0].url)
            this.itemList = resData.data.menu
          }
        } else {
          this.$router.push('/signin')
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  width: 100%;
  height: 100%;
  background: rgb(84, 92, 100);
  .el-collapse {
      border-top: 0px;
  }
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
<style type="text/css" lang="scss">
  .zhedie {
    .el-collapse-item__header {
      padding-left: 10px;
      color: #fff;
      background: rgb(84, 92, 100);
      border: 0px;
    }
    .el-collapse-item__content {
      padding-bottom: 0px;
    }
    // .el-collapse-item__wrap {
    //   border-bottom: 0px;
    // }
  }
</style>
