@extends('admin.layouts.app') @section('title')
<title>{{websiteTitle('Order Manage')}}</title>
@endsection @push('css')
<style type="text/css">
    .select2-container { width:100% !important; }
    .table td, .table th { vertical-align: middle; }
    .method-card { cursor:pointer; user-select:none; }
    .method-card.active { box-shadow: 0 0 0 2px rgba(0,123,255,.15); border:1px solid #007bff; }
    .small-input { width:100px; display:inline-block; }
    .inline-input-group { display:flex; gap:.5rem; align-items:center; }
    .payment-method-form .form-group { margin-bottom:.5rem; }
    #paymentListTable td, #paymentListTable th { vertical-align: middle; }
</style>
@endpush @section('contents')

<header class="page-title-bar">
    <div class="d-md-flex align-items-md-start">
        <div class="mr-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 p-0 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">POS Order</li>
                </ol>
            </nav>
        </div>
        <div class="btn-toolbar">
            <a href="{{route('admin.posOrders')}}" type="button" class="btn btn-outline-success mr-2">Back</a>
        </div>
    </div>
</header>


@include(adminTheme().'alerts')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">POS Sale</h4>
        <div>
            <button id="openDraftModal" class="btn btn-outline-secondary btn-sm ml-2">Drafts</button>
            <button id="newCart" class="btn btn-info btn-sm ml-2">🆕 New Cart</button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- LEFT -->
            <div class="col-md-8">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Search Product</label>
                        <select id="productSearch" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Barcode</label>
                        <div class="input-group">
                            <input id="barcodeInput" type="text" class="form-control" placeholder="Scan barcode ">
                            <div class="input-group-append">
                                <button id="barcodeBtn" class="btn btn-primary"><i class="fa fa-barcode"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered" id="cartTable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Product / Info</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Item Dis (৳)</th>
                            <th>Total</th>
                            <th style="width:50px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Grand Total:</th>
                            <th id="grandTotal">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- RIGHT -->
            <div class="col-md-4">
                <div class="mb-3">
                    <label>Customer</label>
                    <div class="row">
                        <div class="col-md-9">
                            <select id="customerSearch" class="form-control"></select>
                        </div>
                        <div class="col-md-3">
                            <button id="openAddCustomer" class="btn btn-outline-primary" data-toggle="modal" data-target="#addCustomerModal">+ Add</button>
                        </div>
                    </div>
                </div>

                <div class="border p-3 rounded bg-light">
                    <h5>Payment Summary</h5>
                    <p>Subtotal: <span id="subtotal">0.00</span></p>
                    <div class="row">
                        <label class="col-md-8" >Discount (%)</label>
                        <input id="discountPercent" type="number" class="col-md-4 form-control form-control-sm" value="0" min="0" max="100">
                    </div>
                    <div class="row">
                        <label  class="col-md-8" >Shipping (৳)</label>
                        <input id="shipping" type="number" class="col-md-4 form-control form-control-sm" value="0">
                    </div>
                    <div class="row">
                        <label class="col-md-8" >Adjustment (৳)</label>
                        <input id="adjustment" type="number" class="col-md-4 form-control form-control-sm" value="0">
                    </div>
                    <p><strong>Payable: <span id="payable">0.00</span></strong></p>
                </div>

                <div class="mt-3">
                    <label>Payment Methods</label>
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="card p-2 method-card mr-2 mb-2" data-method="cash" style="width:30%">
                            <div class="text-center"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="text-center small">Cash</div>
                        </div>
                        <div class="card p-2 method-card mr-2 mb-2" data-method="mobile" style="width:30%">
                            <div class="text-center"><i class="fas fa-mobile-alt"></i></div>
                            <div class="text-center small">Mobile</div>
                        </div>
                        <div class="card p-2 method-card mr-2 mb-2" data-method="card" style="width:30%">
                            <div class="text-center"><i class="fas fa-credit-card"></i></div>
                            <div class="text-center small">Card</div>
                        </div>
                    </div>

                    <!-- dynamic area for chosen method inputs -->
                    <div id="methodFormArea" class="mt-2"></div>

                    <button id="addPaymentBtn" class="btn btn-primary btn-block mt-2">Add Payment</button>

                    <h6 class="mt-3">Payments</h6>
                    <table class="table table-sm table-bordered" id="paymentListTable">
                        <thead>
                            <tr><th>Method</th><th>Details</th><th class="text-right">Amount</th><th></th></tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Paid</th>
                                <th id="totalPaid" class="text-right">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex">
                    <button id="completeSale" class="btn btn-success btn-block mr-1">Complete</button>
                    <button id="saveDraftBtn" class="btn btn-outline-warning btn-block ml-1 mt-0">Save Draft</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addCustomerForm" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add Customer</h5></div>
      <div class="modal-body">
        <div class="form-row">
            <div class="form-group col-7">
                <input type="text" id="newCustomerName" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-group col-5">
                <input type="text" id="newCustomerPhone" class="form-control" placeholder="Mobile" required>
            </div>
        </div>
        <div id="addCustomerMsg" class="small text-success"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Customer</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Item Edit Modal -->
<div class="modal fade" id="itemEditModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="itemEditForm" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Item</h5></div>
      <div class="modal-body">
        <input type="hidden" id="editItemId">
        <div class="form-group">
            <label>Item Name</label>
            <input type="text" id="editItemName" class="form-control">
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label>Price</label>
                <input type="number" id="editItemPrice" class="form-control">
            </div>
            <div class="form-group col-6">
                <label>Qty</label>
                <input type="number" id="editItemQty" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label>Item Discount (৳)</label>
            <input type="number" id="editItemDiscount" class="form-control" value="0">
        </div>
        <div class="form-group">
            <label>Extra Info (barcode/size/color)</label>
            <input type="text" id="editItemInfo" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </form>
  </div>
</div>

<!-- Draft List Modal -->
<div class="modal fade" id="draftListModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Draft Orders</h5></div>
      <div class="modal-body">
        <table class="table table-bordered" id="draftListTable">
            <thead><tr><th>#</th><th>Name</th><th>Created</th><th>Items</th><th>Action</th></tr></thead>
            <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection 

@push('js')

<script>
    $(function(){

        /* ------------------------
        Storage & Cart Helpers
        ------------------------ */
        const getCarts = () => JSON.parse(localStorage.getItem('carts') || '[]');
        const saveCarts = (c) => localStorage.setItem('carts', JSON.stringify(c));
        const getActiveId = () => localStorage.getItem('activeCartId');
        const setActiveId = (id) => localStorage.setItem('activeCartId', id);

        // Initialize new cart
        function newCart(){
            const carts = getCarts();
            const id = 'cart_' + Date.now();
            const cart = { id, name: 'Cart ' + (carts.length+1), items: [], discount:0, created_at: new Date().toISOString(), status:'active', customer:null, payments:[] };
            carts.push(cart);
            saveCarts(carts);
            setActiveId(id);
            loadCart();
            refreshDraftList();
        }

        // load cart to DOM
        function loadCart(){
            const id = getActiveId();
            if(!id) return;
            const carts = getCarts();
            const cart = carts.find(c=>c.id===id);
            if(!cart) return;
            // populate table
            $('#cartTable tbody').empty();
            cart.items.forEach(it => addRow(it, false));
            $('#discount').val(cart.discount||0);
            $('#shipping').val(cart.shipping||0);
            $('#adjustment').val(cart.adjustment||0);
            $('#discountPercent').val(cart.discountPercent||0);
            if(cart.customer) {
                // set customer select2 (option create if not exists)
                let opt = new Option(cart.customer.text || cart.customer.name, cart.customer.id, true, true);
                $('#customerSearch').append(opt).trigger('change');
            }
            // payments
            $('#paymentListTable tbody').empty();
            (cart.payments||[]).forEach(p=>addPaymentRowToTable(p));
            calcTotal();
        }

        // save active cart current DOM state
        function saveActiveCart(){
            const id = getActiveId();
            if(!id) return;
            const carts = getCarts();
            const cart = carts.find(c=>c.id===id);
            if(!cart) return;
            // gather items from table
            let items = [];
            $('#cartTable tbody tr').each(function(){
                const pid = $(this).data('id');
                const name = $(this).find('.td-name').text();
                const price = parseFloat($('#price_'+pid).val() || 0);
                const qty = parseFloat($('#qty_'+pid).val() || 0);
                const itemDiscount = parseFloat($('#itemdis_'+pid).val() || 0);
                const info = $(this).find('.td-info').data('info') || '';
                items.push({ id: pid, name, price, qty, itemDiscount, info });
            });
            cart.items = items;
            cart.discount = parseFloat($('#discount').val()||0);
            cart.discountPercent = parseFloat($('#discountPercent').val()||0);
            cart.shipping = parseFloat($('#shipping').val()||0);
            cart.adjustment = parseFloat($('#adjustment').val()||0);
            // customer
            const cust = $('#customerSearch').select2('data')[0];
            cart.customer = cust ? { id: cust.id, text: cust.text } : null;
            // payments from table
            const payments = [];
            $('#paymentListTable tbody tr').each(function(){
                payments.push($(this).data('payment'));
            });
            cart.payments = payments;
            saveCarts(carts);
            refreshDraftList();
        }

        /* ------------------------
        Initialize Select2 AJAX
        ------------------------ */
        $('#productSearch').select2({
            placeholder: 'Search product by name or SKU',
            allowClear:true,
            ajax: {
                delay:250,
                url: 'http://127.0.0.1:8000/api/products',
                dataType: 'json',
                data: function(params){ return { q: params.term }; },
                processResults: function(data){
                    // expecting either array or {data: []}
                    const list = data.data || data;
                    return { results: list.map(p => ({ id:p.id, text: p.name + ' - ' + (p.price||0), price: p.price || 0, full:p } )) };
                }
            }
        }).on('select2:select', function(e){
            const d = e.params.data;
            // add product: map to item structure
            const item = {
                id: d.id,
                name: d.full ? d.full.name : d.text,
                price: d.price || 0,
                qty: 1,
                itemDiscount: 0,
                info: (d.full && d.full.variants ? ('Variants: '+ (d.full.variants.map(v=>v.name).join(','))) : '')
            };
            addProduct(item);
            $(this).val(null).trigger('change');
        });

        /* Barcode lookup */
        function lookupBarcode(code){
            if(!code) return;
            $.ajax({
                url: 'http://127.0.0.1:8000/api/product-barcode',
                type: 'GET',
                data: { barcode: code },
                dataType: 'json',
                success: function(res){
                    // expecting single product or null
                    if(!res) { alert('Not found'); return; }
                    const p = res;
                    const item = { id: p.id, name: p.name, price: p.price||0, qty:1, itemDiscount:0, info: p.sku || '' };
                    addProduct(item);
                    $('#barcodeInput').val('');
                },
                error: function(){ alert('Barcode lookup failed'); }
            });
        }

        $('#barcodeBtn').on('click', function(){ lookupBarcode($('#barcodeInput').val().trim()); });
        $('#barcodeInput').on('keypress', function(e){ if(e.key==='Enter'){ e.preventDefault(); lookupBarcode($(this).val().trim()); } });

        /* Customer select2 */
        $('#customerSearch').select2({
            placeholder:'Search customer',
            allowClear:true,
            ajax:{
                delay:250,
                url:'http://127.0.0.1:8000/api/customer',
                dataType:'json',
                data:function(params){ return { q: params.term }; },
                processResults:function(data){
                    const list = data.data || data;
                    return { results: list.map(c=>({ id:c.id, text: c.name + ' ('+c.mobile+')', name:c.name, mobile:c.mobile })) };
                }
            }
        });

        /* Add customer via modal */
        $('#addCustomerForm').on('submit', function(e){
            e.preventDefault();
            const name = $('#newCustomerName').val().trim();
            const phone = $('#newCustomerPhone').val().trim();
            if(!name || !phone) return;
            $.ajax({
                url:'http://127.0.0.1:8000/api/add-customer',
                type:'POST',
                contentType:'application/json',
                data: JSON.stringify({ name, mobile: phone }),
                success:function(res){
                    // expecting created customer with id,name,mobile
                    $('#addCustomerMsg').text('Customer added');
                    $('#addCustomerModal').modal('hide');
                    // add to select2 and select
                    const opt = new Option(res.name + ' ('+res.mobile+')', res.id, true, true);
                    $('#customerSearch').append(opt).trigger('change');
                    $('#newCustomerName,#newCustomerPhone').val('');
                },
                error:function(){ $('#addCustomerMsg').text('Failed to add customer').css('color','red'); }
            });
        });

        /* ------------------------
        Cart Row & Edit
        ------------------------ */

        function addProduct(item){
            // if exists, increase qty
            if($('#row_'+item.id).length){
                const q = parseFloat($('#qty_'+item.id).val()) + (item.qty||1);
                $('#qty_'+item.id).val(q);
                updateRow(item.id);
                saveActiveCart();
                return;
            }
            addRow(item);
            calcTotal();
            saveActiveCart();
        }

        function addRow(p, save=true){
            // p: {id,name,price,qty,itemDiscount,info}
            const id = p.id;
            const name = p.name || p.text || 'Item';
            const price = (p.price||0).toFixed(2);
            const qty = p.qty || 1;
            const itemDiscount = (p.itemDiscount||0).toFixed(2);
            const info = p.info || '';
            const row = $(`
                <tr id="row_${id}" data-id="${id}">
                    <td></td>
                    <td>
                        <div class="td-name">${name}</div>
                        <div class="small text-muted td-info" data-info="${info}">${info}</div>
                    </td>
                    <td><input type="hidden" id="price_${id}" value="${price}">${price}</td>
                    <td><input type="number" id="qty_${id}" class="form-control form-control-sm" value="${qty}" style="width:70px"></td>
                    <td><input type="number" id="itemdis_${id}" class="form-control form-control-sm" value="${itemDiscount}" style="width:90px"></td>
                    <td id="total_${id}">${( (price*1) * qty - parseFloat(itemDiscount) ).toFixed(2)}</td>
                    <td>
                        <button title="Edit" class="btn btn-sm btn-link edit-item text-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger remove" data-id="${id}">&times;</button>
                    </td>
                </tr>`);
            $('#cartTable tbody').append(row);
            refreshRowNumbers();
            // events
            row.find('#qty_'+id).on('input', function(){ updateRow(id); saveActiveCart(); });
            row.find('#itemdis_'+id).on('input', function(){ updateRow(id); saveActiveCart(); });
            row.find('.remove').on('click', function(){ row.remove(); calcTotal(); saveActiveCart(); });
            row.find('.edit-item').on('click', function(){ openEditModal(id); });
            if(save) saveActiveCart();
        }

        function refreshRowNumbers(){
            $('#cartTable tbody tr').each(function(i){ $(this).find('td:first').text(i+1); });
        }

        function updateRow(id){
            const price = parseFloat($('#price_'+id).val()||0);
            const qty = parseFloat($('#qty_'+id).val()||0);
            const itdis = parseFloat($('#itemdis_'+id).val()||0);
            const total = (price * qty) - itdis;
            $('#total_'+id).text(total.toFixed(2));
            calcTotal();
        }

        /* Item Edit Modal logic */
        function openEditModal(id){
            const row = $('#row_'+id);
            if(!row.length) return;
            $('#editItemId').val(id);
            $('#editItemName').val(row.find('.td-name').text().trim());
            $('#editItemPrice').val(parseFloat($('#price_'+id).val()));
            $('#editItemQty').val(parseFloat($('#qty_'+id).val()));
            $('#editItemDiscount').val(parseFloat($('#itemdis_'+id).val()));
            $('#editItemInfo').val(row.find('.td-info').data('info') || '');
            $('#itemEditModal').modal('show');
        }

        $('#itemEditForm').on('submit', function(e){
            e.preventDefault();
            const id = $('#editItemId').val();
            $('#row_'+id).find('.td-name').text($('#editItemName').val());
            $('#row_'+id).find('#price_'+id).val(parseFloat($('#editItemPrice').val()).toFixed(2));
            $('#row_'+id).find('#qty_'+id).val(parseFloat($('#editItemQty').val()));
            $('#row_'+id).find('#itemdis_'+id).val(parseFloat($('#editItemDiscount').val()));
            $('#row_'+id).find('.td-info').data('info', $('#editItemInfo').val()).text($('#editItemInfo').val());
            updateRow(id);
            saveActiveCart();
            $('#itemEditModal').modal('hide');
        });

        /* ------------------------
        Totals & Payment Summary
        ------------------------ */
        function calcTotal(){
            let subtotal = 0;
            $('[id^=total_]').each(function(){ subtotal += parseFloat($(this).text()||0); });
            $('#subtotal').text(subtotal.toFixed(2));
            const discFixed = parseFloat($('#discount').val()||0);
            const discPercent = parseFloat($('#discountPercent').val()||0);
            const shipping = parseFloat($('#shipping').val()||0);
            const adjustment = parseFloat($('#adjustment').val()||0);
            const percentAmount = (discPercent>0) ? (subtotal * discPercent / 100) : 0;
            const totalDiscount = discFixed + percentAmount;
            const payable = subtotal - totalDiscount + shipping + adjustment;
            $('#payable').text(payable.toFixed(2));
            $('#grandTotal').text(subtotal.toFixed(2));
            // also update totalPaid vs payable
            updateTotalPaid();
        }

        /* recalc on inputs */
        $('#discount,#discountPercent,#shipping,#adjustment').on('input', function(){ calcTotal(); saveActiveCart(); });

        /* ------------------------
        Payment Methods
        ------------------------ */
        let selectedMethod = null;
        $('.method-card').on('click', function(){
            $('.method-card').removeClass('active');
            $(this).addClass('active');
            selectedMethod = $(this).data('method');
            renderMethodForm(selectedMethod);
        });

        function renderMethodForm(method){
            const area = $('#methodFormArea');
            area.empty();
            if(method==='cash'){
                area.append(`
                    <div class="payment-method-form">
                        <div class="form-group"><label>Cash Amount</label><input id="cashAmount" type="number" class="form-control form-control-sm" value="0"></div>
                        <div class="form-group"><input id="cashAuto" type="checkbox"> <label class="mb-0">Auto full amount</label></div>
                    </div>`);
                $('#cashAuto').on('change', function(){ if(this.checked){ $('#cashAmount').val(parseFloat($('#payable').text()||0).toFixed(2)); }});
            } else if(method==='mobile'){
                area.append(`
                    <div class="payment-method-form">
                        <div class="form-group"><label>Provider</label>
                            <select id="mobileProvider" class="form-control form-control-sm">
                                <option value="bkash">Bkash</option>
                                <option value="rocket">Rocket</option>
                                <option value="nagad">Nagad</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Amount</label><input id="mobileAmount" type="number" class="form-control form-control-sm" value="0"></div>
                        <div class="form-group"><label>Txn No</label><input id="mobileTxn" class="form-control form-control-sm"></div>
                    </div>`);
            } else if(method==='card'){
                area.append(`
                    <div class="payment-method-form">
                        <div class="form-group"><label>Bank</label>
                            <select id="cardBank" class="form-control form-control-sm">
                                <option>City Bank</option>
                                <option>DBBL</option>
                                <option>BRAC Bank</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Amount</label><input id="cardAmount" type="number" class="form-control form-control-sm" value="0"></div>
                        <div class="form-group"><label>Card No</label><input id="cardNo" class="form-control form-control-sm"></div>
                    </div>`);
            }
        }

        function addPaymentRowToTable(payment){
            // payment: { method, amount, details }
            const tr = $(`<tr>
                <td>${payment.method}</td>
                <td>${payment.details || ''}</td>
                <td class="text-right">${parseFloat(payment.amount).toFixed(2)}</td>
                <td><button class="btn btn-sm btn-danger remove-pay">x</button></td>
            </tr>`);
            tr.data('payment', payment);
            $('#paymentListTable tbody').append(tr);
            tr.find('.remove-pay').on('click', function(){ tr.remove(); saveActiveCart(); updateTotalPaid(); });
            updateTotalPaid();
        }

        $('#addPaymentBtn').on('click', function(){
            if(!selectedMethod){ alert('Select method first'); return; }
            let payment = { method: selectedMethod, amount:0, details: '' };
            if(selectedMethod==='cash'){
                payment.amount = parseFloat($('#cashAmount').val()||0);
                payment.details = 'Cash';
            } else if(selectedMethod==='mobile'){
                const prov = $('#mobileProvider').val();
                payment.amount = parseFloat($('#mobileAmount').val()||0);
                payment.details = prov + ' Txn:' + ($('#mobileTxn').val()||'');
            } else if(selectedMethod==='card'){
                const bank = $('#cardBank').val();
                payment.amount = parseFloat($('#cardAmount').val()||0);
                payment.details = bank + ' Card:' + ($('#cardNo').val()||'');
            }
            if(payment.amount <= 0){ alert('Enter amount'); return; }
            addPaymentRowToTable(payment);
            saveActiveCart();
        });

        /* total paid */
        function updateTotalPaid(){
            let total = 0;
            $('#paymentListTable tbody tr').each(function(){ total += parseFloat($(this).find('td:eq(2)').text()||0); });
            $('#totalPaid').text(total.toFixed(2));
        }

        /* ------------------------
        Save / Complete actions
        ------------------------ */

        $('#completeSale').on('click', function(){
            calcTotal(); saveActiveCart();
            const payable = parseFloat($('#payable').text()||0);
            const paid = parseFloat($('#totalPaid').text()||0);
            if(paid < payable){ if(!confirm('Paid amount less than payable. Continue?')) return; }
            // simulate submit -> here you would POST to server
            const id = getActiveId();
            // remove cart
            let carts = getCarts().filter(c=>c.id !== id);
            saveCarts(carts);
            localStorage.removeItem('activeCartId');
            alert('Order submitted (simulated). Cart removed.');
            // reset UI
            $('#cartTable tbody').empty();
            $('#paymentListTable tbody').empty();
            $('#subtotal,#grandTotal,#payable,#totalPaid').text('0.00');
            $('#discount,#discountPercent,#shipping,#adjustment').val('0');
            $('#customerSearch').val(null).trigger('change');
            newCart();
        });

        $('#saveDraftBtn, #saveDraft').on('click', function(){
            saveActiveCart();
            // mark active cart as draft explicitly
            const id = getActiveId();
            const carts = getCarts();
            carts.forEach(c=> c.status = (c.id === id ? 'draft' : c.status));
            saveCarts(carts);
            refreshDraftList();
            alert('Saved as draft');
        });

        /* New cart */
        $('#newCart').on('click', function(){ newCart(); });

        /* Draft selector change => make active */
        $('#draftSelector').on('change', function(){
            const id = $(this).val();
            if(!id) return;
            // mark all draft and set selected active
            const carts = getCarts();
            carts.forEach(c => c.status = (c.id === id ? 'active' : (c.status==='active' ? 'draft' : c.status)));
            saveCarts(carts);
            setActiveId(id);
            loadCart();
        });

        /* Draft list modal */
        $('#openDraftModal').on('click', function(){
            const rows = getCarts().filter(c=>c.status==='draft').map((d,i)=>({
                idx:i+1,
                id:d.id,
                name:d.name,
                created: new Date(d.created_at).toLocaleString(),
                items: d.items.length
            }));
            const tbody = $('#draftListTable tbody').empty();
            if(rows.length===0) tbody.append('<tr><td colspan="5" class="text-center text-muted">No drafts</td></tr>');
            rows.forEach(r=>{
                const tr = $(`<tr>
                    <td>${r.idx}</td>
                    <td>${r.name}</td>
                    <td>${r.created}</td>
                    <td>${r.items}</td>
                    <td>
                        <button class="btn btn-sm btn-primary load-draft" data-id="${r.id}">Load</button>
                        <button class="btn btn-sm btn-danger del-draft" data-id="${r.id}">Delete</button>
                    </td>
                </tr>`);
                tbody.append(tr);
            });
            $('#draftListModal').modal('show');
        });

        /* load or delete draft actions */
        $(document).on('click', '.load-draft', function(){
            const id = $(this).data('id');
            // make this active
            const carts = getCarts();
            carts.forEach(c => c.status = (c.id === id ? 'active' : (c.status==='active' ? 'draft' : c.status)));
            saveCarts(carts);
            setActiveId(id);
            loadCart();
            $('#draftListModal').modal('hide');
        });
        $(document).on('click', '.del-draft', function(){
            const id = $(this).data('id');
            if(!confirm('Delete draft?')) return;
            let carts = getCarts().filter(c => c.id !== id);
            saveCarts(carts);
            $(this).closest('tr').remove();
            refreshDraftList();
        });


        /* add payment removal update handler on load (existing payments) */
        function rebindPayments(){
            $('#paymentListTable tbody tr').each(function(){
                $(this).find('.remove-pay').on('click', function(){ $(this).closest('tr').remove(); saveActiveCart(); updateTotalPaid(); });
            });
        }

        /* init */
        if(!getActiveId()) newCart(); else { refreshDraftList(); loadCart(); }
        calcTotal();

        });

</script>

@endpush