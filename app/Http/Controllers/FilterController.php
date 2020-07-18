<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    public function index() 
    {
        // if(DB::table('products')->exists() && DB::table('categories')->exists() )
        // {
        //     DB::table('categories')->delete();
        //     DB::table('products')->delete();
        //     $this->index();
            
        // } else {
        //     // 1) Buty PUMA
        //     DB::insert('insert into products (id, nazwa) values (?, ?)', [1, 'Buty Puma']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [1, 'Sneakers']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [1, 'Nowość']);
            
        //     // 2) Trampki Converse
        //     DB::insert('insert into products (id, nazwa) values (?, ?)', [2, 'Trampki Converse']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [2, 'Trampki']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [2, 'Nowość']);
        //     // 3) Trampki Vans
        //     DB::insert('insert into products (id, nazwa) values (?, ?)', [3, 'Trampki Vans']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [3, 'Trampki']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [3, 'Outlet']);
        //     // 4) Trampki DC test 
        //     DB::insert('insert into products (id, nazwa) values (?, ?)', [5, 'Trampki DC']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [5, 'Trampki']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [5, 'Nowość']);
        //     // 5)Buty Fila
        //     DB::insert('insert into products (id, nazwa) values (?, ?)', [4, 'Buty Fila']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [4, 'Sneakers']);
        //     DB::insert('insert into categories (id_produktu, nazwa) values (?, ?)', [4, 'Outlet']);
        
        // }

        $results =DB::select("SELECT p.nazwa FROM categories as outer_categories JOIN products as p ON outer_categories.id_produktu = p.id WHERE outer_categories.nazwa = 'Trampki' AND 
        (SELECT COUNT(id_produktu) FROM categories as inner_categories WHERE inner_categories.id_produktu = outer_categories.id_produktu AND nazwa='Outlet') = 0;");
                
        return view('result', compact('results'));
    }
}


