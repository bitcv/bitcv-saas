<template>
  <div class="basic">
    <div class="form-container">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="用户名">
          <el-input v-model="formData.username" placeholder="用户名" required></el-input>
        </el-form-item>
        <el-form-item label="密码">
          <el-input v-model="formData.password" placeholder="密码" required></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="footer-btn-area">
      <el-button type="primary" @click="submit">登录</el-button>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      formData: {
        username: '',
        password: ''
      }
    }
  },
  mounted () {
    this.updateData()
  },
  methods: {
    updateData () {
    },
    submit () {
      this.$http.post('/api/login', this.formData).then((res) => {
        if (res.data.errcode === 0) {
          location.href = '/info'
        } else {
          this.$message({ type: 'error', message: res.data.errmsg })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
