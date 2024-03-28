<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\AuditTrail;
use Cache;

class ProductManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public $module = "Product Management";

     public function index()
    {
        $product = new Product;
        $product = $product->readAllProductManagement();

        if(request()->ajax())
        { 
            return datatables()->of($product)
                ->addColumn('action', function($product)
                {
                    $button = ' <a class="btn btn-sm" data-id="'. $product->id .'" href="'. route('product-management.edit',$product->id) .'"><i class="fa fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->addColumn('selling_price', function($product)
                {
                    $button = ' <div class="text-right">₱'.$product->selling_price.'</div>';
                   
                    return $button;
                })
                ->addColumn('orig_price', function($product)
                {
                    $button = ' <div class="text-right">₱'.$product->orig_price.'</div>';
                   
                    return $button;
                })
                ->rawColumns(['action', 'selling_price', 'orig_price'])
                ->make(true);
        }

        return view('admin.inventory.product-management.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product,$id)
    {
        $product = $product->readAllProductManagementById($id);
        $unit = Unit::where('status', 1)->get();
        $category = Category::where('status', 1)->get();
        $supplier = Supplier::where('status', 1)->get();

        // return dd($product);

        return view('admin.inventory.product-management.edit', [
            'product' => $product,
            'unit' => $unit, 
            'category' => $category, 
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $at = new AuditTrail;

        $this->validateInputs($request);

        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUpload($request);
        }

        Product::where('id', $id)->update($data);

        $at->audit($this->module, 'Update Product With ID: P-00000' . $id);

        $this->cacheProducts();

        return redirect()->back()
           ->with('success', 'Product was updated.');
    }

    public function cacheProducts() 
    {
        Cache::rememberForever('all_products',  function () {
            $product = new Product;
            return $product->readAllProductManagement();
        });
    }

    public function validateInputs($request) {
        $request->validate([
            // 'description' => 'required|:product',
            'qty' => 'required:product',
            'reorder' => 'required:product',
            'orig_price' => 'required:product',
            // 'category_id' => 'required:product',
            // 'unit_id' => 'required:product',
            // 'supplier_id' => 'required:product',
        ]);
    }
}
