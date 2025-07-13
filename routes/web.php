<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/user-account', [UserController::class, 'index'])->name('user.index');
    Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name("shop.product.details");
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'addToCart'])->name('cart.store');
    Route::put('/cart/increase-qunatity/{rowId}', [CartController::class, 'increase_item_quantity'])->name('cart.increase.qty');
    Route::put('/cart/reduce-qunatity/{rowId}', [CartController::class, 'reduce_item_quantity'])->name('cart.reduce.qty');
    Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item_from_cart'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty');
    Route::post('/wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'remove_item_from_wishlist'])->name('wishlist.remove');
    Route::delete('/wishlist/clear', [WishlistController::class, 'empty_wishlist'])->name('wishlist.empty');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');
    Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
    Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-order', [CartController::class, 'place_order'])->name('cart.place.order');
    Route::get('/order-confirmation', [CartController::class, 'confirmation'])->name('cart.confirmation');
    Route::get('/account-orders', [UserController::class, 'account_orders'])->name('user.account.orders');
    Route::get('/account-order-detials/{order_id}', [UserController::class, 'account_order_details'])->name('user.acccount.order.details');
    Route::put('/account-order/cancel-order', [UserController::class, 'account_cancel_order'])->name('user.account_cancel_order');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    //
    // Brand Page
    //
    Route::get('/admin/brands', action: [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/add-brand', [AdminController::class, 'addBrand'])->name('admin.addbrand');
    Route::post('/admin/store-brand', [AdminController::class, 'storeBrand'])->name('admin.storebrand');;
    Route::get('/admin/edit-brand/{id}', [AdminController::class, 'editBrand'])->name('admin.editbrand');
    Route::put('/admin/brand/update', [AdminController::class, 'update_brand'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'delete_brand'])->name('admin.brand.delete');

    //
    // Products Page
    //
    Route::get('/admin/products', action: [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'add_product'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit', [AdminController::class, 'edit_product'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'update_product'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'delete_product'])->name('admin.product.delete');

    //
    // Category Page
    //
    Route::get('/admin/categories', action: [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'add_category'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class, 'add_category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit', [AdminController::class, 'edit_category'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'update_category'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'delete_category'])->name('admin.category.delete');

    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [AdminController::class, 'add_coupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class, 'add_coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit', [AdminController::class, 'edit_coupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'update_coupon'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'delete_coupon'])->name('admin.coupon.delete');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/items/{order_id}', [AdminController::class, 'order_items'])->name('admin.order.items');
    Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');
    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/add', [AdminController::class, 'slides_add'])->name('admin.slide.add');
    Route::post('/admin/slide/add', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit', [AdminController::class, 'edit_slide'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
        Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'delete_slide'])->name('admin.slide.delete');

});
