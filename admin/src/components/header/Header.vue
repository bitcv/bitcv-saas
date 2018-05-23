<template>
  <div class="header container">
    <div class="logo">
      <a href="/">
        <img src="/static/img/logo.png" alt="">
      </a>
    </div>
    <div class="info">
      <div class="account"><span>{{ mobile }}</span></div>
      <div class="signout" @click="signout"><span>退出登录</span></div>
    </div>
  </div>
</template>

<script>
import {mapMutations} from 'vuex'
export default {
  data () {
    return {
      mobile: ''
    }
  },
  mounted () {
    this.mobile = localStorage.getItem('mobile')
  },
  methods: {
    /* signout () {
      this.$http.post('/api/signout', {}).then((res) => {
        if (res.data.errcode === 0) {
          localStorage.removeItem('userId')
          localStorage.removeItem('mobile')
          localStorage.removeItem('avatarUrl')
          window.location.href = '/'
        }
      })
    } */
    ...mapMutations(['cleanUserInfo']),
    signout () {
      this.$http.post('/api/doSignout', {}).then((res) => {
        if (res.data.errcode === 0) {
          this.cleanUserInfo()
          this.$router.push('/admin/signin')
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
      cursor: pointer;
      align-items: center;
    }
  }
}
</style>
