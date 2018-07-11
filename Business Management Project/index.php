<?php
require 'lib/libs/Slim/Slim.php';
require 'lib/libs/RedBean/rb.php';
require 'lib/libs/classes/constant.php';
require 'lib/libs/classes/SystemUtils.php';
require 'lib/libs/classes/enconding.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim( array('mode' => 'production', 'templates.path' => 'views', 'debug' => true, 'routes.case_sensitive' => false));
date_default_timezone_set('Africa/Kigali');
try {
	R::setup(DB_SERVER, DB_USER, DB_PASS);
	R::setAutoResolve(true);
	R::freeze(true);
} catch (Exception $e) {
	echo $e -> getMessage() . ' .. ' . $e -> getTraceAsString();
}
session_start();
// init access headers
$app -> response() -> header('Access-Control-Allow-Origin: *');

$app -> response() -> header('Access-Control-Allow-Methods: GET, POST, DELETE');

$app -> response() -> header("Access-Control-Allow-Headers: X-Requested-With");

$app -> response() -> header("Access-Control-Allow-Headers: Content-Type");

//START OF ROUTERS TO RENDER PAGES
$app -> get('/', function() use ($app) {
	 if (isset($_SESSION['username'])) {
	 	$app -> redirect('/stock');
	 } else {
		$app -> render('login.html');
	 }
});

$app -> get('/login', function() use ($app) {
	if (isset($_SESSION['username'])) {
		$app -> redirect('/stock');
	} else {
		$app -> render('login.html');
	}
});

$app -> get('/stock', function() use ($app) {
	if (isset($_SESSION['username'])) {
		$app -> render('stock.html', array("username" => $_SESSION['username']));
	} else {
		$app -> redirect('/login');
	}
});

$app -> get('/selling', function() use ($app) {
	if (isset($_SESSION['username'])) {
		$app -> render('selling.html', array("username" => $_SESSION['username']));
	} else {
		$app -> redirect('/login');
	}
});
//END OF ROUTERS TO RENDER PAGES------------------------------------

//router of post
$app -> post('/login/credentials', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(loginAdmin($app), true));
});
$app -> post('/add/category', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addCategory($app), true));
});
$app -> post('/update/category/status/', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(updateCategoryStatus($app), true));
});
$app -> post('/add/brand', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addBrand($app), true));
});
$app -> post('/update/brand/status/', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(updateBrandStatus($app), true));
});
$app -> post('/add/product', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addProduct($app), true));
});
$app -> post('/add/to/stock', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addProductToStock($app), true));
});
$app -> post('/remove/to/stock', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(removeProductToStock($app), true));
});
$app -> post('/add/customer', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addCustomer($app), true));
});
$app -> post('/add/to/sell', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(addToSell($app), true));
});

//router of delete
$app -> delete('/logout', function() use ($app) {
	try {
		unset($_SESSION['username']);
		session_destroy();
		$app -> deleteCookie('user');
		return true;
	} catch (Exception $e) {
		echo ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return false;
	$app -> render('login.html');

});


// router of getting
$app -> get('/category/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(RetrieveCategory(), true));
});
$app -> get('/update/category/name/:id/:name', function($id,$name) use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(editCategoryName($id,$name), true));
});
$app -> get('/brand/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(RetrieveBrand(), true));
});
$app -> get('/update/brand/name/:id/:name', function($id,$name) use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(editBrandName($id,$name), true));
});
$app -> get('/product/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(RetrieveProduct(), true));
});
$app -> get('/existing/product/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(RetrieveExistingProduct(), true));
});
$app -> get('/stock/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(viewStock(), true));
});
$app -> get('/product/number/:productId', function($productId) use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(stockProdNbr($productId), true));
});
$app -> get('/sale/stock/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(saleStockList(), true));
});
$app -> get('/customer/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(customerList(), true));
});
$app -> get('/selling/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(retrieveFromSell(), true));
});
$app -> get('/product/details/:id', function($id) use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(retrieveProductDetails($id), true));
});
$app -> get('/suppliers/list', function() use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(retrievefromsuppliers(), true));
});
// delete worker
$app -> delete('/delete/worker/:id', function($id) use ($app) {
	$app -> response() -> header('Content-Type', 'application/json');
	$app -> response() -> setBody(json_encode(deleteworker($id), true));
});

$app -> run();

function loginAdmin($app) {
	try {
		$responseData = array('status' => 'failed', 'data' => 'Sorry login failed. Check your credentials or your status.');
		$message = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
		$Personel = R::findOne("users", "username=? AND password=? AND status=?", array($message['usr'],($message['pswd']), '1'));
		if ($Personel) {
			$Personel -> lastlogin = date("Y-m-d H:i:s");
			R::store($Personel);
			$user = $Personel -> getProperties();
			unset($Personel);
			$_SESSION['username'] = $user['username'];
			$_SESSION['id'] = $user['id'];			
			$_SESSION['authorize'] = $user['privilege'];
			setcookie("user", $user['username'], time() + (86400 * 30), "/");
			$responseData['status'] = 'success';

			if($_SESSION['authorize'] == 0){
				$responseData['data'] = '/selling';
			}else{
				$responseData['data'] = '/stock';
			}
		}

	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function addCategory($app) {
	$responseData = array('status' => 'failed', 'data' => "Unable to save this category for now...");
	try {
			$categ = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_categ = R::dispense('category');
			$add_categ -> category_name = $categ['Categ_name'];
			$add_categ -> status = 1;
			$add_categ -> regdate = date("Y-m-d H:i:s");
			$id = R::store($add_categ);
			if ($id >= 1) {
				$responseData['data'] = ' The Category is well added!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function updateCategoryStatus($app) {
	$responseData = array('status' => 'failed', 'data' => ' Can\'t update the Category status');
	try {
		$categ = json_decode($app -> request -> getBody(), true);
		$categ_row = R::findOne("category", "status=? AND id=? ", array($categ['status'], $categ['id']));
		if ($categ_row) {
			if ($categ['status'] == '0') {
				$categ_row -> status = '1';
			} elseif ($categ['status'] == '1') {
				$categ_row -> status = '0';
			}
			$id = R::store($categ_row);
			if ($id >= 1) {
				$responseData['data'] = ' The Category is well updated!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'An error occured';
			}
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function editCategoryName($id,$name){
	$responseData = array('status' => 'failed', 'data' => 'Unable to updated ' . PHP_EOL);
	try {
		$edit_request = R::findOne("category", "id=?", array($id));
		if ($edit_request) {						
				$edit_request -> category_name = $name;				
			$id = R::store($edit_request);
			
			if ($id >= 1) {
				$responseData['data'] = ' successfully well updated!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function addBrand($app) {
	$responseData = array('status' => 'failed', 'data' => "Unable to save this brand for now...");
	try {
			$brand = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_brand = R::dispense('brand');
			$add_brand -> brand_name = $brand['brand_name'];
			$add_brand -> status = 1;
			$add_brand -> regdate = date("Y-m-d H:i:s");
			$id = R::store($add_brand);
			if ($id >= 1) {
				$responseData['data'] = ' The Brand is successfully added!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function updateBrandStatus($app){
	$responseData = array('status' => 'failed', 'data' => ' Can\'t update the brand status');
	try {
		$brand = json_decode($app -> request -> getBody(), true);
		$brand_row = R::findOne("brand", "status=? AND id=? ", array($brand['status'], $brand['id']));
		if ($brand_row) {
			if ($brand['status'] == '0') {
				$brand_row -> status = '1';
			} elseif ($brand['status'] == '1') {
				$brand_row -> status = '0';
			}
			$id = R::store($brand_row);
			if ($id >= 1) {
				$responseData['data'] = ' The Brand is well updated!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'An error occured';
			}
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function editBrandName($id,$name){
	$responseData = array('status' => 'failed', 'data' => 'Unable to updated ' . PHP_EOL);
	try {
		$edit_request = R::findOne("brand", "id=?", array($id));
		if ($edit_request) {						
				$edit_request -> brand_name = $name;				
			$id = R::store($edit_request);
			
			if ($id >= 1) {
				$responseData['data'] = ' successfully well updated!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function addProduct($app){
	$responseData = array('status' => 'failed', 'data' => "Unable to save this product for now...");
	try {
			$prod = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_prod = R::dispense('product');
			$add_prod -> pname = $prod['product_name'];
			$add_prod -> description  = $prod['description'];
			$add_prod -> id_category = $prod['cat_id'];
			$add_prod -> id_brand = $prod['brand_id'];
			$id = R::store($add_prod);
			if ($id >= 1) {
				$responseData['data'] = ' The Product is successfully added!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function addProductToStock($app){
	$responseData = array('status' => 'failed', 'data' => "Unable to add this product for now...");
	try {

			$prod = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_prod = R::dispense('stock');

			$prod_existing = R::findOne("stock", "id_product=? ORDER BY id DESC LIMIT 1", array($prod['product']));
			if($prod_existing){

				$add_prod -> id_product = $prod['product'];
				$add_prod -> quantity_entry = $prod['quantity'];
				$add_prod -> price  = $prod['up'];
				$add_prod -> total_quantity  = $prod_existing['total_quantity']+$prod['quantity'];
				$add_prod -> regdate  = date("Y-m-d H:i:s");
				 
			}
			else{
				$add_prod -> id_product = $prod['product'];
				$add_prod -> quantity_entry = $prod['quantity'];
				$add_prod -> price  = $prod['up'];
				$add_prod -> total_quantity  = $prod['quantity'];
				$add_prod -> regdate  = date("Y-m-d H:i:s");
			}
			

			$id = R::store($add_prod);
			if ($id >= 1) {
				$responseData['data'] = 'successfully added';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function removeProductToStock($app){
	$responseData = array('status' => 'failed', 'data' => "Unable to remove this product quantity for now...");
	try {
			$prod = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_prod = R::dispense('stock');

			$prod_existing = R::findOne("stock", "id_product=? ORDER BY id DESC LIMIT 1", array($prod['product']));
			if($prod_existing){
				$add_prod -> id_product = $prod['product'];

				if($prod['quantity'] > $prod_existing['total_quantity']){
					$responseData['data'] = $prod['quantity']  .'   is TOO MUCH quantity! Check your Stock please';
					return $responseData;
				}

				$add_prod -> quantity_out = $prod['quantity'];
				$add_prod -> total_quantity  = $prod_existing['total_quantity']-$prod['quantity'];
				$add_prod -> regdate  = date("Y-m-d H:i:s");

				if($prod['selectedDestination'] == 'vente'){
					$add_prod -> destination = 'vente';

					$pro_salestock = R::findOne("salestock", "id_product=?", array($prod['product']));
					if($pro_salestock){
						$pro_salestock -> quantity = $pro_salestock['quantity'] + $prod['quantity'];
						$id = R::store($pro_salestock);
					}

					else{
						$add_salestock = R::dispense('salestock');
						$add_salestock -> id_product = $prod['product'];
						$add_salestock -> quantity = $prod['quantity'];
						$id = R::store($add_salestock);
					}
					
				}
				else{
					$add_prod -> destination = $prod['Destination'];
				}
			}
			else{
				$add_prod -> id_product = $prod['product'];
				$add_prod -> quantity_out = $prod['quantity'];
				$add_prod -> total_quantity  = $prod['quantity'];
				$add_prod -> regdate  = date("Y-m-d H:i:s");

				if($prod['selectedDestination'] == 'vente'){
					$add_prod -> destination = 'vente';
				}else{
					$add_prod -> destination = $prod['Destination'];
				}
			}
			
			$id = R::store($add_prod);
			if ($id >= 1) {
				$responseData['data'] = 'successfully Removed';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}	
		}  catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function RetrieveCategory() {
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any category");
	try {
		$responseData['data'] = R::getAll("SELECT *, LPAD(id,3,0) AS id_modified,DATE_FORMAT(regdate,'%b %D %Y') AS regdate_modified FROM category");
		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} else {
			$responseData['data'] = 'There are no category at this time.';
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function RetrieveBrand() {
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any brand");
	try {
		$responseData['data'] = R::getAll("SELECT *, LPAD(id,2,0) AS id_modified,DATE_FORMAT(regdate,'%b %D %Y') AS regdate_modified FROM brand");
		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} else {
			$responseData['data'] = 'There are no brand at this time.';
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function RetrieveProduct() {
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any product");
	try {
		$responseData['data'] = R::getAll("SELECT *, LPAD(id,3,0) AS id_modified,(SELECT category_name FROM category WHERE id=id_category)AS categoryName,(SELECT brand_name FROM brand WHERE id=id_brand)AS brandName FROM product");
		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} else {
			$responseData['data'] = 'There are no product at this time.';
		}
	}  catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function RetrieveExistingProduct() {
	$responseData = array('status' => 'failed', 'data' => "Product does not exist");
	try {
		$responseData['data']= R::getAll("SELECT DISTINCT id_product,(SELECT pname FROM product WHERE id=id_product) AS pname FROM stock");

		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} else {
			$responseData['data'] = 0;
		}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function viewStock() {
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any product");
	try {
		$allInSStock= R::getAll("SELECT id_product,total_quantity, (SELECT pname FROM product WHERE id=id_product) AS pname, (SELECT id_category FROM product WHERE id=id_product) AS id_category ,(SELECT category_name FROM category WHERE id=id_category) AS category_name, (SELECT id_brand FROM product WHERE id=id_product) AS id_brand, (SELECT brand_name FROM brand WHERE id=id_brand) AS brand_name FROM stock t1 WHERE regdate=(SELECT max(regdate) FROM stock WHERE t1.id_product=stock.id_product) ORDER BY regdate desc");

		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
			$responseData['data'] = $allInSStock;
		} 
		else {
			$responseData['data'] = 'There are no product at this time.';
		}
	}  catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function stockProdNbr($productId){
	$responseData = array('status' => 'failed', 'data' => ' Unable to retrieve the product number' . PHP_EOL);
	try {
		$id_request = R::findOne("stock", "id_product=? ORDER BY id DESC LIMIT 1", array($productId));
		if ($id_request) {	
				$responseData['data'] = $id_request['total_quantity'];
				$responseData['status'] = 'success';
		}
		 else {
				$responseData['data'] = 'Some problem occur try again.';
			}
	} catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function saleStockList() {
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any product");
	try {
		$responseData['data']= R::getAll("SELECT *, (SELECT pname FROM product WHERE id=id_product) AS pname FROM salestock");

		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} 
		else {
			$responseData['data'] = 'There are no product at this time.';
		}
	}  catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function customerList(){
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any product");
	try {
		$responseData['data']= R::getAll("SELECT * FROM customers");

		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} 
		else {
			$responseData['data'] = 'There are no product at this time.';
		}
	}  catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}

function addCustomer($app){
	$responseData = array('status' => 'failed', 'data' => "Unable to save the customer for now...");
	try {
			$customer = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$add_cus = R::dispense('customers');
			$add_cus -> customer_name = $customer['cus_name'];
			$add_cus -> regdate = date("Y-m-d H:i:s");
			$id = R::store($add_cus);
			if ($id >= 1) {
				$responseData['data'] = ' Customer successfully added!';
				$responseData['status'] = 'success';
			}else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function addToSell ($app){
	$responseData = array('status' => 'failed', 'data' => "Unable to register this selling for now...");
	try {
			$sell = json_decode(Encoding::toUTF8($app -> request -> getBody()), true);
			$salestock_pro = R::findOne("salestock", "id_product=?", array($sell['ProId']));
			if($salestock_pro){
				if($sell['quantity']>$salestock_pro['quantity']){
					$responseData['data'] = 'There are no enough items. Please check your stock';
					return $responseData;
				}
				$salestock_pro -> quantity = $salestock_pro['quantity']-$sell['quantity'];
				$id = R::store($salestock_pro);
			}
			$exist_invoice = R::findOne("sales", "invoice_number=?", array($sell['invoice']));
			if($exist_invoice){
				$responseData['data'] = 'This invoice number already exists!';
				return $responseData;
			}

			$add_sell = R::dispense('sales');
			$add_sell -> invoice_number = $sell['invoice'];
			$add_sell -> id_product = $sell['ProId'];
			$add_sell -> quantity_sold = $sell['quantity'];
			$add_sell -> unit_price = $sell['unityP'];
			$add_sell -> total_amount  = $sell['unityP'] * $sell['quantity'];
			$add_sell -> customer_id  = $sell['custId'];
			$add_sell -> payment_mode  = $sell['payment'];
			$add_sell -> regdate = date("Y-m-d H:i:s");
			$id = R::store($add_sell);
			if ($id >= 1) {
				$responseData['data'] = 'The sale is successfully registred!';
				$responseData['status'] = 'success';
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function retrieveFromSell (){
	$responseData = array('status' => 'failed', 'data' => "Unable to register this selling for now...");
	try {
			$responseData['data'] = R::getAll("SELECT *, (SELECT pname FROM product WHERE id=id_product) AS pname, (SELECT customer_name FROM customers WHERE id=customer_id) AS customer FROM sales ORDER BY id DESC");

			if (sizeof($responseData['data']) >= 1) {
				$responseData['status'] = 'success';
				
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function retrieveProductDetails($id){
	$responseData = array('status' => 'failed', 'data' => "Unable to retrieve this product details for now ...");
	try {
			$responseData['data'] = R::getAll("SELECT * FROM stock WHERE id_product=? ORDER BY id ", array($id));

			if (sizeof($responseData['data']) >= 1) {
				$responseData['status'] = 'success';
				
			} else {
				$responseData['data'] = 'Some problem occur try again.';
			}
		} catch (Exception $e) {
			$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
		}	
		return $responseData;
}

function retrievefromsuppliers(){
	$responseData = array('status' => 'failed', 'data' => "we couldn't retrieve any supplier");
	try {
		$responseData['data']= R::getAll("SELECT * FROM supplier");

		if (sizeof($responseData['data']) >= 1) {
			$responseData['status'] = 'success';
		} 
		else {
			$responseData['data'] = 'No supplier at this time.';
		}
	}  catch (Exception $e) {
		$responseData['data'] = ' error in  ' . $e -> getMessage() . ' trace ' . $e -> getTraceAsString();
	}
	return $responseData;
}