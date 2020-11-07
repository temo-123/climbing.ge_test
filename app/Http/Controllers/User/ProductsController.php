<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Favorite_product;
use Auth;
use File;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
    	if (view()->exists('user.my_products_list')) {

            $request->user()->authorizeRoles(['manager', 'admin']);

    		$products = product::latest('id')->get();
    		// $products = product::latest('id')->where('user_id', '=', Auth::user()->id)->get();
            $products_count = product::count();
            $favorite_products = Favorite_product::get();
            // dd($products[1]->title);
            $tags = product::get();
            $tags_count = product::count();

            $price_array = array();
            foreach ($products as $product) {
                if ($product->discount != null) {
                    $old_price = $product->price;
                    $sale = $product->discount;
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    $old_price = $product->price;
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>'0', 'new_price'=>$old_price]);
                }
            }            

            $favorite_products_array = array();
            foreach ($favorite_products as $favorite) {
                array_push($favorite_products_array, ['favorite_id'=>$favorite->id, 'favorite_product_id'=> $favorite->product_id, 'user_id'=>$favorite->user_id]);
            }
            // dd($favorite_products_array);
            // foreach ($price_array as $price) {
            //     print_r($price['product_id']);
            //     echo "<hr />";
            // }
            // dd($price_array);
            // dd($products[0]);
            // array_push($products, $price_array);
            // if ($x > 0) {
            //     $new_price = 0;
            // }

    		$data = [
    			'title'=>'Shop',
    			'products'=>$products,
                'products_count'=>$products_count,
                'favorite_products_array'=>$favorite_products_array,
                'tags'=>$tags,
                'tags_count'=>$tags_count,
                'page_name'=>'Products',

                'price_array'=>$price_array,
                'loop_num_var' => 0,

    			'shop'=>1,
                'num' => 1,

                'active' => 'product list',
                // 'table_1_name' => ''
                'table_1_title'=>'1',
    		    'table_1_pablic' => '',
    		    'table_1_name'=> '$table_1_name',
                
                'articles_link' => 'other_page',
                'image_dir' => 'shop_img',
            ];
    		return view('user.my_products_list',$data);
    	}
    	abort(404);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');

            $image_dir = "/images/shop_img";

            $this->product_validate($request);

            $product = new product();

            $product['image_1'] = $this->image_upload($image_dir, "image_1", $request);
            $product['image_2'] = $this->image_upload($image_dir, "image_2", $request);
            $product['image_3'] = $this->image_upload($image_dir, "image_3", $request);
            $product['image_4'] = $this->image_upload($image_dir, "image_4", $request);
            $product['user_id'] = Auth::user()->id;
            $product['url_title'] = $this->get_url_title($request->title);
            $product['title'] = $request->title;
            $product['text'] = $request->text;
            $product['price'] = $request->price;
            $product['currency'] = $request->currency;
            $product['category'] = $request->category;
            
            if ($request->general_image == NULL) {
                $product['general_image'] = 1;
            }

            // $product -> fill($input);
            $product -> save();

            if ($product->save()) {
                return  redirect()->route('products_list') -> with('status', 'Product addid');
            }
        }
        
        if (view() -> exists('user.components.forms.products_form')) {
            $category = $request->category;
            $data=[
                'category' => $category,
                'title' => 'New '.$category,
                'back_btn' => 'home',
                'add_title' => 'Add '.$category,
                'add_active' => 'Add '.$category,
                
                'add_form' => 'articleAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.products_form', $data);
        }
        abort(404);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function edit(Product $product, Request $request)
    {
        $image_dir = 'product_img';
        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            
            $product['image_1'] = '$this->image_update($image_dir, $product, $input, $request)';

            unset($input ['old_image']);
//             $data = $product->getAttributes();
// dd($product -> fill($input));
// dd($product->getAttributes());
            $product -> fill($input);

            // dd($product);

            if ($product->update()) {
                return redirect()->route('products_list')->with('status','product updated!'); //text
            }
            else{
                return redirect()->route('products_list')->with('error','Error! product not updated'); //text
            }
        }
        $old = $product -> toArray();

        if (view()->exists('user.components.forms.products_form')) {
            $data = [
                'title' => 'Edit news - '.$old['title'],
                'data' => $old,

                'edit_form'=>'productEdit',
                'edit_title'=>'test',
                'edit_active'=>'test 2',
                'published' => 1,
                'description' => 1,
                'text' => 1,

                'image' => $image_dir
            ];          
            return view('user.components.forms.products_form', $data);
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function delete(product $product, Request $request)
    {
        if ($request->isMethod('delete')) {
            // delete product file
            $fileName = $product['image'];
            $destinationPath = 'images/shop_img/';
            File::delete($destinationPath.$fileName);

            // delete product from db
            $product ->delete();

            return back()->with('good_status', 'product delited!'); //text
        }
        else{
            return back()->with('bed_status', 'Error! product not deleted!'); //text
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function favorite()
    {
        if (view()->exists('user.favorite_products')) {

            $favorite_products = Favorite_product::where('user_id', '=', Auth::user()->id)->get();
    		$products = product::get();
            
            $my_favorite_products = array();

            foreach ($favorite_products as $favorite_product) {
                foreach ($products as $product) {
                    // dd($favorite_product->id);
                    if ($product->id == $favorite_product->product_id) {
                        // print_r($product->id);
                        array_push($my_favorite_products, [product::where('id', '=', $product->id)->get()]);
                    }
                }
            }
            // $products = product::latest('id')->where('user_id', '=', Auth::user()->id)->get();
            // $products_count = product::count();
            // $favorite_products = Favorite_product::get();
            // // dd($products[1]->title);
            // $tags = product::get();
            // $tags_count = product::count();
            // $price_array = array();
            // foreach ($products as $product) {
            //     if ($product->discount != null) {
            //         $old_price = $product->price;
            //         $sale = $product->discount;
            //         $price_x_sale = $sale * $old_price;
            //         $var_1 = $price_x_sale/100;
            //         $new_price = $old_price - $var_1;
            //         // dd($products[0]);
            //         array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
            //     }
            //     else{
            //         $old_price = $product->price;
            //         array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>'0', 'new_price'=>$old_price]);
            //     }
            // }            

            // $favorite_products_array = array();
            // foreach ($favorite_products as $favorite) {
            //     array_push($favorite_products_array, ['favorite_id'=>$favorite->id, 'favorite_product_id'=> $favorite->product_id, 'user_id'=>$favorite->user_id]);
            // }

    		$data = [
    			'title'=>'My favorite products',
                'page_name'=>'favorite_products',
    			'my_favorite_products' => $my_favorite_products
    		];
    		return view('user.favorite_products',$data);
    	}
    	abort(404);
    }









    private function product_validate($request)
    {
        $request->validate([
            'title' => 'required|max:25',
        ]);
    }

    public function image_upload($image_dir, $file_upload_name, $request)
    {
        if ($request->hasFile($file_upload_name)) {
                
            $file = $request -> file($file_upload_name);

            $input[$file_upload_name] = $file -> getClientOriginalName();

            // rename file
            $pieces = explode( '.', $input[$file_upload_name] );
            $fruit = array_pop($pieces);
            $comma_separated = implode(",", $pieces);
            $image_name = $comma_separated.'_('.date('Y-m-d-H-m-s').').'.$fruit;

            // move fili in derectory
            $file -> move(public_path().$image_dir, $image_name);

            // updete file name for add in DB
            return $image_name;
        }
    }
    public function image_update($image_dir, $product, $input, $request)
    {
        if ($request->hasFile('image_1')) {

            $file = $request -> file('image_1');

            //get original file
            $input['image_1'] = $file -> getClientOriginalName();

            //rename file
            $pieces = explode( '.', $input['image_1'] );
            $fruit = array_pop($pieces);
            $comma_separated = implode(",", $pieces);
            $image_name = $comma_separated.'_('.date('Y-m-d-H-m-s').').'.$fruit;

            // move fili in derectory
            $file -> move(public_path().$image_dir, $image_name);

            // delite old file
            $fileName = $product['image_1'];
            $destinationPath = $image_dir;
            File::delete($destinationPath.$fileName);

            // updete file name in array for add in DB
            return $image_name; 
        }
        else {
            return $input['old_image'];
        }
    }

    public function get_url_title($title)
    {
        $title_pieces = explode(" ", $title);
        $url_title = implode("_", $title_pieces);

        return $url_title;
    }
}
