

// ========================================================
// ==== On DOM Ready ======================================
// ========================================================
$( document ).ready(function() {
    getAllCategories();
    getAllProducts();
});




// ========================================================
// ==== Functions =========================================
// ========================================================

// ============================================
// ==== Get All Categories ====================
// ============================================
/**
 * Ajax Request - get all Categories.
 */
function getAllCategories() {
    $.ajax({
        type : 'GET',
        dataType : 'json',
        url: 'http://symfony.projects/satc/web/app_dev.php/category/list',
        success : function(data) {
            if(data.status=="success"){
                buildTableCategories(data.cateogries);
                updateAllCategoriesCount(data.cateogries);
                buildCategorySelectorDropdown(data.cateogries);
            }else{
                alert(data.status);
            }

        },
        error : function (data) {
            console.log(
                'Ajax Failed: GetAllCategories' + '\n' +
                'Status Code: ' + data.status + '\n' +
                'StatusText: ' + data.statusText
            );
        }
    });
}


/**
 * fills in the Categories HTML table.
 */
function buildTableCategories(data) {
    var tableBodyCategories = $('#tableBodyCategories');

    for(var i = 0; i < data.length; i++) {
        var obj = data[i];
        // add the record to the html table.
        tableBodyCategories.append( "" +
            "<tr>" +
            "<td>" + obj.id +"</td>" +
            "<td>" + obj.name +"</td>" +
            "</tr>" );
    }
}
/**
 * Updates the Categories count.
 */
function updateAllCategoriesCount(data) {
    var countAllCategories= $('#countAllCategories');
    // show product count
    countAllCategories.text(data.length);
}


// ============================================
// ==== Get All Products ======================
// ============================================
/**
 * Ajax Request - get all products.
 */
function getAllProducts() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'http://symfony.projects/satc/web/app_dev.php/product/list',
        success: function (data) {
            if(data.status=="success"){
                buildTableAllProducts(data.products);
                updateAllProductsCount(data.products);
            }else{
                alert(data.status);
            }
        },
        error : function (data) {
            console.log(
                'Ajax Failed: Products' + '\n' +
                'Status Code: ' + data.status + '\n' +
                'StatusText: ' + data.statusText
            );
        }
    });
}

/**
 * fills in the the HTML table.
 */
function buildTableAllProducts(data) {
    var tableBodyAllProducts= $('#tableBodyAllProducts');

    for(var i = 0; i < data.length; i++) {
        var obj = data[i];

        var productCategories = '';
        for (var c = 0; c < obj.categories.length; c++) {
            productCategories += obj.categories[c]['name'] + ', ';
        }
        if(productCategories !== '') {
            productCategories = productCategories.slice(0, -2);
        }


        // add the record to the html table.
        tableBodyAllProducts.append( "" +
            "<tr>" +
            "<td>" + obj.id +"</td>" +
            "<td>" + obj.name +"</td>" +
            "<td>" + productCategories + "</td>" +
            "</tr>" );
    }
}
/**
 * Updates the All Products count.
 */
function updateAllProductsCount(data) {
    var countAllProducts= $('#countAllProducts');
    // show product count
    countAllProducts.text(data.length);
}

// ============================================
// ==== Get All Products By Category ==========
// ============================================

/**
 * Ajax Request - get all products By Category.
 */
function getAllProductsByCategoryId(categoryId) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        data: {
            categoryId: categoryId
        },
        url: 'http://symfony.projects/satc/web/app_dev.php/product/category/'+categoryId,
        success: function (data) {
            buildTableProductsByCategory(data.products);
            updateAllProductsByCategoryCount(data.products);
        },
        error : function (data) {
            console.log(
                'Ajax Failed: Products' + '\n' +
                'Status Code: ' + data.status + '\n' +
                'StatusText: ' + data.statusText
            );
        }
    });
}

/**
 * fills in the the HTML table.
 */
function buildTableProductsByCategory(data) {
    var tableBodyProductsByCategory= $('#tableBodyProductsByCategory');
    tableBodyProductsByCategory.empty();
    for(var i = 0; i < data.length; i++) {
        var obj = data[i];

        var productCategories = '';
        for (var c = 0; c < obj.categories.length; c++) {
            productCategories += obj.categories[c]['name'] + ', ';
        }
        if(productCategories !== '') {
            productCategories = productCategories.slice(0, -2);
        }


        // add the record to the html table.
        tableBodyProductsByCategory.append( "" +
            "<tr>" +
            "<td>" + obj.id +"</td>" +
            "<td>" + obj.name +"</td>" +
            "<td>" + productCategories + "</td>" +
            "</tr>" );
    }
}


/**
 * Updates the All Products By Category count.
 */
function updateAllProductsByCategoryCount(data) {
    var countAllProductsByCategory = $('#countAllProductsByCategory');
    // show product count
    countAllProductsByCategory.text(data.length);


}






// ============================================
// ==== Category Selector =====================
// ============================================


function buildCategorySelectorDropdown(data) {
    var categoryList = $('#categoryList');
    categoryList.empty();
    // add the All Categories Option.
    categoryList.append('<li><a data-catid="0" data-catname="All Categories">All Categories</a></li>');
    for(var i = 0; i < data.length; i++) {
        var obj = data[i];
        // add the record to the html table.
        categoryList.append('<li><a data-catid="' + obj.id +'" data-catname="' + obj.name + '"</a>' + obj.name + '</li>');
    }
    var categoryListForProductForm = $('#productcategory');
    categoryListForProductForm.append('<option val="">Select Category</option>');
    for(var i = 0; i < data.length; i++) {
        var obj = data[i];
        // add the record to the html table.
        categoryListForProductForm.append('<option value="' + obj.id +'">'+obj.name+'</option>');
    }
    // add click event listeners to the dropdown.
    $('#categoryList li a').on('click', function () {
        // get current category data
        var categoryId = $(this).data("catid");
        var categoryName = $(this).data("catname");
        // update current category in UI
        $('#currentCategory').text(categoryName);
        // get all products by the category (and update the table).
        getAllProductsByCategoryId(categoryId);
    })
}

// ============================================
// ==== ADD/Edit Modal Form =====================
// 

function showFormModal(data){
    
}

function submitForm(elm){
    var formId=elm.getAttribute("data-form-id");
    var form=$(formId);
    var endPoint=elm.getAttribute("data-end-point");
    //elm.setAttribute("disabled",true);
    form.submit(function(){
        var name=$("#"+endPoint+"name").val();
        var data;
        if(endPoint=="product"){
            var name=$("#"+endPoint+"name").val();
            var category=$("#productcategory").val();
            data={name:name,category:category};
        }
        if(endPoint=="category"){
            var name=$("#"+endPoint+"name").val();
            data={name:name};
        }
          $.ajax({
                type: 'POST',
                //dataType: 'json',
                data: data,
                url: 'http://symfony.projects/satc/web/app_dev.php/'+endPoint+'/add',
                success: function (data) {
                    alert(data.status);
                    //elm.setAttribute("disabled",false);
                    location.reload();
                },
                error : function (data) {
                    console.log(
                        'Ajax Failed: Products' + '\n' +
                        'Status Code: ' + data.status + '\n' +
                        'StatusText: ' + data.statusText
                    );
                }
            });
        return false;
    });
    
}