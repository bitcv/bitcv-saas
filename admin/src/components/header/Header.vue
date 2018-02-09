<template>
  <div class="header container">
    <div class="logo">
      <img src="/static/logo.png" alt="">
    </div>
    <div class="info">
      <div class="account"><span>JunderKing</span></div>
      <div class="signout"><span @click="logout">退出登录</span></div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
    }
  },
  mounted () {
  },
  methods: {
    updateData () {
    },
    logout () {
        this.$http.post('/api/signout').then((res) => {
            if (res.data.errcode === 0) {
              this.$router.push('/login')
            } else {
              this.$message({ type: 'error', message: res.data.errmsg })
            }
          })
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
  background-color: rgb(67, 74, 80);
  .logo {
    box-sizing: border-box;
    height: 100%;
    padding: 10px;
    img {
      box-sizing: border-box;
      height: 100%;
    }
  }
  .info {
    height: 100%;
    display: flex;
    .account {
      display: flex;
      align-items: center;
      padding: 15px;
      color: #FFF;
    }
    .signout {
      padding: 0 20px;
      background-color: #F56C6C;
      color: #FFF;
      height: 100%;
      display: flex;
      align-items: center;
    }
  }
}
</style>
