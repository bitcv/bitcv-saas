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
      <hr v-if="this.isshow">
      <el-form-item label="有效期还剩：" v-if="this.isshow">
        {{ this.day }} 天 {{ this.hr }} 小时 {{ this.min }} 分钟 {{ this.sec }} 秒
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      useremail: '',
      userpwd: '',
      day: 0,
      hr: 0,
      min: 0,
      sec: 0,
      endtime: '',
      isshow: false
    }
  },
  mounted () {
    this.userData()
    this.countdown()
  },
  methods: {
    userData () {
      this.$http.post('/api/getSimpleAuthUser', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.useremail = res.data.data.uinfo.email
          this.endtime = res.data.data.endtime
          this.isshow = res.data.data.isshow
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
    },
    countdown: function () {
      const end = Date.parse(new Date(this.endtime))
      const now = Date.parse(new Date())
      const msec = end - now
      let day = parseInt(msec / 1000 / 60 / 60 / 24)
      let hr = parseInt(msec / 1000 / 60 / 60 % 24)
      let min = parseInt(msec / 1000 / 60 % 60)
      let sec = parseInt(msec / 1000 % 60)
      this.day = day
      this.hr = hr > 9 ? hr : '0' + hr
      this.min = min > 9 ? min : '0' + min
      this.sec = sec > 9 ? sec : '0' + sec
      const that = this
      setTimeout(function () {
        that.countdown()
      }, 1000)
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
