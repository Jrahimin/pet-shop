<?php

namespace App\Console\Commands;

use App\Model\Cart;
use Illuminate\Console\Command;

class DeleteCartProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:cart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all cart and cart products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $carts = Cart::all();
        $currentTime = date('h:i:s', time());
        foreach ($carts as $cart)
        {
            $timeDifference = (strtotime($currentTime) - strtotime($cart->last_active)) / 60;
            if ($timeDifference > 30)
            {
                $cart->cartProducts()->delete();
                $cart->delete();
            }
        }
    }
}
