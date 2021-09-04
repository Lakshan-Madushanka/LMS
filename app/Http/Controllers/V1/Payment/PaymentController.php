<?php

namespace App\Http\Controllers\V1\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class PaymentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {

       // $user = Cashier::findBillable(1);
        $user = User::find(11);
//        $user->applyBalance(100000, 'bonus');
       // $data = $user->taxIds();
       // return $user->redirectToBillingPortal();
         $data = $user->paymentMethods();

        print_r($data);

        //return $this->showOne('', '', '', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
