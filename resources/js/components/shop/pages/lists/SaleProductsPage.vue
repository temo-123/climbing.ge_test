<template>
    <div class="col-sm-12">

        <h1 class="page_title">{{ $t('shop.title.sale_products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.shop_short_description"></span>
        </h2>

        <div class="col-md-12" v-if="products_loading">
            <content-loader
                viewBox="0 0"
                primaryColor="#f3f3f3"
                secondaryColor="#27bb7d8c"
            />
        </div>
        <div class="col-sm-12" v-else>
            <!-- <section class="inner"> -->

                <section class="portfolio inner" id="portfolio">

                        <button type="submit" class="btn btn-default btn-send main-btn" @click="open_menu()">Filter products</button>

                        <div class="layout" v-if="filtred_products.length > 0">
                            <!-- <section class="inner"> -->
                                <ul class="grid">

                                    <catalogItem
                                        v-for="product in filtred_products"
                                        :key='product.id'
                                        :product_data="product"
                                    />

                                </ul>
                            <!-- </section> -->
                        </div>
                </section>

                <div  v-if="filtred_products.length == 0">
                    <emptyPageComponent />
                </div>
            <!-- </section> -->

            <productLeftMenu 
                ref="left_menu"

                @sort_by_sale_type="sort_by_sale_type"
                @sort_by_brand="sort_by_brand"
                @sort_by_subcategories="sort_by_subcategories"
                @clear_filtrs="clear_filtrs"
            />
        </div>
        
        <metaData 
            :title = " $t('shop.meta.products') "
            :description = "'Climbing products. All products made in Georgia.'"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import catalogItem from '../../items/cards/CatalogItemComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery
    import metaData from '../../items/MetaDataComponent'
    import productLeftMenu from '../../items/navbars/LeftMenuComponent'

    export default {
        components: {
            metaData,
            lingallery,
            catalogItem,
            emptyPageComponent,
            ContentLoader,
            productLeftMenu
        },
        data: function () {
            return {
                products: [],
                filtred_products: [],
                
                // min_price: 0,
                // max_price: 999,
                // filter_category: 'All',
                
                // categories: [],
                // products_loading: true,
                // product_modal: false,
                // modalClass: '',
            };
        },
        mounted() {
            this.get_products()
        },
        methods: {
            get_products(){
                axios
                .get('/sale_products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data

                    this.filtred_products = this.products
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },

            open_menu(){
                this.$refs.left_menu.open_menu()
            },
            
            sort_by_sale_type(sale_type){
                if(sale_type == 0){
                    this.get_products()
                }
                else{
                    if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.sale_type == sale_type
                        })
                    }
                    else{
                        this.filtred_products = this.filtred_products.filter(function (item){
                            return item.global_product.sale_type == sale_type
                        })
                    }
                }
            },
            
            sort_by_brand(filter_brand){
                if(filter_brand == 0){
                    this.get_products()
                }
                else{
                    if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.brand_id == filter_brand
                        })
                    }
                    else{
                        this.filtred_products = this.filtred_products.filter(function (item){
                            return item.global_product.brand_id == filter_brand
                        })
                    }
                }
            },

            sort_by_subcategories(subcategory_id){
                if(subcategory_id == 0){
                    this.get_products()
                }
                else{
                    if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.subcategory_id == subcategory_id
                        })
                    }
                    else{
                        this.filtred_products = this.filtred_products.filter(function (item){
                            return item.global_product.subcategory_id == subcategory_id
                        })
                    }
                }
            },

            clear_filtrs(){
                this.get_products()
            },
        }
    }
</script>

<style scoped>
    .model_price_text_size{
        font-size: 2.5em;
    }
    .margin_bottom_top{
        margin-top: -3%;
        margin-bottom: -3%;
    }
    .list_btn{
        border: 1px solid !important;
        border-radius: 3.5px !important;
        border-color: #b3b3b3 !important;
    }

    .range-slider{
        width: 100%;
        /* margin: auto 16px;
        text-align: center;
        position: relative; */
    }

    .range-slider svg, .range-slider input[type = range]{
        position: absolute;
        left: 0;
        right: 0;
    }

    input[type=range]::-webkit-slider-thumb{
        z-index: 2;
        position: relative;
        top: 2px;
        margin-top: -7px;
    }

    .price_range{
        width: 100%
    }

    .price_range_text p{
        /* margin-top: 5%; */
        margin-bottom: 0% !important;
    }

    /* Switch button */
    .btn-default.btn-on.active{
        background-color: #8ee48e;
    }
    .btn-default.btn-off.active{
        background-color: #fc8480;
    }

    .product_style_but_icon{
        font-size: 180%;
    }



    .modal-content {
        margin-top: 50% !important;
    }
</style>