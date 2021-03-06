/*
This file contain jquery code that deal with the New Order Page neworder.php
 */

var NewProductData = "";
var newOrderProduct = "";
var addedProducts = [];

$(document).ready(function(){
    NewProductData = $(document).find("#NewProductData");
    newOrderProduct = $(document).find("div[id=newOrderProducts]");

    //**************************** { howHideNewProductForm button has been clicked } *****************************//
    $("#showHideNewProductForm").on("click", "button", function() {
        ToggleShowHideNewProductForm();
    });

    //**************************** { Change changeable td on the fly } *****************************//
    newOrderProduct.on("dblclick", ".editable", function() {
        ConvertChildrensInput($(this),"text", "editing");
        $(".editing").find("input").focus();
    });

    newOrderProduct.on("blur", ".editing", function() {
        ConvertChildrensInput($(this),"hidden", "editable");
    });

    //**************************** { Add Product to List From bottom form inputs } *****************************//
    $(document).on("click", "#AddProductButton", function() {
        var ProductData = [];
        NewProductData.find("input").each(function( index , Element) {
            ProductData[Element.id] = Element.value;

            //clear input after adding to array

            ChangeNameToInput("");
            var valueData = "";
            if (Element.id === "form-product-quantity")
                valueData = 1;
            Element.value = valueData;
        });
        //console.log(ProductData);
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

            newOrderProduct.find("tbody:last-child").append(ProductHtml);
            addedProducts.push(ProductData["form-product-barcode"]);
            showHideProductOrderTable("show");
        }
    });

    //**************************** { Remove Product From list button code } *****************************//
    $(document).on("click", "#RemoveNewProduct", function() {
        newOrderProduct.find("#"+$(this).attr("data-ProductId")).replaceWith("");
        addedProducts.splice(addedProducts.indexOf($(this).attr("data-Barcode")), 1);

        if (addedProducts.length === 0) {
            showHideProductOrderTable("hide");
            NewProductData.removeClass("framed");
        }
    });

    //**************************** { Client Data AutoFill } *****************************//
    $("#clientwantsemails").hide();
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
            if (ui.item.Email != null) {
                $("#form-checkwantsemails").prop('checked', true);
                $("#clientwantsemails").show(500);
            }
        },
        change: function (event, ui) {
            var PhoneNumber = $(this).val();
            AutoFillUserData(PhoneNumber);
        }
    });

    //**************************** { toggle email according to wantemail checkbox } *****************************//
    $(document).on("click", "#check-wantsemail", function() {
        if ($(this).children("#form-checkwantsemails").prop("checked")) {
            $("#clientwantsemails").show(500);
        }
        else {
            $("#clientwantsemails").hide(500);
        }
    });
});

function AutoFillUserData(phoneNumber) {
    var retData = DoAPIAjax("GetClientByPhoneNumber", phoneNumber);
    if (retData !== false && retData !== 0) {
        $("#form-FirstName").val(retData.FirstName);
        $("#form-LastName").val(retData.LastName);
        $("#form-Email").val(retData.Email);
        if (retData.Email !== "") {
            //$("#form-Email").attr("disabled", "true");
        }

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
       // $("#form-FirstName").val("").attr("disabled", "false");
       // $("#form-LastName").val("").attr("disabled", "false");
       // $("#form-Email").val("").attr({"disabled": "false"});
        $('input[name=wantsemail]').attr('checked', false);
        document.getElementById("clientwantsemails").className = "form-group";
    }
}



function showHideProductOrderTable(what) {
    switch (what) {
        case "hide":
            newOrderProduct.hide(defaultHideOptions);
            $("#CreateOrderButton").attr("disabled", true);
            NewProductData.show(defaultShowOptions);
            $("#showHideNewProductForm").hide(defaultHideOptions);
            SetShowHideButtonVisibility('show');
            break;

        case "show":
            default:
            newOrderProduct.show(defaultShowOptions);
            $("#CreateOrderButton").attr("disabled", false);
            NewProductData.hide(defaultHideOptions);
            $("#showHideNewProductForm").show(defaultShowOptions);
            SetShowHideButtonVisibility('show');
    }
}

function ToggleShowHideNewProductForm() {
    var isVisible = NewProductData.is(':visible');
    if (!isVisible) {
        SetShowHideButtonVisibility('hide');
        NewProductData.addClass("framed");
        NewProductData.show(defaultShowOptions);
    }
    else {
        SetShowHideButtonVisibility('show');
        NewProductData.removeClass("framed");
        NewProductData.hide(defaultHideOptions);
    }
}

function SetShowHideButtonVisibility(ShowHide) {
    switch (ShowHide) {
        case 'show': $("#showHideNewProductForm").find("button").replaceWith("<button type='button' class='btn btn-basic-improved2'><span class='glyphicon glyphicon-plus'></span>&nbsp;<span>???????? ???????? ??????</span></button>");
            break;
        case 'hide': $("#showHideNewProductForm").find("button").replaceWith("<button type='button' class='btn btn-basic-improved'><span class='glyphicon glyphicon-minus'></span>&nbsp;<span>???????? ????????????</span></button>");
            break;
    }
}