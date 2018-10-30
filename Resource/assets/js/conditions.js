var Condition = function () {
    var params;
    var productsClassData;
    var dataCategory = null;
    // var inputCurrentIds;
    var categoryNameAndId = {};
    var categoryList = [];
    var productList = [];

    var _minus = '<i class="fas fa-minus fa-lg font-weight-bold text-secondary"></i>';
    var _plus = '<i class="fa fa-plus fa-lg font-weight-bold text-secondary"></i>';

    var ruleForm = $('#ruleForm');
    var addProduct = '#addProduct';
    var addProductCategory = $('#addProductCategory');
    var mdAddCondition = $('.mdAddCondition');

    return {
        init: function (_params) {
            params = _params;
            console.log(params);

            this.events();
        },
        events: function () {
            $(addProduct).on('shown.bs.modal', function () {
                var rows = $(addProduct).find('table tbody tr');
                if (rows.length > 0) {
                    $.each(rows, function () {
                        Condition.handlePlusButton($(this));
                    });
                }
            });

            $("#addProductCategory").on('shown.bs.modal', function () {
                var valueIdInput = $('.mdAddCondition.show').find('input.inputConditionId').val();
                var tempArr = valueIdInput.split(',');
                $.each(tempArr, function (k, v) {
                    $("#addProductCategory").find('input[value="' + v + '"]').prop('checked', true);
                });
            });

            $(addProduct).on('click', '#searchProductModalButton', function () {
                var list = $('.searchDataModalList', $(this).closest('.modal-body'));
                $.ajax({
                    url: params.admin_order_search_product,
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        'id': $('#admin_search_product_id').val(),
                        'category_id': $('#admin_search_product_category_id').val()
                    }
                }).done(function (data) {
                    list.html(data);
                    productsClassData = productsClassCategories;
                    console.log(productsClassData);

                    var _button = list.find('table tbody tr td button');
                    $.each(_button, function () {
                        var dataAttr = $(this).attr('onclick');
                        var dataIds = dataAttr.split(',');
                        $(this).attr('data-id', $.trim(dataIds[1]));
                        $(this).attr('data-type', $.trim(dataIds[2]));
                        $(this).attr('data-action', 'plus');
                        $(this).removeAttr('onclick');
                    });

                    Condition.searchComplete();
                }).fail(function () {
                    alert('Search product failed.');
                });
            });

            addProductCategory.on('click', 'input[type="checkbox"]', function (e) {
                var catId = $(this).val();
                Condition.handleInputDataPopup(catId);
            });

            var sel1 = mdAddCondition.find('select[name="classcategory_id1"]');
            $.each(sel1, function () {
                $(this).on('change', function () {
                    var rowTr = $(this).closest('tr');
                    Condition.handlePlusButton(rowTr);
                });
            });

            mdAddCondition.on('click', 'table > tbody > tr > td > button.btn-ec-actionIcon', function (e) {
                var $sele1 = $(this).closest('tr').find('select[name=classcategory_id1]');
                var $sele2 = $(this).closest('tr').find('select[name=classcategory_id2]');

                if ($sele1.length && $sele1.val() == '__unselected') {
                    alert(params.msg_unselected_class);
                    return;
                }
                if ($sele2.length && !$sele2.val()) {
                    alert(params.msg_unselected_class);
                    return;
                }

                var ProductClass = Condition.fnAddConditionsProductClass($(this).closest('tr'), $(this).data('id'));
                if (ProductClass == undefined) {
                    return;
                }

                Condition.handleInputDataPopup(ProductClass['product_class_id']);
                Condition.handlePlusButton($(this).closest('tr'));
                Condition.handleProductClassName($(this).closest('tr'), ProductClass);
            });

            $('#ruleForm').on('click', '.findConditionsIds', function (e) {
                e.preventDefault();

                $(this).closest('td').find('.onFocus').removeClass('onFocus');
                $(this).closest('.condition-entity').addClass('onFocus');

                var conditionType = $(this).closest('.onFocus').find('[name="condition[type]"]').val();
                var dataIds = $(this).closest('.onFocus').find('[name="condition[value]"]').val();
                var mdAddCondition = $('.mdAddCondition');
                var inputConditionId = mdAddCondition.find('.inputConditionId');

                if (inputConditionId.length == 0) {
                    mdAddCondition.find('.searchDataModalList').before('<input class="inputConditionId form-control mb-2" value="' + dataIds + '">');
                } else {
                    inputConditionId.val(dataIds);
                }

                if (conditionType == 'condition_product_class_id') {
                    $(addProduct).modal('show');
                }

                if (conditionType == 'condition_product_category_id') {
                    Condition.getProductsCategory();
                }
            });

            $('#ruleForm').on('click', '.condition-entity .nameList li', function (e) {
                e.preventDefault();
                console.log($(this).data('id'));
                Condition.removeIdFromInputCondition($(this));
            });
        },
        handleProductClassName: function (_item, ProductClass) {
            console.log('__ProductClass', ProductClass);
        },
        removeIdFromInputCondition: function (_item) {
            var id = _item.data('id');
            var valueIdInput = _item.closest('.condition-entity').find('input[name="condition[value]"]');
            var newInputValue = Condition.calculatorInput(valueIdInput, id);
            valueIdInput.val(newInputValue);
            _item.slideUp("normal", function() { $(this).remove(); } );
        },
        addCategoriesToObject: function () {
            if ($.isEmptyObject(categoryNameAndId)) {
                var allCategories = addProductCategory.find('.searchDataModalList input');
                if (allCategories.length > 0) {
                    $.each(allCategories, function () {
                        var catId = $(this).val();
                        var catName = $(this).closest('li').find('> label[for="product-category-' + catId + '"]').text();
                        categoryNameAndId[catId] = catName;
                    });
                }
            }
        },
        getProductsCategory: function () {
            if (dataCategory === null) {
                $.ajax({
                    url: addProductCategory.data('url'),
                    type: 'GET',
                    dataType: 'html'
                }).done(function (data) {
                    dataCategory = data;
                    addProductCategory.find('.searchDataModalList').html(dataCategory);
                    addProductCategory.modal('show');
                    Condition.addCategoriesToObject();
                }).fail(function () {
                    alert('Search category failed.');
                });
            } else {
                addProductCategory.find('.searchDataModalList').html(dataCategory);
                addProductCategory.modal('show');
                Condition.addCategoriesToObject();
            }
        },
        renderListNameData: function (newInputValue) {
            var nameList = ruleForm.find('.condition-entity.onFocus .nameList');
            nameList.html('');
            $.each(newInputValue, function (k, id) {
                if (id && categoryNameAndId[id]) {
                    console.log('k id', k, id);
                    nameList.append('<li data-id="' + id + '"><a href="#"><i class="fas fa-times"></i></a> ' + categoryNameAndId[id] + '</li>');
                }
            });
        },
        handleInputDataPopup: function (dataId) {
            if (dataId == undefined) {
                return;
            }

            var valueIdInput = $('.mdAddCondition.show').find('input.inputConditionId');
            var newInputValue = Condition.calculatorInput(valueIdInput, dataId);
            Condition.setInputData(newInputValue);
            Condition.renderListNameData(newInputValue.split(','));
        },
        calculatorInput: function (valueIdInput, id) {
            var inputValue = valueIdInput.val() + ',';
            if (inputValue.indexOf(id + ',') != -1) {
                var tempArr = inputValue.split(',');
                var result = tempArr.filter(function (elem) {
                    return (elem != id && elem != '');
                });
                return result.toString();
            } else {
                return valueIdInput.val() ? (valueIdInput.val() + ',' + id) : id;
            }
        },
        setInputData: function (newValue) {
            $('.mdAddCondition.show').find('input.inputConditionId').val(newValue);
            $('#ruleForm').find('div.onFocus input[name="condition[value]"]').val(newValue);
        },
        searchComplete: function () {
            var sel1 = mdAddCondition.find('select[name="classcategory_id1"]');
            $.each(sel1, function () {
                $(this).on('change', function () {
                    var rowTr = $(this).closest('tr');
                    Condition.handlePlusButton(rowTr);
                });
            });

            var sel2 = mdAddCondition.find('select[name="classcategory_id2"]');
            $.each(sel2, function () {
                $(this).on('change', function () {
                    var rowTr = $(this).closest('tr');
                    Condition.handlePlusButton(rowTr);
                });
            });
        },
        handlePlusButton: function ($row) {
            var btnPlus = $row.find('.btn-ec-actionIcon');
            var ProductClass = Condition.fnAddConditionsProductClass($row, btnPlus.data('id'));
            if (ProductClass != undefined){
                var productClassId = ProductClass['product_class_id'];
                var inputConditionId = $(addProduct).find('input.inputConditionId');
                var currentValue = inputConditionId.val() + ',';

                if (currentValue.indexOf(productClassId + ',') != -1) {
                    btnPlus.attr('data-action', 'minus').html(_minus);
                    return;
                }
            }

            btnPlus.attr('data-action', 'plus').html(_plus);
            return;
        },
        fnAddConditionsProductClass: function ($row, product_id) {
            var product,
                class_category_id1,
                class_cateogry_id2;
            var $sele1 = $row.find('select[name=classcategory_id1]');
            var $sele2 = $row.find('select[name=classcategory_id2]');
            var product_class_id = null;

            if (!$sele1.length && !$sele2.length) {
                product = productsClassData[product_id]['__unselected2']['#'];
                // product_class_id = product['product_class_id'];
            } else if ($sele1.length) {
                if ($sele2.length) {
                    class_category_id1 = $sele1.val();
                    class_cateogry_id2 = $sele2.val();
                    if (class_category_id1 == '__unselected' || !class_cateogry_id2) {
                        return;
                    }
                    product = productsClassData[product_id][class_category_id1]['#' + class_cateogry_id2];
                    // product_class_id = product['product_class_id'];
                } else {
                    class_category_id1 = $sele1.val();
                    if (class_category_id1 == '__unselected') {
                        return;
                    }
                    product = productsClassData[product_id][class_category_id1]['#'];
                    // product_class_id = product['product_class_id'];
                }
            }
            alert('// TODO: get product name, cat1 name, cat2 name,...')
            return product;
        }
    }
}();