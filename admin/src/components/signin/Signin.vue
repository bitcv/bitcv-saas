<template>
  <div class="signin">
    <el-form class="signin-form">
      <el-form-item>
        <el-input v-model="inputAccount" placeholder="请输入账号"></el-input>
      </el-form-item>
      <el-form-item>
        <el-input v-model="inputPasswd" type="password" placeholder="请输入密码"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="signin" type="primary" class="signin-btn">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {mapMutations} from 'vuex'

export default {
  data () {
    return {
      inputAccount: '',
      inputPasswd: ''
    }
  },
  methods: {
    ...mapMutations(['updateUserInfo']),
    // 新 官网后台跟项目方后台分开
    signin () {
      if (!this.inputAccount) {
        return alert('请输入账号')
      }
      if (!this.inputPasswd) {
        return alert('请输入密码')
      }
      this.$http.post('/api/doSignin', {
        email: this.inputAccount,
        passwd: this.inputPasswd
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.updateUserInfo(res.data.data.userinfo)
          this.$router.push('/admin/setting')
        } else {
          alert(res.data.errmsg)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.signin {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #F5F5F5;
  .signin-form {
    background-color: #FFF;
    padding: 30px;
    box-sizing: border-box;
    width: 400px;
    .signin-btn {
      width: 100%;
    }
  }
}

</style>
