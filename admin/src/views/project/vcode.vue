<template>
  <div>
    <el-form label-width="100px">
      <el-form-item label="手机号:">
        <el-input v-model="mobile" placeholder="请输入手机号" style="width:200px;"></el-input>
        <el-button @click.prevent="inspectCode" >查询</el-button>
      </el-form-item>
      <el-form-item label="验证码:">
      <el-input v-model="inputCode" style="width:200px;"></el-input>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
export default {
  data () {
    return {
      mobile: '',
      inputCode: ''
    }
  },
  methods: {
    inspectCode(){
      this.$http.post('/api/inspectCode', {
        mobile: this.mobile
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.inputCode = res.data.data.code
        }
      })  
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
