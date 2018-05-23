<template>
  <div class="deposit-box">
    <el-form class="personal" label-width="120px">
      <el-form-item label="邮箱：">
        <el-input type="text" placeholder="请输入公司邮箱" v-model="useremail" disabled></el-input>
      </el-form-item>
      <el-form-item label="密码：">
        <el-input type="password" placeholder="请输入6～10位密码" v-model="userpwd"></el-input>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" @click="submit">确定</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      useremail: '',
      userpwd: ''
    }
  },
  mounted () {
    this.userData()
  },
  methods: {
    userData () {
      this.$http.post('/api/getSimpleAuthUser', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.useremail = res.data.data.uinfo.email
        }
      })
    },
    submit () {
      var emailReg = RegExp('^([a-zA-Z0-9]+[_|\\_|\\.]?)*[a-zA-Z0-9_]+@([a-zA-Z0-9\\-]+[_|\\_|\\.]?)+[a-zA-Z0-9]+\\.[a-zA-Z]{2,4}$')
      if (!emailReg.test(this.useremail)) {
        return alert('请输入正确的邮箱地址')
      }
      this.updateAuthUser()
    },
    updateAuthUser () {
      this.$http.post('/api/updateAuthUser', {
        uemail: this.useremail,
        passwd: this.userpwd
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.$message({ type: 'success', message: '更新成功' })
          this.userData()
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .personal {
    .el-input {
      width: 60%;
    }
  }
</style>
