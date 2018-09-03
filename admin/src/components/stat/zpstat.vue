<template>
    <div class="deposit-box">
        <el-tabs type="border-card">
            <el-tab-pane label="统计列表">
                <el-form :inline="true" label-width="100px">
                    <el-form-item>
                        <div class="block">
                            <span class="demonstration">时间：</span>
                            <el-date-picker v-model="selectday" type="date" placeholder="选择日期" value-format="yyyyMMdd"></el-date-picker>
                        </div>
                    </el-form-item>
                    <el-form-item label="筛选奖品:">
                        <el-select v-model="name" placeholder="请选择奖品">
                            <el-option v-model="all" label="全部">全部</el-option>
                            <el-option label="实物" value="1">实物</el-option>
                            <el-option label="AAC" value="2">AAC</el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="success" @click.prevent="getItemList()">搜索</el-button>
                    </el-form-item>
                </el-form>
                <el-table :data="itemList">
                    <el-table-column type="index"></el-table-column>
                    <el-table-column label="昵称">
                        <template slot-scope="scope">{{ scope.row.nickname }}</template>
                    </el-table-column>
                    <el-table-column label="手机号">
                        <template slot-scope="scope">{{ scope.row.mobile }}</template>
                    </el-table-column>
                    <el-table-column label="奖品名称">
                        <template slot-scope="scope">{{ scope.row.prizeName }}</template>
                    </el-table-column>
                    <el-table-column label="数量">
                        <template slot-scope="scope">{{ scope.row.prizeAmount }}</template>
                    </el-table-column>
                    <el-table-column label="时间">
                        <template slot-scope="scope">{{ scope.row.updateTime }}</template>
                    </el-table-column>
                </el-table>
                <el-pagination v-if="itemList && itemList.length > 0" class="footer-page-box" @size-change="onBoxSizeChange" @current-change="onBoxCurChange" :current-page="pageno" :page-sizes="[10, 20, 30, 40]" :page-size="perpage" layout="total, sizes, prev, pager, next, jumper" :total="dataCount">
                </el-pagination>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
export default {
  data () {
    return {
      itemList: [],
      pageno: 1,
      perpage: 10,
      dataCount: 0,
      selectday: '',
      coin: '',
      name: '',
      all: '',
      symbol: ''
    }
  },
  mounted () {
    this.getPid()
  },
  methods: {
    getPid () {
      this.$http.post('/api/getPid', {
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.symbol = res.data.data.symbol
          this.getItemList({countPages: true})
        }
      })
    },
    getItemList (param) {
      this.$http.post('/api/getZpstaCoin1', {
        pageno: this.pageno,
        perpage: this.perpage,
        date: this.selectday,
        coin: this.symbol,
        isReal: this.name
      }).then((res) => {
        if (res.data.errcode === 0) {
          // 如果需要修改页码，传入countPage
          if (param && param.countPages) {
            this.dataCount = res.data.data.dataCount
          }
          this.itemList = res.data.data.dataList
          console.log(this.itemList)
        }
      })
    },
    onBoxSizeChange (perpage) {
      this.perpage = perpage
      this.getItemList()
    },
    onBoxCurChange (pageno) {
      this.pageno = pageno
      this.getItemList()
    }
  }
}
</script>

<style>
</style>
