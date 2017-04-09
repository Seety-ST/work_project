 <template>
        <div  class="go-top-mod"  v-show="show" @click="go_top()">
            <a href="javascript:;"></a>
        </div>
</template>


<style lang="scss">
    .go-top-mod a 
    {
        width: 44px;
        height: 44px;
        display: block;
        position: fixed;
        right: 20px;
        bottom: 95px;
        text-decoration: none;
        color: #fff;
        background: url(../../../image/common/go-top-88x88.png) ;
        width: 44px;
        height:44px;
        background-size: cover;
    }
</style>


<script>

export default {
    name: 'go-top',
    data() {
      return {
        show : false,
        scrolled: 0,
      };
    },
    created: function () {
        window.addEventListener('scroll', this.handleScroll)
    },
    destroyed: function () {
        window.removeEventListener('scroll', this.handleScroll)
    },
    watch : {
        scrolled(val){
            
        }
    },
    methods : {
        go_top : function(){
            document.documentElement.scrollTop = document.body.scrollTop =0;
        },
        handleScroll : function(){
            this.scrolled=document.documentElement.scrollTop || document.body.scrollTop;
            if(this.scrolled>100){
                this.show = true;
            }
            else{
                this.show = false;
            }
          },
    },
    
    
};
</script>
