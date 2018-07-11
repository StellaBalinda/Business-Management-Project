var app = angular.module('myAlpha', ['alpha.services', 'ui.bootstrap' , 'simple-autocomplete', 'angularUtils.directives.dirPagination']);
//================================  this is the control of the login page   ==================================//

app.controller('Ctr_Login', function($scope, Data, $window) {

	$scope.login = function() {
		
		var logins = {};
		var username = _('username').value;
		var password = _('password').value;

		if (username == "" || password == "") {
			_('isa_error').style.display = "block";
			_('isa_error_message').innerHTML = "Please fill the form correctly";
			setTimeout(hidden_error_sign_in, 3000);
			return false;
		}
		logins.usr = username;
		logins.pswd = password;
		// _('img_succes_loading').style.visibility = "visible";
		Data.post('/login/credentials', {
			dashData : logins
		}).success(function(response) {
			// _('img_succes_loading').style.visibility = "hidden";
			if (response.status == "success") {
				$window.location.href = response.data;
			} else {
				_('isa_error').style.display = "block";
				_('isa_error_message').innerHTML = response.data;
				setTimeout(hidden_error_sign_in, 5000);
			}
		}).error(function(err) {
			console.log(err);
		});
	};


});

//================================ this is the control of members (add, view and edit)  ===============
app.controller('Ctr_Stock', function($scope, Data, $window) {

	$scope.logout = function() {
		Data.
		delete ('/logout').success(function(response) {
			location.reload();
		}).error(function(err) {
			console.log('connection failed.');
		});
	};

	$scope.startStock = function() {
		$scope.categoryList();
		$scope.brandList();
		$scope.productList();
		$scope.existingProductList();
	};

	$scope.saveCategory = function (){
		var cat = {}; 
		var Categ_name = _('Categ_name').value;
			cat.Categ_name = Categ_name;
		// _('img_succes_loading').style.visibility = "visible";
		Data.post('/add/category', {
			dashData : cat
		}).success(function(response) {
			if (response.status === "success") {
				_('isa_success_cat').style.display = "block";
				_('isa_success_message_cat').innerHTML = response.data;
				_('addCateg_form').reset();
				$scope.categoryList();
				setTimeout(hidden_error_add_cat, 3000);
			} else {
				_('isa_error_cat').style.display = "block";
				_('isa_error_message_cat').innerHTML = response.data;
				_('addCateg_form').reset();
				setTimeout(hidden_error_add_cat, 3000);
			}
		}).error(function(err) {
			console.log(err);
		});
	};

		$scope.editStatusCategory = function(status, id) {
		var status_id_pair = {};
		var new_status = status;
		var new_id = id;

		status_id_pair.status = new_status;
		status_id_pair.id = new_id;

		Data.post('/update/category/status/', {
			dashData : status_id_pair
		}).success(function(response) {
			if (response.status == "success") {
				var priority = 'success';
				var title = 'success';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
				$scope.categoryList();
			} else {
				var priority = 'error';
				var title = 'error';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
			}
		}).error(function(err) {
			console.warn(err);
		});

	};

	$scope.correspondEditCategory = function(myid,id,name){
    	$scope.mycorrespondedEditCategory = myid;
    	$scope.categoryId = id;
    	$scope.categoryCurrentName = name;
    };

    $scope.editCategoryName = function(categId, categName){
    	Data.get('/update/category/name/'+ categId + '/' +categName).success(function(response) {
			if (response.status == "success") {
				var priority = 'success';
				var title = 'success';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
				$scope.categoryList();
			}
			else {
				var priority = 'error';
				var title = 'error';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
			}
		}).error(function(err) {
			console.warn(err);
		});
    };

	$scope.saveBrand = function (){
		var brand = {}; 
		var brand_name = _('brand_name').value;
			brand.brand_name = brand_name;
		Data.post('/add/brand', {
			dashData : brand
		}).success(function(response) {
			if (response.status === "success") {
				_('isa_success_brand').style.display = "block";
				_('isa_success_message_brand').innerHTML = response.data;
				_('addBrand_form').reset();
				$scope.brandList();
				setTimeout(hidden_error_add_brand, 4000);
			} else {
				_('isa_error_brand').style.display = "block";
				_('isa_error_message_brand').innerHTML = response.data;
				_('addBrand_form').reset();
				setTimeout(hidden_error_add_brand, 4000);
			}
		}).error(function(err) {
			console.log(err);
		});
	};

	$scope.editStatusBrand = function(status, id) {
		var status_pair = {};
		var new_status = status;
		var new_id = id;

		status_pair.status = new_status;
		status_pair.id = new_id;

		Data.post('/update/brand/status/', {
			dashData : status_pair
		}).success(function(response) {
			if (response.status == "success") {
				var priority = 'success';
				var title = 'success';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
				$scope.brandList();
			} else {
				var priority = 'error';
				var title = 'error';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
			}
		}).error(function(err) {
			console.warn(err);
		});

	};

	$scope.correspondEditBrand = function(myid,id,name){
    	$scope.mycorrespondedEditBrand = myid;
    	$scope.brandId = id;
    	$scope.brandCurrentName = name;
    };

    $scope.editBrandName = function(brandId, brandName){
    	Data.get('/update/brand/name/'+ brandId + '/' +brandName).success(function(response) {
			if (response.status == "success") {
				var priority = 'success';
				var title = 'success';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
				$scope.brandList();
			}
			else {
				var priority = 'error';
				var title = 'error';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
			}
		}).error(function(err) {
			console.warn(err);
		});
    };

	$scope.saveProduct = function(product_name,description,cat_id,brand_id){

		if (product_name == null) {
			_('isa_error_prod').style.display = "block";
			_('isa_error_message_prod').innerHTML = ' Add a  <b> Product</b>  name please!';
			setTimeout(hidden_error_add_product, 3000);
			return false;
		}
		if (cat_id == null) {
			_('isa_error_prod').style.display = "block";
			_('isa_error_message_prod').innerHTML = ' Select a <b> Category</b>  please!!';
			setTimeout(hidden_error_add_product, 3000);
			return false;
		}
		if (brand_id == null) {
			_('isa_error_prod').style.display = "block";
			_('isa_error_message_prod').innerHTML = ' Select a <b> Brand</b>  please!!';
			setTimeout(hidden_error_add_product, 3000);
			return false;
		}

		var product = {};
		product.product_name = product_name;
		product.description = description
		product.cat_id = cat_id;
		product.brand_id = brand_id;

			Data.post('/add/product', {
			dashData : product
		}).success(function(response) {
			if (response.status === "success") {
				_('isa_success_prod').style.display = "block";
				_('isa_success_message_prod').innerHTML = response.data;
				_('addProd_form').reset();
				$scope.productList();
				setTimeout(hidden_error_add_product, 3000);
			} else {
				_('isa_error_prod').style.display = "block";
				_('isa_error_message_prod').innerHTML = response.data;
				_('addProd_form').reset();
				setTimeout(hidden_error_add_product, 3000);
			}
		}).error(function(err) {
			console.log(err);
		});
	};

	$scope.categoryList = function() {
		Data.get('/category/list').success(function(response) {
			$scope.category_list = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.brandList = function() {
		Data.get('/brand/list').success(function(response) {
			$scope.brand_list = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.productList = function() {
		Data.get('/product/list').success(function(response) {
			$scope.product_list = response.data;
			$scope.last_product = $scope.product_list.slice(-1)[0];
			// $scope.last_product = $scope.product_list.pop();
			// $scope.datas = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.existingProductList = function() {
		Data.get('/existing/product/list').success(function(response) {
			$scope.existing_productList = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.stockList = function() {
		Data.get('/stock/list').success(function(response) {
			$scope.stock_List = response.data;
			$scope.count = 0;
		}).error(function(err) {
			console.warn(err);
		});
	};	

	
	$scope.showMe = false;
    $scope.showDescription = function() {
        $scope.showMe = !$scope.showMe;
    };
     $scope.hideDescription = function() {
        $scope.showMe = false;
    };

    $scope.addProductToStock = function(prodToAddId,addedQnty,addedUP){
    	if (prodToAddId == null || addedQnty == null || addedUP == null) {
			swal("OOPS...", "Make sure to select and fill all the fields", "error");
			return;		
		}
		if (!(validateNumber(addedQnty))){
			swal("OOPS...", "Make sure that the quantity is number ", "error");
			return;		
		}
		if (!(validateNumber(addedUP))) {
			swal("OOPS...", "Make sure that the UNITY PRICE is number", "error");
			return;		
		}

		var addToStock = {};
		addToStock.product = prodToAddId;
		addToStock.quantity = addedQnty;
		addToStock.up = addedUP;

			Data.post('/add/to/stock', {
			dashData : addToStock
		}).success(function(response) {
			if (response.status == "success") {
				swal("YES", response.data, "success");
				$scope.stockList();
				$scope.existingProductList();
			} else {
				swal("OOPS...", response.data , "error");
			}
		}).error(function(err) {
			console.log(err);
		});
    };


    $scope.RegisterProductAndAddProductToStock = function(prodToAddId,addedQnty,addedUP){
    	if (prodToAddId == null || addedQnty == null || addedUP == null) {
			swal("OOPS...", "Make sure to select and fill all the fields", "error");
			return;		
		}
		if (!(validateNumber(addedQnty))){
			swal("OOPS...", "Make sure that the quantity is number ", "error");
			return;		
		}
		if (!(validateNumber(addedUP))) {
			swal("OOPS...", "Make sure that the UNITY PRICE is number", "error");
			return;		
		}

		var addToStock = {};
		addToStock.product = prodToAddId;
		addToStock.quantity = addedQnty;
		addToStock.up = addedUP;

			Data.post('/add/to/stock', {
			dashData : addToStock
		}).success(function(response) {
			if (response.status == "success") {
				swal("YES", response.data, "success");
				$scope.stockList();
				$scope.existingProductList();
			} else {
				swal("OOPS...", response.data , "error");
			}
		}).error(function(err) {
			console.log(err);
		});
    };

    $scope.correspondAddToStock = function(myid,id){
    	$scope.mycorrespondedAddToStock = myid;
    	$scope.productId_added = id;
    };

    $scope.addProductToStockModal = function(proId,qnty,up){
		$scope.addProductToStock(proId,qnty,up);
    };

	$scope.showProNbr = false;
    $scope.stockProductNbr = function(productId) {
    	_('nbr_warning').innerHTML = "";
    	$scope.removed_qnty = "";
        Data.get('/product/number/' +productId).success(function(response) {
        	$scope.showProNbr = true;
			 $scope.product_number = response.data;
			 	if($scope.product_number == 0){
			 		_('remove_btn').disabled = true;
			 	}else{
			 		_('remove_btn').disabled = false;
			 	}
			}).error(function(err) {
				console.warn(err);
			});
    };

    $scope.rmvNbrValidation = function(removed_qnty){
    	if(removed_qnty>$scope.product_number){
    		_('nbr_warning').innerHTML = "    Look at the stock status please";
    		_('remove_btn').disabled = true;
    	}else{
    		_('nbr_warning').innerHTML = "";
    		_('remove_btn').disabled = false;
    	}
    };

    $scope.correspondRemoveToStock = function(myid,id){
		$scope.mycorrespondedRemoveToStock = myid;
		$scope.productId_rmvd = id;
		$scope.stockProductNbr(id);
	};

	$scope.removeProductToStock = function(prodToRemoveId,removed_qnty,selectedDestination,Destination){
			if (prodToRemoveId == null) {
				swal("OOPS...", "Make sure to select the product", "error");
				return;		
			}
			if (removed_qnty == null) {
				swal("OOPS...", "Make sure to specify the quantity", "error");
				return;		
			}
			if (!(validateNumber(removed_qnty))){
				swal("OOPS...", "Make sure that the quantity is number ", "error");
				return;		
			}
			if (selectedDestination == null){
				swal("OOPS...", "Make sure to select the destination ", "error");
				return;		
			}
			if (selectedDestination == "other" && Destination == null){
				swal("OOPS...", "Make sure to specify the reason ", "error");
				return;		
			}

			var rmvToStock = {};
			rmvToStock.product = prodToRemoveId;
			rmvToStock.quantity = removed_qnty;
			rmvToStock.selectedDestination = selectedDestination;
			rmvToStock.Destination = Destination;

			Data.post('/remove/to/stock', {
				dashData : rmvToStock
			}).success(function(response) {
				if (response.status == "success") {
					swal("YES", response.data, "success");
					$scope.stockList();
					$scope.existingProductList();
					$scope.productDetails(prodToRemoveId);
				} else {
					swal("OOPS...", response.data , "error");
				}
			}).error(function(err) {
				console.log(err);
			});
		};

	$scope.removeProductToStockModal = function(proId,qnty,selectedDestination_modal,Destination_modal){
		$scope.removeProductToStock(proId,qnty,selectedDestination_modal,Destination_modal);
	};

	$scope.correspondMoreProdDetails = function(myid,id){
		$scope.prodDetailsModal = myid;
		$scope.productDetails(id);
	};	

	$scope.productDetails = function(Id){
		Data.get('/product/details/' +Id).success(function(response) {
			$scope.product_details = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.supplierList=function(){
      Data.get('/suppliers/list').success(function(response){
      	$scope.supplier_list=response.data;
      }).error(function(err){
      	console.warn(err);
      })
	};

	// this is direct copy paste fro net

	 //  $scope.party = {
		// 		    "invited": [{
		// 		      "email": "a@mail.com"
		// 		    }, {
		// 		      "email": "b@mail.com"
		// 		    }]
		// 		  };

  // $scope.tasks = ["suppls"];
  // $scope.addTask = function() {
  //   console.log($scope.tasks);
  // };
  // $scope.inputs =  {"fields":
		// 				[
		// 				    {"supp":"", "prod":"","qnt":"", "purch":"","sell":""}
		// 				]
		// 			};

	$scope.inputs = [];
	$scope.tasks = [{'supp': '','prod':'','qnt': '','purch':'','sell':''}];

	$scope.addInput = function (task) {
   // $scope.inputs.push(task);
    console.log(task);
	};

	$scope.removeInput = function (index) {
    $scope.inputs.splice(index, 1);
	};

	$scope.saveSupplier = function(index,supplid,prodid,quant,purchs,sells){
		console.log(index,supplid,prodid,quant,purchs,sells);
	};

});

app.controller('Ctr_Selling', function($scope, Data, $window) {

	$scope.startSelling = function() {
		$scope.saleStockList();
		$scope.customerList();
		$scope.salesList();
	};

	$scope.logout = function() {
		Data.
		delete ('/logout').success(function(response) {
			location.reload();
		}).error(function(err) {
			console.log('connection failed.');
		});
	};

	$scope.saleStockList = function() {
		Data.get('/sale/stock/list').success(function(response) {
			$scope.saleStock_list = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.customerList = function() {
		Data.get('/customer/list').success(function(response) {
			$scope.customer_list = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.addCustomer = function(custName) {
		if (custName == null) {
			_('isa_error_customer').style.display = "block";
			_('isa_error_message_customer').innerHTML = "Specify the name please";
			setTimeout(hidden_error_customer, 3000);
			return false;
		}
		Data.post('/add/customer', {
			dashData : {
				"cus_name" : custName
			}
		}).success(function(response) {
			if (response.status == "success") {
				_('isa_success_customer').style.display = "block";
				_('isa_success_message_customer').innerHTML = response.data;
				$scope.customerList();
				$scope.custNameModal = ""; 
				setTimeout(hidden_error_customer, 3000);
			} else {
				_('isa_error_root_income').style.display = "block";
				_('isa_error_message_root_income').innerHTML = response.data;
				setTimeout(hidden_error_customer, 3000);
			}
		}).error(function(err) {
			console.warn(err);
		});
	};

	$scope.sellSaving = function(invoice,ProId,quantity,unityP,custId,payment){
		if (invoice == null || ProId == null || quantity == null || custId == null || payment == null) {
			var priority = 'error';
			var title = 'error';
			var message = "Make sure to fill all fields";
			$.toaster({
				priority : priority,
				title : title,
				message : message
			});
			return;		
		}

		if (!(validateNumber(quantity))){
			var priority = 'error';
			var title = 'error';
			var message = "Make sure the quantity is a number";
			$.toaster({
				priority : priority,
				title : title,
				message : message
			});
			return;			
		}

		var addToSell = {};
		addToSell.invoice = invoice;
		addToSell.ProId = ProId;
		addToSell.quantity = quantity;
		addToSell.unityP = unityP;
		addToSell.custId = custId;
		addToSell.payment = payment;

		Data.post('/add/to/sell', {
			dashData : addToSell
		}).success(function(response) {
			if (response.status == "success") {
				var priority = 'success';
				var title = 'success';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
				 $scope.salesList();
				 saleStockPro.quantity;
			}
			 else {
				var priority = 'error';
				var title = 'error';
				var message = response.data;
				$.toaster({
					priority : priority,
					title : title,
					message : message
				});
			}
		}).error(function(err) {
			console.log(err);
		});
	};

	$scope.salesList = function(){
		Data.get('/selling/list').success(function(response) {
			$scope.sales_list = response.data;
		}).error(function(err) {
			console.warn(err);
		});
	};

});



//function that help me to minimizing my codes
function _(x) {
	return document.getElementById(x);
}

function hidden_error_sign_in() {
	_('isa_error').style.display = 'none';
		_('isa_success').style.display = 'none';
}

function hidden_error_add_cat() {
	_('isa_error_cat').style.display = 'none';
	_('isa_success_cat').style.display = 'none';
}
function hidden_error_edit_catName() {
	_('isa_error_editCategory').style.display = 'none';
	_('isa_success_editCategory').style.display = 'none';
}

function hidden_error_add_brand() {
	_('isa_error_brand').style.display = 'none';
	_('isa_success_brand').style.display = 'none';
}

function hidden_error_add_product() {
	_('isa_error_prod').style.display = 'none';
	_('isa_success_prod').style.display = 'none';
}

function hidden_error_customer() {
	_('isa_error_customer').style.display = 'none';
	_('isa_success_customer').style.display = 'none';
}

function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function validateNumber(number) {
	var re = /^\d+$/;
	return re.test(number);
}

