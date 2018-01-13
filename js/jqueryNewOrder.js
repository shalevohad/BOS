/*
This file contain jquery code that deal with the New Order Page neworder.php
 */

var AddButton = "";
var NewProductData = "";
var OrderProducts = "";
var addedProducts = [];

$(document).ready(function(){
    AddButton = $(document).find("#AddProductButton");
    NewProductData = $(document).find("#NewProductData");
    OrderProducts = $(document).find("#OrderProducts");


    //**************************** { howHideNewProductForm button has been clicked } *****************************//
    $("#showHideNewProductForm").on("click", "button", function() {
        ToggleShowHideNewProductForm();
    });

    //**************************** { Change changeable td on the fly } *****************************//
    OrderProducts.on("click", ".editable", function() {
        ConvertChildrensInput($(this),"text", "editing");
        //$(this).replaceWith("<td class='editing'><input type='text' data-ProductId='" + $(this).find("input").attr("data-ProductId") + "' name='" + $(this).find("input").attr("name") + "' value='" + $(this).find("input").val() + "'></td>");
        $(".editing").find("input").focus();
    });
    OrderProducts.on("blur", ".editing", function() {
        ConvertChildrensInput($(this),"hidden", "editable");
        //$(this).replaceWith("<td class='editable'><input type='hidden' data-ProductId='" + $(this).find("input").attr("data-ProductId") + "' name='" + $(this).find("input").attr("name") + "' value='" + $(this).find("input").val() + "'><span>" + $(this).find("input").val() + "</span></td>");
    });

    //**************************** { Add Product to List From bottom form inputs } *****************************//
    AddButton.on("click", function() {
        var ProductData = [];
        NewProductData.find("input").each(function( index , Element) {
            ProductData[Element.id] = Element.value;

            //clear input after adding to array
            var valueData = "";
            if (Element.id == "form-product-quantity")
                valueData = 1;
            Element.value = valueData;
        });
        ChangeBarcodeToInput();

        if (ProductData["form-product-barcode"] === "" && ProductData["form-product-name"] === "") {
            alert("Unable to add empty product to 'Products List'!\nTry to insert some product data first and then 'hit' the add button");
        }
        else if (addedProducts.indexOf(ProductData["form-product-barcode"]) !== -1) {
            alert("Product with barcode " +ProductData["form-product-barcode"]+ " already added to the list!\nUnable to add product!");
        }
        else {
            var ProductId = "product_" + ProductData["form-product-barcode"];
            var ProductHtml = "<tr id='" +ProductId+ "'>" +
                "<td><input type='hidden' data-ProductId='"+ProductId+"' name='"+ProductId+"_Name' value='" + ProductData["form-product-name"] + "'><span>" + ProductData["form-product-name"] + "</span></td>" +
                "<td><input type='hidden' data-ProductId='"+ProductId+"' name='"+ProductId+"_Barcode' value='" + ProductData["form-product-barcode"] + "'><span>" + ProductData["form-product-barcode"] + "</span></td>" +
                "<td class='editable'><input type='hidden' data-ProductId='"+ProductId+"' name='"+ProductId+"_Quantity' value='" + ProductData["form-product-quantity"] + "'><span>" + ProductData["form-product-quantity"] + "</span></td>" +
                "<td class='editable'><input type='hidden' data-ProductId='"+ProductId+"' name='"+ProductId+"_Remark' value='" + ProductData["form-product-remarks"] + "'><span>" + ProductData["form-product-remarks"] + "</span></td>" +
                "<td><button type='button' id='RemoveNewProduct' class='btn btn-danger' data-ProductId='" +ProductId+ "' data-Barcode='" +ProductData["form-product-barcode"]+ "'><span class = 'glyphicon glyphicon-minus'></span></button></td>" +
                "</tr>";

            OrderProducts.find("table > tbody:last-child").append(ProductHtml);
            addedProducts.push(ProductData["form-product-barcode"]);
            showHideProductOrderTable("show");
        }
    });

    //**************************** { Remove Product From list button code } *****************************//
    $(document).on("click", "#RemoveNewProduct", function() {
        //console.log("Remove Button clicked!");
        OrderProducts.find("#"+$(this).attr("data-ProductId")).empty();
        addedProducts.splice(addedProducts.indexOf($(this).attr("data-Barcode")), 1);

        if (addedProducts.length === 0)
            showHideProductOrderTable("hide");
    });

    //**************************** { Client Data AutoFill } *****************************//
    $("#form-PhoneNumber").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "API_CALLS.php?method=SearchClient",
                dataType: "json",
                data: {
                    data: request.term + "|PhoneNumber"
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            AutoFillUserData(ui.item.PhoneNumber);
        },
        change: function (event, ui) {
            var PhoneNumber = $(this).val();
            AutoFillUserData(PhoneNumber);
        }
    });
});

function AutoFillUserData(phoneNumber) {
    var retData = DoAPIAjax("GetClientByPhoneNumber", phoneNumber);
    if (retData !== false && retData !== 0) {
        $("#form-FirstName").val(retData.FirstName);
        $("#form-LastName").val(retData.LastName);
        $("#form-Email").val(retData.Email);
        if (retData.ClientWantsMails === 1) {
            $('input[name=wantsemail]').attr('checked', true);
            document.getElementById("clientwantsemails").className = "open";
        }
        else {
            $('input[name=wantsemail]').attr('checked', false);
            document.getElementById("clientwantsemails").className = "form-group";
        }
    }
    else {
        $("#form-FirstName").val("");
        $("#form-LastName").val("");
        $("#form-Email").val("");
        $('input[name=wantsemail]').attr('checked', false);
        document.getElementById("clientwantsemails").className = "form-group";
    }
}

function showHideProductOrderTable(what) {
    switch (what) {
        case "hide":
            OrderProducts.hide(defaultHideOptions);
            $("#CreateOrderButton").attr("disabled", true);
            NewProductData.show(defaultShowOptions);
            $("#showHideNewProductForm").hide(defaultHideOptions);
            SetShowHideButtonVisibility('show');
            break;

        case "show":
            default:
            OrderProducts.show(defaultShowOptions);
            $("#CreateOrderButton").attr("disabled", false);
            NewProductData.hide(defaultHideOptions);
            $("#showHideNewProductForm").show(defaultShowOptions);
    }
}

function ToggleShowHideNewProductForm() {
    var isVisible = NewProductData.is(':visible');
    if (!isVisible) {
        SetShowHideButtonVisibility('hide');
        NewProductData.show(defaultShowOptions);
    }
    else {
        SetShowHideButtonVisibility('show');
        NewProductData.hide(defaultHideOptions);
    }
}

function SetShowHideButtonVisibility(ShowHide) {
    switch (ShowHide) {
        case 'show': $("#showHideNewProductForm").find("button").replaceWith("<button type='button' class='btn btn-success'><span class='glyphicon glyphicon-plus'></span><span>הוסף מוצר חדש</span></button>");
            break;
        case 'hide': $("#showHideNewProductForm").find("button").replaceWith("<button type='button' class='btn btn-warning'><span class='glyphicon glyphicon-minus'></span><span>הסתר חלונית</span></button>");
            break;
    }
}