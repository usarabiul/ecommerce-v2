<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use Hash;
use Mail;
use File;
use DB;
use Session;
use Cookie;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\General;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use GuzzleHttp\Client;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

	public function orders(Request $r,$status=null){

        // Filter Action Start
      if($r->action){
        if($r->checkid){
            $datas=Order::latest()->where('order_type','customer_order')->whereIn('id',$r->checkid);
            if($r->action==1){
                $datas->update(['order_status'=>'pending']);
            }elseif($r->action==2){
                $datas->update(['order_status'=>'confirmed']);
            }elseif($r->action==3){
                $datas->update(['order_status'=>'shipped']);
            }elseif($r->action==4){
                $datas->update(['order_status'=>'delivered']);
            }elseif($r->action==5){
                $datas->update(['order_status'=>'cancelled']);
            }elseif($r->action==6){
                foreach($datas->get(['id']) as $data){
                    $data->items()->delete();
                    $data->delete();
                }
            }
            Session()->flash('success','Action Successfully Completed!');
        }else{
            Session()->flash('info','Please Need To Select Minimum One Post');
        }
        return redirect()->back();
      }
      //Filter Action End

        if($status==null || $status=='pending-payment' || $status=='unpaid' || $status=='pending' || $status=='confirmed' || $status=='shipped' || $status=='delivered' || $status=='cancelled'){

            $orders =Order::latest()->where('order_type','customer_order')->where('order_status','<>','temp')
            ->where(function($qq)  use ($status,$r)  {
                if($status){
                    if($status=='pending-payment'){
                        $qq->where('payment_method',null);
                    }else if($status=='unpaid'){
                        $qq->whereIn('payment_status',['unpaid','partial']);
                    }else{
                        $qq->where('order_status',$status);
                    }
                }
                
                if($r->search){
                   $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
                }
                
                if($r->startDate || $r->endDate)
                {
                    if($r->startDate){
                        $from =$r->startDate;
                    }else{
                        $from=Carbon::now()->format('Y-m-d');
                    }

                    if($r->endDate){
                        $to =$r->endDate;
                    }else{
                        $to=Carbon::now()->format('Y-m-d');
                    }
                    $qq->whereBetween('created_at', [$from, $to]);
                }
            })
            ->paginate(25)->appends(['search'=>$r->search,'status'=>$r->status,'startDate'=>$r->startDate,'endDate'=>$r->endDate]);

            //Total Count Results
            $totals = Order::where('order_type','customer_order')
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when order_status = 'pending' then 1 end) as pending")
            ->selectRaw("count(case when order_status = 'confirmed' then 1 end) as confirmed")
            ->selectRaw("count(case when order_status = 'shipped' then 1 end) as shipped")
            ->selectRaw("count(case when order_status = 'delivered' then 1 end) as delivered")
            ->selectRaw("count(case when order_status = 'cancelled' then 1 end) as cancelled")
            ->first();

            return view(adminTheme().'orders.ordersAll',compact('orders','totals','status'));


        }else{
            Session()->flash('error','Order Status Un-known Type');
            return redirect()->route('admin.orders');
        }

        
    }

    public function ordersAction(Request $r,$action,$id){
        $order =Order::find($id);
        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.orders');
        }

        if($action=='invoice'){

            return view(adminTheme().'orders.invoice',compact('order'));
        }

        if($action=='search'){

            $key = $r->search;

            $products =Post::latest()->where('type',2)->where('status','active')
                ->where('name','like','%'.$key.'%')
                ->limit(10)     
                ->get();


            $view =View(adminTheme().'orders.includes.searchItem',compact('products','order'))->render();

            return Response()->json([
                    'success' => true,
                    'view' => $view,
                    ]);
        }

        if($action=='update'){
            return $r;
            $check = $r->validate([
                'order_status' => 'required',
            ]);
            if($order->order_status!=$r->order_status){
                $order->order_status=$r->order_status;
                $orderStatus =$r->order_status;
                if($orderStatus=='pending' || $orderStatus=='confirmed' || $orderStatus=='shipped' || $orderStatus=='delivered' || $orderStatus=='cancelled' ){
                    $columD =$orderStatus.'_at';
                    $columBy =$orderStatus.'_by';
                    $order[$columD]=Carbon::now();
                    $order[$columBy]=Auth::id();
                }
            
                foreach($order->items as $item){
                    if($item->order_status=='cancelled'){
                        
                    }else{
                        
                        if($order->order_status=='cancelled'){
                            if($item->product){
                                $product =$item->product;
                                if($product->variation_status){
                                    
                                }else{
                                    
                                    $product->quantity+=$item->quantity;
                                    if($product->sell_count > 0){
                                    $product->sell_count-=1;
                                    }
                                    $product->save();
                                }
                            } 
                        }
                        
                    }
                    $item->order_status=$order->order_status;
                    if($order->order_status=='confirmed'){
                    $item->confirmed_at=$order->confirmed_at;
                    $item->confirmed_by=$order->confirmed_by;
                    
                    //   if($item->product){
                    //         $product =$item->product;
                    //         if($product->variation_status){
                                
                    //         }else{
                                
                    //             if($product->quantity >= $item->quantity){
                                    
                    //                 $product->quantity-=$item->quantity;
                    //                 $product->sell_count+=1;
                    //                 $product->save();
                                    
                    //             }
                                
                    //         }
                    //     }
                        
                    }elseif($order->order_status=='shipped'){
                    $item->shipped_at=$order->shipped_at;
                    $item->shipped_by=$order->shipped_by;
                    }elseif($order->order_status=='delivered'){
                    $item->delivered_at=$order->delivered_at;
                    $item->delivered_by=$order->delivered_by;
                    }
                    $item->save();
                }
                $order->save();
            }
            //send mail or sms

            Session()->flash('success','Order Update Successfully Done!');
            return redirect()->back();

        }

        if($action=='payment'){
       
            $check = $r->validate([
                'amount' => 'required|numeric',
                'method' => 'required|numeric',
            ]);
            $method =Attribute::where('type',11)->find($r->method);
            if(!$method){
                Session()->flash('error','Method Type Are Found');
                return redirect()->back();
            }

            $transaction =new Transaction();
            $transaction->src_id =$order->id;
            if($r->transaction_type==1){
                $transaction->type =2;
            }else{
               $transaction->type =0; 
            }
            $transaction->status ='success';
            $transaction->method_id=$method->id;
            $transaction->transection_id=Carbon::now()->format('YmdHis');
            $transaction->amount=$r->amount;
            $transaction->billing_note=$r->note;
            $transaction->billing_name=$order->name;
            $transaction->billing_mobile=$order->mobile;
            $transaction->billing_email=$order->email;
            $transaction->billing_address=$order->address;
            $transaction->save();

            //Method Balance Update
            if($transaction->type==1){
                $method->amounts -=$transaction->amount;
            }else{
                $method->amounts +=$transaction->amount;
            }            
            $method->save();

            //Order payment Update
            $order->paid_amount=$order->transactionsSuccess->sum('amount');
            $order->return_amount=$order->transactionsRefund->sum('amount');

            if($order->grand_total >= $order->paid_amount){
                $order->due_amount=$order->grand_total-$order->paid_amount;
                $order->extra_amount=0;
            }else{
                $order->due_amount=0; 
                $order->extra_amount=$order->paid_amount-$order->grand_total; 
            }
            
            if($order->due_amount==0){
            $order->payment_status='paid';
            }elseif($order->due_amount==$order->grand_total){
            $order->payment_status='unpaid';
            }else{
            $order->payment_status='partial';
            }
            $order->save();
            if($transaction->type==1){
                Session()->flash('success','Payment Refund Successfully Done!');
            }else{
                Session()->flash('success','Payment Added Successfully Done!');
            }
            return redirect()->back();
        }

        $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->orderBy('view','asc')->get();
        return view(adminTheme().'orders.ordersManage',compact('order','methods'));
    }

    public function posOrdersAction(Request $r,$action,$id=null){


        if($action=='sale'){

            $order=null;
            return view(adminTheme().'orders-pos.posSale',compact('order'));
        }
    }

}