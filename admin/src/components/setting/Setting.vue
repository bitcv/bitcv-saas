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
    <el-dialog class="terms" title="糖包定制技术开发协议" :visible.sync="showDialog" width="80%" :show-close="false" :close-on-click-modal="false" center>
      <p style="text-align: center">本糖包定制技术开发协议（以下简称“本协议”）于2018年【{{ this.month + 1 }} 】月【{{ this.currentDay }}】日由下述主体签订并生效</p>
      <el-form class="personal" label-width="70px" style="margin-top: 15px;">
        <el-form-item label="甲方：">
          <el-input type="text" placeholder="甲方" v-model="other" style="width: 50%;"></el-input>
        </el-form-item>
        <el-form-item label="地址：">
          <el-input type="text" placeholder="地址" v-model="otherAddr" style="width: 50%;"></el-input>
        </el-form-item>
        <el-form-item label="联系人：">
          <el-input type="text" placeholder="联系人" v-model="otherContact" style="width: 50%;"></el-input>
        </el-form-item>
        <el-form-item label="邮箱：">
          <el-input type="text" placeholder="邮箱" v-model="otherEmail" style="width: 50%;"></el-input>
        </el-form-item>
      </el-form>
      <div class="iterms" style="margin: 0 auto;">
        <p>乙方：优码源创（北京）信息技术有限公司   地址：北京市海淀区银科大厦810室&nbsp;&nbsp;邮箱：bitcv@bitcv.com&nbsp;&nbsp;电话：010-57023527</p>
        <span>甲方和乙方以下各称为“一方”，合称为“双方”。</span>
        <p style="font-size: 16px; color: #0a0a0a;">鉴于：</p>
        <p style="margin-top: 10px;">
          1、甲方是一家注册在新加坡的公司，甲方为开展业务，需要委托乙方进行定制技术开发服务（以下简称“本服务”），并支付开发实施经费和报酬，乙方接受委托并进行定制技术开发实施工作。
        </p>
        <p style="margin-top: 10px;">
          2、本合同甲乙双方经过平等协商，在真实、充分地表达各自意愿并做出相互陈述、保证、承诺的基础上，根据《中华人民共和国合同法》及其他相关法律法规的规定，达成如下协议，并由双方共同恪守。
        </p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">1、服务内容和期限：</p>
        <div style="margin-left: 20px">
          <p  style="margin-top: 10px">1.1、服务内容</p>
          <p  style="margin-top: 10px">乙方同意在本协议有效期内为甲方定制开发糖包产品（以下简称“标的系统”），标的系统包含以下功能和内容：</p>
          <p  style="margin-top: 10px">1.1.1、定制宣传图片功能；</p>
          <p  style="margin-top: 10px">1.1.2、定制专属发放链接功能；</p>
          <p  style="margin-top: 10px">1.1.3、提供后台数据查询功能。</p>
          <p  style="margin-top: 10px">1.2、服务期限 </p>
            本服务实施计划周期为收到甲方服务款项后的5个工作日内。<br/>
        </div>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">2、陈述、保证和承诺：</p>
        <p>2.1、甲方的陈述、保证和承诺</p>
        <p>2.1.1、甲方具有完全、独立的法律地位和法律能力签署、交付并履行本协议，可以独立地作为一方诉讼主体。</p>
        <p>2.1.2、甲方在此承诺不使用乙方的服务从事任何违法犯罪活动，包括但不限于以下活动：</p>
        <p style="margin-top: 10px">i.违反宪法所确定的基本原则的；</p>
        <p style="margin-top: 10px">ii.危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p>
        <p style="margin-top: 10px">iii.损害国家荣誉和利益的；</p>
        <p style="margin-top: 10px">iv.煽动民族仇恨、民族歧视，破坏民族团结的；</p>
        <p style="margin-top: 10px">v.破坏国家宗教政策，宣扬邪教和封建迷信的；
        <p style="margin-top: 10px">vi.散布谣言，扰乱社会秩序，破坏社会稳定的；</p>
        <p style="margin-top: 10px">vii.散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</p>
        <p style="margin-top: 10px">viii.侮辱或者诽谤他人，侵害他人合法权益的；</p>
        <p style="margin-top: 10px">ix.传销、集资诈骗、非法吸收公众存款等活动；</p>
        <p style="margin-top: 10px">x.法律、行政法规禁止的其他违法犯罪活动。</p>
        <p style="margin-top: 10px">如乙方在任何时候发现甲方从事前述违法犯罪活动，乙方有权立即单方面终止本协议并终止提供本服务，对于由此带来的责任和损失，乙方不承担任何责任。如因甲方的违法犯罪活动给乙方带来任何损失或不利影响，乙方有权要求甲方进行赔偿。</p>
        <p style="margin-top: 10px">2.1.1、甲方在此保证并承诺，对于甲方利用乙方的定制技术开发服务所从事的任何商业行为，包括但不限于广告宣传、客户激励等行为，均与乙方无关，乙方仅提供标的系统的开发和使用，不负责对甲方的商业目的或行为进行实质判断，对于甲方在商业活动中所产生的任何风险、损失或诉讼，乙方不承担任何责任。如因甲方的商业活动不当给乙方带来任何损失或不利影响，乙方有权要求甲方进行赔偿并消除影响。</p>
        <p style="margin-top: 10px">2.2、乙方的陈述、保证和承诺</p>
        <p style="margin-top: 10px">2.2.1、乙方具有完全、独立的法律地位和法律能力签署、交付并履行本协议，可以独立地作为一方诉讼主体</p>
        <p style="margin-top: 10px">3、乙方的权利和义务</p>
        <p style="margin-top: 10px">3.1、乙方在技术开发的软件正式交付后，讲定期回访用户，并按照本合同的规定提供标的系统售后服务。</p>
        <p style="margin-top: 10px">3.2、未经甲方事先书面许可，就甲方提供的文字及图片等任何形式的资料，乙方不得以任何方式泄露给第三方，亦不得用于本合同以外的任何商业用途。</p>
        <p style="margin-top: 10px">3.3、乙方完成定制技术开发、并按合同约定交付后，乙方无义务对甲方与技术开发相关的载体或信息进行备份或保留。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">4、甲方的权利和义务</p>
        <p style="margin-top: 10px">4.1、甲方须在双方约定的时间内向乙方提供标的系统开发所需要的内容资料（包括但不限于文字、图片等），该等内容资料均须以电子版的形式提供，并保证该等内容资料不违反国家法律、法规，不侵犯任何第三方的权利，否则由此所引起的一切费用，包括但不限于损害赔偿费、诉讼费、律师费和其他费用由甲方独自承担并负责支付，乙方不承担任何责任。</p>
        <p style="margin-top: 10px">4.2、甲方按照工作计划进度，可及时对乙方发出验收申请，组织并完成验收工作</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">5、费用及支付</p>
        <p style="margin-top: 10px">5.1、在本协议有效期内，甲方委托乙方开发标的系统，应向乙方支付开发经费和报酬。开发经费和报酬,经双方协议商定。支付方式可以下列任一方式支付：</p>
        <p style="margin-top: 10px">5.1.1、个ETH（以太坊）；</p>
        <p style="margin-top: 10px">5.1.2、甲方可购买乙方发行的个BCV，购买价格为，前提是，甲方购买的乙方BCV应按乙方要求的方式锁定1年或1年以上，锁定期内，甲方无权以任何方式处置该等BCV。</p>
        <p style="margin-top: 10px">5.2、如支付ETH（以太坊），乙方指定的数字钱包地址如下：0x977545646F1cCfC80e2BfA1Fe95d62BacBe76B56</p>
        <p style="margin-top: 10px">5.3、如以第5.1.2条约定的方式支付，甲方购买BCV的价款支付方式参照第5.2条和第5.3条处理。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">6、合同的变更与补充</p>
        <p style="margin-top: 10px">6.1、本合同的变更必须由双方协商一致，并以书面或邮件形式确定。</p>
        <p style="margin-top: 10px">6.2、开发期间甲方如有对标的系统的需求变更、需求补充、用户体验的完善等版本升级的要求，乙方将在不影响本期标的系统开发质量基础上进行开发服务，双方将协商对服务内容和期限、费用及支付等相关情况进行补充约定。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">7、保密</p>
        <p style="margin-top: 10px">双方约定因履行本合同应遵守的保密义务如下：</p>
        <p style="margin-top: 10px">7.1、双方都有责任对对方提供的技术情报、资料数据、技术信息、经营信息及商业秘密进行保密，不得向第三方泄露前述保密信息的全部或部分内容。</p>
        <p style="margin-top: 10px">7.2、未经对方同意，任何一方不得以任何形式公开合同及其相关附件内容。</p>
        <p style="margin-top: 10px">7.3、双方在未征得对方同意的情况下，不得向第三方泄露在本服务中接触到的需要保密的情报和资料</p>
        <p style="margin-top: 10px">7.4、本条款在本协议结束后和合同终止后继续有效。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">8 交付与验收</p>
        <p style="margin-top: 10px">乙方应当按以下方式向甲方交付项目成果：</p>
        <p style="margin-top: 10px">8.1 “糖包”的交付形式及数量：程序、用户手册一套。</p>
        <p style="margin-top: 10px">8.2 交付时间：甲方支付结算款后五个工作日内交付予甲方。</p>
        <p style="margin-top: 10px">8.3 验收标准：能够实现定制宣传图片功能、定制专属发放链接功能、后台提供数据查询功能。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">9、知识产权的所有权</p>
        <p style="margin-top: 10px">双方约定，本合同标的系统技术开发的全部软件技术成果在全世界的所有版权及一切相关权利归乙方所有，甲方仅有使用权。除非经乙方书面许可，本协议并不赋予甲方享有任何知识产权上的权利。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">10、违约责任</p>
        <p style="margin-top: 10px">10.1、本协议如无特殊约定，任何一方违反、或没有履行其在本协议中的陈述、保证、义务或责任，即构成违约行为</p>
        <p style="margin-top: 10px">10.2、因甲方原因导致甲方迟延付款，造成开发工作停滞、延误的，乙方不承担责任。因甲方原因造成甲方逾期付款，每逾期一日，应向乙方支付应付款总额2‰（千分之二）的违约金，违约金总额累计不超过合同总额的5%（百分之五）。</p>
        <p style="margin-top: 10px">10.3、因乙方原因导致在约定的时间内不能完成合同约定的计划，应提前做出书面解释，经甲方认可，工期做适当顺延；若甲方不能认可的，乙方应承担责任。每逾期一日，应向甲方支付合同总额2‰（千分之二）的违约金，违约金总额累计不超过合同总额的5%（百分之五）。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">11、合同解除</p>
        <p style="margin-top: 10px">11.1、不可抗力</p>
        <p style="margin-top: 10px">若由于地震、台风、洪水、火灾、流行病、战争、罢工以及其他任何无法预见并且是受影响方无法防止亦无法避免的不可抗力事件（以下简称“不可抗力”），而直接致使本协议任何一方不能履行或不能完全履行本协议时，则受上述不可抗力影响的一方不对此不履行或部分履行承担责任。但受影响方须立即毫不迟延地向另外一方发出书面通知，并须在发出该书面通知后15天内向另外一方提供不可抗力事件的说明及证明文件，解释不能履行、部分不能履行或需要迟延履行的原因。</p>
        <p style="margin-top: 10px">11.2、若主张不可抗力的一方未能根据以上规定通知另一方并提供适当证明，其不得免于未能履行其在本协议项下义务的责任。受不可抗力影响的一方应作出合理的努力，以减低该不可抗力造成的后果，并在该不可抗力终止后尽快恢复履行所有有关义务。如受不可抗力影响的一方在因不可抗力而暂免履行义务</p>
        <p style="margin-top: 10px">11.3、不可抗力发生时，双方应立即互相协商，以求达致公平解决方案，并须作出一切合理努力，尽量减低该不可抗力造成的后果。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">12、争议解决</p>
        <p style="margin-top: 10px">在本合同履行过程中如发生争议，双方应当协商解决。双方不愿协商解决或者协商不成的，双方商定，应向合同签订所在地法院起诉</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">13、通知</p>
        <p style="margin-top: 10px">13.1、本协议项下要求或发出的所有通知和其他通信应通过专人递送、挂号邮寄、商业快递服务、传真或电子邮件的方式发到该方下列地址。该等通知视为有效送达的日期按如下方式确定：
        <p style="margin-top: 10px">13.1.1、亲自或以专人递送的，亲自或专人递送当日即视为已实际送达；</p>
        <p style="margin-top: 10px">13.1.2、用信函或快递发出的，则在邮资付讫的挂号信寄出日（邮戳上标明）后的第7天即应视为已实际送达，或在送交国际或者国内承认的快递服务机构后的第五天即应视为已实际送达；</p>
        <p style="margin-top: 10px">13.2、为通知的目的，双方通知地址及联系人信息如下，本协议任何一方可向另一方发出通知改变下述信息。</p>
        <p style="font-size: 16px; color: #0a0a0a; margin-top: 10px;">14、其他</p>
        <p style="margin-top: 10px">本合同一式贰份，双方各执壹份，具有同等法律效力。本合同经双方签字盖章之日起生效。双方权利义务履行完毕后，合同终止。</p>
        <p style="margin-top: 10px">本协议双方已于文首所述日期签署本协议并即生效，以昭信守</p>
      </div>
      <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="agreeItems">同意</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import ElFormItem from 'element-ui/packages/form/src/form-item'

export default {
  components: {ElFormItem},
  data () {
    return {
      useremail: '',
      userpwd: '',
      day: 0,
      hr: 0,
      min: 0,
      sec: 0,
      endtime: '',
      other: '',
      otherAddr: '',
      otherContact: '',
      otherEmail: '',
      isshow: false,
      showDialog: false,
      month: '',
      currentDay: ''
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
          this.showDialog = res.data.data.showItem
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
    agreeItems () {
      if (!this.other || !this.otherAddr || !this.otherContact || !this.otherEmail) {
        return alert('请完善信息')
      }
      this.$http.post('/api/agreeItems', {
        other: this.other,
        otherContact: this.otherContact,
        otherEmail: this.otherEmail,
        otherAddr: this.otherAddr
      }).then((res) => {
        if (res.data.errcode === 0) {
          this.showDialog = false
        }
      })
    },
    countdown: function () {
      const end = Date.parse(new Date(this.endtime))
      const now = Date.parse(new Date())
      var myDate = new Date()
      const msec = end - now
      let day = parseInt(msec / 1000 / 60 / 60 / 24)
      let hr = parseInt(msec / 1000 / 60 / 60 % 24)
      let min = parseInt(msec / 1000 / 60 % 60)
      let sec = parseInt(msec / 1000 % 60)
      this.day = day
      this.month = myDate.getMonth()
      this.currentDay = myDate.getDate()
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
